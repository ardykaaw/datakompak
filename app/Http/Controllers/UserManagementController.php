<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Unit;
use Illuminate\Support\Facades\Log;

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
        
        $units = Unit::all();
        return view('user-management.create', compact('units'));
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
            'unit' => ['required', 'exists:units,id'],
        ], [
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.mixed' => 'Password harus mengandung huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
            'email.unique' => 'Email sudah digunakan.',
            'role.in' => 'Role yang dipilih tidak valid.',
            'unit.required' => 'Unit harus dipilih.',
            'unit.exists' => 'Unit yang dipilih tidak valid.',
        ]);

        try {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'is_active' => $validated['is_active'],
                'unit_id' => $validated['unit'],
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

        $units = Unit::all();
        return view('user-management.edit', compact('user', 'units'));
    }

    public function update(Request $request, User $user)
    {
        // Check if user has permission to update this user
        if (!$this->canManageUser($user)) {
            Log::error('Permission denied to update user', [
                'auth_user' => Auth::user()->toArray(),
                'target_user' => $user->toArray()
            ]);
            return redirect()->route('user-management.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengubah pengguna ini.');
        }

        Log::info('Update User Request:', [
            'request_all' => $request->all(),
            'user_id' => $user->id,
            'current_user_data' => $user->toArray(),
            'allowed_roles' => $this->getAllowedRoles($user)
        ]);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in($this->getAllowedRoles($user))],
            'is_active' => ['required', 'boolean'],
            'unit' => ['required', 'exists:units,id'],
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = [Password::min(8)->mixedCase()->numbers()];
        }

        try {
            $validated = $request->validate($rules, [
                'password.min' => 'Password minimal harus 8 karakter.',
                'password.mixed' => 'Password harus mengandung huruf besar dan kecil.',
                'password.numbers' => 'Password harus mengandung angka.',
                'email.unique' => 'Email sudah digunakan.',
                'role.in' => 'Role yang dipilih tidak valid.',
                'unit.required' => 'Unit harus dipilih.',
                'unit.exists' => 'Unit yang dipilih tidak valid.',
            ]);

            Log::info('Validated Data:', $validated);
            
            // Update user data
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'is_active' => $validated['is_active'],
                'unit_id' => $validated['unit'],
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            Log::info('Update Data:', $updateData);
            
            $user->update($updateData);

            Log::info('User Updated Successfully:', [
                'user_id' => $user->id,
                'updated_data' => $user->fresh()->toArray()
            ]);

            return redirect()->route('user-management.index')
                ->with('success', 'Pengguna berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
                'allowed_roles' => $this->getAllowedRoles($user)
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Update Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'allowed_roles' => $this->getAllowedRoles($user)
            ]);
            
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

        // Super admin can set users to admin or user role
        if ($authUser->role === 'super_admin') {
            return ['user', 'admin'];
        }

        // Admin can only set users to user role
        if ($authUser->role === 'admin') {
            return ['user'];
        }

        // Return empty array if no roles are allowed
        return [];
    }
} 