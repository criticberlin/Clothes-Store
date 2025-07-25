<?php

namespace App\Http\Controllers\Web;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
//use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use App\Mail\PasswordResetEmail;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    use ValidatesRequests;

    public function __construct()
    {
        // Constructor fixed (removing middleware for now)
    }

    public function register(Request $request)
    {
        return view('users.register');
    }

    public function doRegister(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:5'],
                'email' => ['required', 'email', 'unique:users'],
                // Make password requirements less complex for testing
                'password' => ['required', 'min:8', 'confirmed'],
            ]);

            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->password = bcrypt($validated['password']);
            $user->email_verified_at = now(); // Auto-verify for testing
            $user->save();
            
            // Assign the customer role using direct DB operations
            if ($roleId = DB::table('roles')->where('name', 'customer')->value('id')) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $roleId,
                    'model_type' => User::class,
                    'model_id' => $user->id
                ]);
            }

            // Auto-login the user
            Auth::login($user);
            
            return redirect('/')->with('success', 'Registration successful!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput($request->except('password', 'password_confirmation'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Registration failed: ' . $e->getMessage())
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }

    public function login(Request $request)
    {
        return view('users.login');
    }

    public function doLogin(Request $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->back()->withInput($request->input())->withErrors('Invalid login information.');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user->email_verified_at) {
            Auth::logout();
            return redirect()->back()
                ->withInput($request->input())
                ->withErrors('Your email is not verified.');
        }

        Auth::setUser($user);

        // Check if the user has Admin role
        $hasAdminRole = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', $user->id)
            ->where('model_has_roles.model_type', 'App\\Models\\User')
            ->where('roles.name', 'Admin')
            ->exists();

        // Redirect admin users to the admin dashboard
        if ($hasAdminRole) {
            return redirect()->route('admin.dashboard');
        }

        return redirect('/');
    }

    public function doLogout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    public function verify(Request $request)
    {
        try {
            $decryptedData = json_decode(Crypt::decryptString($request->token), true);
        } catch (\Exception $e) {
            return redirect('/')->withErrors('Invalid or expired verification link.');
        }

        $user = User::find($decryptedData['id']);

        if (!$user) {
            return redirect('/')->withErrors('User not found.');
        }

        if (!$user->email_verified_at) {
            $user->email_verified_at = Carbon::now();
            $user->save();
        }

        return view('users.verified', compact('user'));
    }

    public function showForgotForm(){
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $tokenData = [
            'id' => $user->id,
            'email' => $user->email,
            'timestamp' => now()->timestamp,
        ];

        $token = Crypt::encryptString(json_encode($tokenData));
        $resetLink = route('password.reset', ['token' => $token]);

        Mail::to($user->email)->send(new PasswordResetEmail($resetLink));

        return back()->with('success', 'We sent a password reset link to your email.');
    }

    public function showResetForm($token){
        try {
            $data = json_decode(Crypt::decryptString($token), true);
        } catch (\Exception $e) {
            return redirect()->route('password.request')->withErrors(['token' => 'Invalid or expired reset link.']);
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $data['email']]);
    }

    public function resetPassword(Request $request){
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        try {
            $data = json_decode(Crypt::decryptString($request->token), true);
        } catch (\Exception $e) {
            return back()->withErrors(['token' => 'Invalid or expired token.']);
        }

        $user = User::where('id', $data['id'])->where('email', $data['email'])->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully!');
    }

    public function createRoll(Request $request){
        if(!Auth::check()) return redirect('login');
        
        // Get roles from DB directly
        $roles = DB::table('roles')->get();
        $permissions = DB::table('permissions')->get();
        
        // If this is an admin route, use admin layout
        if ($request->is('admin/*')) {
            return view('admin.users.create', compact('roles', 'permissions'));
        }
        
        return view('users.create', compact('roles', 'permissions'));
    }

    public function edit(Request $request, ?User $user = null){
        if(!Auth::check()) return redirect('login');
        
        $user = $user ?? new User();
        $roles = DB::table('roles')->get();
        $permissions = DB::table('permissions')->get();

        // If this is an admin route, use admin layout
        if ($request->is('admin/*')) {
            return view('admin.users.edit', compact('user', 'roles', 'permissions'));
        }
        
        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function save(Request $request, $user){
        if(!Auth::check()) return redirect('login');
        
        // Handle case where user is 0 (new user)
        if ($user === '0' || $user === 0) {
            $user = new User();
            $validationRules = [
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'profile_photo' => ['nullable', 'image', 'max:2048'], // 2MB max
            ];
        } else {
            // Find existing user
            $user = User::find($user);
            if (!$user) {
                return redirect()->back()->withErrors('User not found.');
            }
            
            $validationRules = [
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
                'profile_photo' => ['nullable', 'image', 'max:2048'], // 2MB max
            ];

            // Add password validation if provided
            if ($request->filled('password')) {
                $validationRules['password'] = ['required', 'string', 'min:8', 'confirmed'];
            }
        }
        
        $validated = $request->validate($validationRules);
        
        // Update basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        // Update password if provided
        if (!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }
        
        // Additional fields if they exist in the request
        if ($request->has('phone')) {
            $user->phone = $request->input('phone');
        }
        
        if ($request->has('address')) {
            $user->address = $request->input('address');
        }
        
        // Handle profile photo upload
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            // Create directory if it doesn't exist
            $uploadPath = public_path('images/users');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Delete old photo if exists and it's not the default avatar
            if ($user->profile_photo && $user->profile_photo != 'default-avatar.png' && 
                file_exists(public_path('images/users/' . $user->profile_photo))) {
                unlink(public_path('images/users/' . $user->profile_photo));
            }
            
            // Generate a unique filename
            $fileName = time() . '_' . uniqid() . '.' . $request->file('profile_photo')->extension();
            
            // Move uploaded file
            $request->file('profile_photo')->move($uploadPath, $fileName);
            
            // Save filename to database
            $user->profile_photo = $fileName;
        }
        
        $user->save();
        
        // Handle role assignment if roles is provided
        if ($request->has('roles')) {
            $user->roles()->sync($request->input('roles'));
        }
        
        if ($request->is('admin/*')) {
            return redirect()->route('admin.users.list')->with('success', 'User saved successfully!');
        }
        
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function delete(Request $request, User $user){
        if(!Auth::check()) return redirect('login');
        
        // Verify password if user is deleting their own account
        if (Auth::id() == $user->id) {
            if (!$request->filled('password_confirm') || !Hash::check($request->password_confirm, $user->password)) {
                return redirect()->back()->withErrors('Password confirmation is required and must match your current password.');
            }
        } else {
            // Check if user has permission to delete other users
            $hasPermission = DB::table('model_has_permissions')
                ->join('permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
                ->where('model_id', Auth::id())
                ->where('model_type', User::class)
                ->where('permissions.name', 'delete_users')
                ->exists();
                
            if (!$hasPermission) {
                abort(403, 'Unauthorized action');
            }
        }
        
        // Delete profile photo if exists and it's not the default avatar
        if ($user->profile_photo && $user->profile_photo != 'default-avatar.png' && 
            file_exists(public_path('images/users/' . $user->profile_photo))) {
            unlink(public_path('images/users/' . $user->profile_photo));
        }
        
        $user->delete();
        
        if (Auth::id() == $user->id) {
            Auth::logout();
            return redirect('/')->with('success', 'Your account has been deleted.');
        }
        
        if ($request->is('admin/*')) {
            return redirect()->route('admin.users.list')->with('success', 'User deleted successfully!');
        }
        
        return redirect()->route('home')->with('success', 'User deleted successfully!');
    }

    public function editPassword(Request $request, ?User $user = null){
        $user = $user ?? Auth::user();
        if (Auth::id() != $user?->id) {
            // Check permission using direct DB query
            $hasPermission = DB::table('model_has_permissions')
                ->join('permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
                ->where('model_id', Auth::id())
                ->where('model_type', User::class)
                ->where('permissions.name', 'change_password')
                ->exists();
                
            if (!$hasPermission) {
                abort(401);
            }
        }

        return view('users.edit_password', compact('user'));
    }

    public function savePassword(Request $request, User $user){
        if (Auth::id() == $user?->id) {
            $this->validate($request, [
                'password' => ['required', 'confirmed', Password::min(8)->numbers()->letters()->mixedCase()->symbols()],
            ]);

            if (!Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
                Auth::logout();
                return redirect('/');
            }
        } else {
            // Check permission using direct DB query
            $hasPermission = DB::table('model_has_permissions')
                ->join('permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
                ->where('model_id', Auth::id())
                ->where('model_type', User::class)
                ->where('permissions.name', 'edit_users')
                ->exists();
                
            if (!$hasPermission) {
                abort(401);
            }
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return redirect(route('profile', ['user' => $user->id]));
    }

    public function profile(Request $request, ?User $user = null){
        $user = $user ?? Auth::user();
        if (Auth::id() != $user->id) {
            // Check permission using direct DB query
            $hasPermission = DB::table('model_has_permissions')
                ->join('permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
                ->where('model_id', Auth::id())
                ->where('model_type', User::class)
                ->where('permissions.name', 'view_users')
                ->exists();
                
            if (!$hasPermission) {
                abort(401);
            }
        }

        // Get user permissions and role permissions directly from DB
        $permissions = [];
        
        // Direct permissions
        $directPermissions = DB::table('model_has_permissions')
            ->join('permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
            ->where('model_id', $user->id)
            ->where('model_type', User::class)
            ->select('permissions.*')
            ->get();
            
        foreach ($directPermissions as $permission) {
            $permissions[] = $permission;
        }
        
        // Role permissions
        $rolePermissions = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->where('model_id', $user->id)
            ->where('model_type', User::class)
            ->select('permissions.*')
            ->distinct()
            ->get();
            
        foreach ($rolePermissions as $permission) {
            $permissions[] = $permission;
        }

        return view('users.profile', compact('user', 'permissions'));
    }
}


