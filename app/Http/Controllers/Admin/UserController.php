<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua user di platform.
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('email', 'LIKE', '%' . $request->search . '%');
        }

        $users = $query->latest()->paginate(20);
        
        // Load all platform roles to show in dropdowns
        // Platform roles usually exclude 'organizer_owner' etc which are context-specific, 
        // but for simplicity we can just list 'super_admin' and 'customer'.
        $roles = Role::whereIn('slug', ['super_admin', 'customer'])->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Update role global pengguna.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $role = Role::findOrFail($request->role_id);
        
        // Prevent removing the last super admin
        if ($role->slug !== 'super_admin' && $user->hasRole('super_admin')) {
            $superAdminCount = User::whereHas('roles', function($q) {
                $q->where('slug', 'super_admin');
            })->count();
            
            if ($superAdminCount <= 1) {
                return back()->with('error', 'Tidak dapat mengubah role admin utama terakhir.');
            }
        }

        // For this platform, a user only has one primary global role 
        // (super_admin OR customer). Organizer roles are pivot based.
        // First detach super_admin and customer, then attach new.
        $globalRoleIds = Role::whereIn('slug', ['super_admin', 'customer'])->pluck('id')->toArray();
        $user->roles()->detach($globalRoleIds);
        $user->roles()->attach($role->id);

        return back()->with('success', "Role {$user->name} berhasil diperbarui menjadi {$role->name}.");
    }
}
