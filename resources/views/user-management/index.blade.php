@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="min-h-screen bg-gray-100"
     x-data="{ 
        isSidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
        isDarkMode: localStorage.getItem('darkMode') === 'true',
        showCreateModal: false,
        init() {
            this.$watch('isSidebarOpen', value => localStorage.setItem('sidebarOpen', value))
            this.$watch('isDarkMode', value => {
                localStorage.setItem('darkMode', value)
                document.documentElement.classList.toggle('dark', value)
            })
        }
     }">
    
    @include('layouts.sidebar')

    <!-- Main Content -->
    <div class="transition-all duration-300 ease-in-out"
         :class="{
             'lg:pl-64': isSidebarOpen,
             'lg:pl-20': !isSidebarOpen
         }">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <button @click="isSidebarOpen = !isSidebarOpen" 
                            class="text-gray-500 hover:text-gray-600 focus:outline-none mr-4">
                        <i class="fas fa-grip-lines text-xl"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-900">Manajemen Pengguna</h1>
                </div>
                @can('manage-users')
                <a href="{{ route('user-management.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Pengguna
                </a>
                @endcan
            </div>
        </header>

        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Stats -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-4 mb-6">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-users text-2xl text-blue-500"></i>
                                </div>
                                <div class="ml-5">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Pengguna</dt>
                                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalUsers }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-user-shield text-2xl text-purple-500"></i>
                                </div>
                                <div class="ml-5">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Super Admin</dt>
                                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $users->where('role', 'super_admin')->count() }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-user-tie text-2xl text-indigo-500"></i>
                                </div>
                                <div class="ml-5">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Admin</dt>
                                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $users->where('role', 'admin')->count() }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-user text-2xl text-yellow-500"></i>
                                </div>
                                <div class="ml-5">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Regular Users</dt>
                                        <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $users->where('role', 'user')->count() }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                            <div class="flex items-center">
                                <i class="fas fa-users text-blue-500 text-xl mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">Daftar Pengguna</h3>
                            </div>
                            <div class="relative w-full md:w-64">
                                <input type="text" 
                                       placeholder="Cari pengguna..." 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm">
                                <div class="absolute left-3 top-2.5">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-200">
                            <thead>
                                <tr class="bg-gradient-to-r from-gray-50 to-gray-100">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-r border-gray-200">Nama</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-r border-gray-200">Email</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-r border-gray-200">Unit</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-r border-gray-200">Role</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-r border-gray-200">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b border-gray-200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                <tr class="hover:bg-blue-50/30 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-100 to-blue-200 flex items-center justify-center shadow-sm">
                                                    <i class="fas fa-user text-blue-500"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                        <div class="text-sm text-gray-600 font-medium">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                        <span class="px-3 py-1.5 inline-flex items-center text-xs font-semibold rounded-full shadow-sm
                                            bg-gray-100 text-gray-800 ring-1 ring-gray-300">
                                            <i class="fas fa-building mr-1.5"></i>
                                            {{ $user->unit ? $user->unit->name : 'Belum Ada Unit' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                        <span class="px-3 py-1.5 inline-flex items-center text-xs font-semibold rounded-full shadow-sm
                                            {{ $user->role === 'super_admin' ? 'bg-purple-100 text-purple-800 ring-1 ring-purple-300' : 
                                               ($user->role === 'admin' ? 'bg-blue-100 text-blue-800 ring-1 ring-blue-300' : 
                                               'bg-green-100 text-green-800 ring-1 ring-green-300') }}">
                                            <i class="fas {{ $user->role === 'super_admin' ? 'fa-user-shield' : 
                                                   ($user->role === 'admin' ? 'fa-user-tie' : 'fa-user') }} mr-1.5"></i>
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                        <span class="px-3 py-1.5 inline-flex items-center text-xs font-semibold rounded-full shadow-sm
                                            {{ $user->is_active ? 'bg-emerald-100 text-emerald-800 ring-1 ring-emerald-300' : 'bg-red-100 text-red-800 ring-1 ring-red-300' }}">
                                            <i class="fas {{ $user->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-1.5"></i>
                                            {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @can('manage-users')
                                        <div class="flex space-x-3">
                                            <a href="{{ route('user-management.edit', $user->id) }}" 
                                               class="p-1.5 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                            <button onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')" 
                                                    class="p-1.5 text-red-600 hover:text-red-900 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $user->id }}" 
                                                  action="{{ route('user-management.destroy', $user->id) }}" 
                                                  method="POST" 
                                                  class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            @endif
                                        </div>
                                        @endcan
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State yang Lebih Menarik -->
                    @if($users->isEmpty())
                    <div class="text-center py-12 bg-gray-50">
                        <div class="rounded-full bg-blue-100 w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-blue-500 text-2xl"></i>
                        </div>
                        <h3 class="text-gray-900 font-medium text-lg mb-2">Belum ada pengguna</h3>
                        <p class="text-gray-500">Mulai tambahkan pengguna baru untuk mengelola sistem</p>
                    </div>
                    @endif

                    <!-- Pagination dengan Style yang Lebih Baik -->
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function confirmDelete(userId, userName) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        html: `Apakah Anda yakin ingin menghapus pengguna <strong>${userName}</strong>?<br>Tindakan ini tidak dapat dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${userId}`).submit();
        }
    });
}
</script>
@endsection 