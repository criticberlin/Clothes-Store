<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Check if user has admin role
        $hasAdminAccess = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', Auth::id())
            ->where('model_has_roles.model_type', 'App\\Models\\User')
            ->where('roles.name', 'Admin')
            ->exists();
            
        if (!$hasAdminAccess) {
            return redirect()->route('home')->with('error', 'You do not have permission to access the admin dashboard.');
        }
        
        // Get counts for dashboard widgets
        $userCount = User::count();
        
        // Get available roles and permissions for management
        $roles = Role::all();
        $permissions = Permission::all();
        
        return view('admin.dashboard', compact('userCount', 'roles', 'permissions'));
    }
    
    /**
     * Assign a role to a user.
     */
    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id'
        ]);
        
        $user = User::findOrFail($request->user_id);
        $role = Role::findOrFail($request->role_id);
        
        // Check if the role-user assignment already exists
        $exists = DB::table('model_has_roles')
            ->where('role_id', $role->id)
            ->where('model_id', $user->id)
            ->where('model_type', 'App\\Models\\User')
            ->exists();
            
        if (!$exists) {
            // Insert directly into model_has_roles table
            DB::table('model_has_roles')->insert([
                'role_id' => $role->id,
                'model_type' => 'App\\Models\\User',
                'model_id' => $user->id
            ]);
        }
        
        return redirect()->back()->with('success', 'Role assigned successfully.');
    }
} 