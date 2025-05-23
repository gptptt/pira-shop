<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class UserRoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Show the form for editing user roles.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('admin.users.roles', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the user's roles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        try {
            DB::beginTransaction();
            
            $roles = $request->input('roles', []);
            
            // If admin user, ensure they always have admin role
            if ($user->is_admin && !in_array(Role::where('name', 'admin')->first()->id, $roles)) {
                $roles[] = Role::where('name', 'admin')->first()->id;
            }
            
            $user->syncRoles($roles);
            
            DB::commit();
            
            return redirect()->route('admin.users.show', $user)
                ->with('success', 'User roles updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update user roles: ' . $e->getMessage());
        }
    }
    
    /**
     * Quick assign a role to a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        try {
            $role = Role::findById($request->role_id);
            $user->assignRole($role);
            
            return redirect()->back()
                ->with('success', "Role '{$role->name}' assigned to user successfully.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to assign role: ' . $e->getMessage());
        }
    }
    
    /**
     * Quick remove a role from a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        try {
            $role = Role::findById($request->role_id);
            
            // Prevent removing admin role from admin users
            if ($user->is_admin && $role->name === 'admin') {
                return back()->with('error', 'Cannot remove admin role from system administrators.');
            }
            
            $user->removeRole($role);
            
            return redirect()->back()
                ->with('success', "Role '{$role->name}' removed from user successfully.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove role: ' . $e->getMessage());
        }
    }
} 