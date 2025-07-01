<?php

namespace App\Http\Controllers\web;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
//use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use App\Mail\PasswordResetEmail;

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

    public function list(Request $request)
    {
        if (!Auth::check()) {
            abort(401);
        }
        
        // Check permission using direct DB query instead of hasPermissionTo
        $hasPermission = DB::table('model_has_permissions')
            ->join('permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
            ->where('model_id', Auth::id())
            ->where('model_type', User::class)
            ->where('permissions.name', 'view_users')
            ->exists();
            
        if (!$hasPermission) {
            abort(401);
        }

        $query = User::select('*');

        // Check if user has Manager role
        $isManager = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_id', Auth::id())
            ->where('model_type', User::class)
            ->where('roles.name', 'Manager')
            ->exists();
            
        if ($isManager) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'Admin');
            });
        }

        $query->when($request->keywords, function($q) use ($request) {
            $q->where(function($query) use ($request) {
                $query->where('name', 'like', "%{$request->keywords}%")
                      ->orWhere('email', 'like', "%{$request->keywords}%");
            });
        });

        $query->when($request->role, function($q) use ($request) {
            $q->whereHas('roles', function($query) use ($request) {
                $query->where('name', $request->role);
            });
        });

        $users = $query->paginate(10)->withQueryString();
        
        // Get roles from DB directly
        $roles = DB::table('roles')->get();

        return view('users.list', compact('users', 'roles'));
    }

    public function createRoll(){
        // Get roles from DB directly
        $roles = DB::table('roles')->get();
        return view('users.create', compact('roles'));
    }

    public function edit(Request $request, ?User $user = null){
        $user = $user ?? Auth::user();
        if(Auth::id() != $user?->id) {
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

        $roles = [];
        $allRoles = DB::table('roles')->get();
        foreach ($allRoles as $role) {
            // Check if user has role directly
            $hasRole = DB::table('model_has_roles')
                ->where('model_id', $user->id)
                ->where('model_type', User::class)
                ->where('role_id', $role->id)
                ->exists();
            
            $role->taken = $hasRole;
            $roles[] = $role;
        }

        $permissions = [];
        $directPermissionsIds = DB::table('model_has_permissions')
            ->where('model_id', $user->id)
            ->where('model_type', User::class)
            ->pluck('permission_id')
            ->toArray();
            
        $allPermissions = DB::table('permissions')->get();
        foreach ($allPermissions as $permission) {
            $permission->taken = in_array($permission->id, $directPermissionsIds);
            $permissions[] = $permission;
        }

        return view('users.edit', compact('user', 'roles', 'permissions'));
    }

    public function save(Request $request, User $user){
        if(Auth::id() != $user->id) {
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

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'address' => ['nullable', 'string', 'max:1000'],
            'roles' => ['array'],
            'permissions' => ['array'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
        ]);
        
        // Check if user has Admin or Manager role
        $hasAdminOrManagerRole = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_id', Auth::id())
            ->where('model_type', User::class)
            ->whereIn('roles.name', ['Admin', 'Manager'])
            ->exists();
            
        if ($hasAdminOrManagerRole) {
            // Sync roles manually
            if ($request->has('roles')) {
                // Clear existing roles
                DB::table('model_has_roles')
                    ->where('model_id', $user->id)
                    ->where('model_type', User::class)
                    ->delete();
                
                // Add new roles
                foreach ($request->roles as $roleId) {
                    DB::table('model_has_roles')->insert([
                        'role_id' => $roleId,
                        'model_type' => User::class,
                        'model_id' => $user->id
                    ]);
                }
            }
            
            // Sync permissions manually
            if ($request->has('permissions')) {
                // Clear existing permissions
                DB::table('model_has_permissions')
                    ->where('model_id', $user->id)
                    ->where('model_type', User::class)
                    ->delete();
                
                // Add new permissions
                foreach ($request->permissions as $permissionId) {
                    DB::table('model_has_permissions')->insert([
                        'permission_id' => $permissionId,
                        'model_type' => User::class,
                        'model_id' => $user->id
                    ]);
                }
            }
        }

        return redirect()->route('profile', ['user' => $user->id])->with('success', 'Profile updated successfully.');
    }

    public function delete(Request $request, User $user){
        // Check permission using direct DB query
        $hasPermission = DB::table('model_has_permissions')
            ->join('permissions', 'permissions.id', '=', 'model_has_permissions.permission_id')
            ->where('model_id', Auth::id())
            ->where('model_type', User::class)
            ->where('permissions.name', 'delete_users')
            ->exists();
            
        if (!$hasPermission) {
            abort(401);
        }
        
        $user->delete();

        return redirect()->route('users.list')->with('success', 'User deleted successfully.');
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


