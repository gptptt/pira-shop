<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleOrPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|array  ...$rolesOrPermissions
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$rolesOrPermissions): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to access this page');
        }

        $user = Auth::user();
        
        // If no roles or permissions are required, proceed
        if (empty($rolesOrPermissions)) {
            return $next($request);
        }
        
        // Check if user has any of the specified roles or permissions
        if ($user->hasAnyRole($rolesOrPermissions) || $user->hasAnyPermission($rolesOrPermissions)) {
            return $next($request);
        }
        
        // User doesn't have any of the required roles or permissions
        return redirect()->back()->with('error', 'You do not have permission to access this resource');
    }
} 