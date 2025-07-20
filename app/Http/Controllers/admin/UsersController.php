<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    /**
     * Display a listing of users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }
    
    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = new User();
        $roles = Role::all();
        $permissions = Permission::all();
        
        return view('admin.users.create', compact('user', 'roles', 'permissions'));
    }
    
    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        
        return view('admin.users.edit', compact('user', 'roles', 'permissions'));
    }
    
    /**
     * Store a newly created or update an existing user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User|null  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $userId = null)
    {
        // Handle case where user is 0 (new user)
        if ($userId === '0' || $userId === 0 || $userId === null) {
            $user = new User();
            $validationRules = [
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'profile_photo' => ['nullable', 'image', 'max:2048'], // 2MB max
            ];
        } else {
            // Find existing user
            $user = User::find($userId);
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
            $user->password = Hash::make($request->password);
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
        
        return redirect()->route('admin.users.index')->with('success', 'User saved successfully!');
    }
    
    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        try {
            // Delete profile photo if exists and it's not the default avatar
            if ($user->profile_photo && $user->profile_photo != 'default-avatar.png' && 
                file_exists(public_path('images/users/' . $user->profile_photo))) {
                unlink(public_path('images/users/' . $user->profile_photo));
            }
            
            $user->delete();
            
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return redirect()->route('admin.users.index')->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
    
    /**
     * Assign a role to a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignRole(Request $request)
    {
        $validator = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);
        
        $user = User::find($request->user_id);
        $user->roles()->sync([$request->role_id]);
        
        return redirect()->back()
            ->with('success', 'Role assigned successfully!');
    }
} 