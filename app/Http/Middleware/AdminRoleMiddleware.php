<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Check if user has admin role through model_has_roles table
            $hasAdminRole = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', Auth::id())
                ->where('model_has_roles.model_type', 'App\\Models\\User')
                ->where('roles.name', 'Admin')
                ->exists();
                
            if ($hasAdminRole) {
                return $next($request);
            }
        }
        
        return redirect()->route('home')->with('error', 'You do not have permission to access this page.');
    }
}
