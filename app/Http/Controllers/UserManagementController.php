<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        $query = User::query();
        
        // If user is not super_admin, they can only see users with lower or equal roles
        if (Auth::user()->role !== 'super_admin') {
            $query->where(function($q) {
                $q->where('role', '!=', 'super_admin')
                  ->when(Auth::user()->role === 'user', function($q) {
                      $q->where('role', 'user');
                  });
            });
        }
        
        $users = $query->latest()->paginate(10);
        
        // Get counts for dashboard
        $totalUsers = $query->count();
        $activeUsers = $query->where('is_active', true)->count();
        $inactiveUsers = $totalUsers - $activeUsers;
        
        return view('user-management.index', compact('users', 'totalUsers', 'activeUsers', 'inactiveUsers'));
    }

    public function create()
    {
        // Only admin and super_admin can create users
        if (!in_array(Auth::user()->role, ['admin', 'super_admin'])) {
            return redirect()->route('user-management.index')
                ->with('error', 'Anda tidak memiliki izin untuk membuat pengguna baru.');
        }
        
        return view('user-management.create');
    }

    public function store(Request $request)
    {
        // Only admin and super_admin can create users
        if (!in_array(Auth::user()->role, ['admin', 'super_admin'])) {
            return redirect()->route('user-management.index')
                ->with('error', 'Anda tidak memiliki izin untuk membuat pengguna baru.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::min(8)->mixedCase()->numbers()],
            'role' => ['required', Rule::in(Auth::user()->role === 'super_admin' ? ['user', 'admin'] : ['user'])],
            'is_active' => ['required', 'boolean'],
        ], [
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.mixed' => 'Password harus mengandung huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
            'email.unique' => 'Email sudah digunakan.',
            'role.in' => 'Role yang dipilih tidak valid.',
        ]);

        try {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_active' => $validated['is_active'],
            ]);

            return redirect()->route('user-management.index')
                ->with('success', 'Pengguna berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat pengguna. Silakan coba lagi.');
        }
    }

    public function edit(User $user)
    {
        // Check if user has permission to edit this user
        if (!$this->canManageUser($user)) {
            return redirect()->route('user-management.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengedit pengguna ini.');
        }

        return view('user-management.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Check if user has permission to update this user
        if (!$this->canManageUser($user)) {
            return redirect()->route('user-management.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengubah pengguna ini.');
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in($this->getAllowedRoles($user))],
            'is_active' => ['required', 'boolean'],
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = [Password::min(8)->mixedCase()->numbers()];
        }

        $validated = $request->validate($rules, [
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.mixed' => 'Password harus mengandung huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
            'email.unique' => 'Email sudah digunakan.',
            'role.in' => 'Role yang dipilih tidak valid.',
        ]);

        try {
            // Update user data
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'is_active' => $validated['is_active'],
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            return redirect()->route('user-management.index')
                ->with('success', 'Pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui pengguna. Silakan coba lagi.');
        }
    }

    public function destroy(User $user)
    {
        // Check if user has permission to delete this user
        if (!$this->canManageUser($user)) {
            return redirect()->route('user-management.index')
                ->with('error', 'Anda tidak memiliki izin untuk menghapus pengguna ini.');
        }

        // Prevent deleting the last super admin
        if ($user->role === 'super_admin' && User::where('role', 'super_admin')->count() === 1) {
            return redirect()->route('user-management.index')
                ->with('error', 'Tidak dapat menghapus super admin terakhir.');
        }

        // Prevent deleting yourself
        if ($user->id === Auth::id()) {
            return redirect()->route('user-management.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        try {
            $user->delete();
            return redirect()->route('user-management.index')
                ->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('user-management.index')
                ->with('error', 'Terjadi kesalahan saat menghapus pengguna. Silakan coba lagi.');
        }
    }

    /**
     * Check if the authenticated user can manage the target user
     */
    private function canManageUser(User $targetUser): bool
    {
        $authUser = Auth::user();

        // Super admin can manage all users
        if ($authUser->role === 'super_admin') {
            return true;
        }

        // Admin can manage regular users but not other admins or super admins
        if ($authUser->role === 'admin') {
            return $targetUser->role === 'user';
        }

        // Regular users cannot manage any users
        return false;
    }

    /**
     * Get allowed roles for user management based on authenticated user's role
     */
    private function getAllowedRoles(User $targetUser): array
    {
        $authUser = Auth::user();

        if ($authUser->role === 'super_admin') {
            return ['user', 'admin', 'super_admin'];
        }

        if ($authUser->role === 'admin') {
            return ['user'];
        }

        return [$targetUser->role]; // Can't change roles
    }
} 