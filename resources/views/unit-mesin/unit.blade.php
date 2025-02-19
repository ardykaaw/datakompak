@extends('layouts.app')

@section('title', 'Daftar Unit')

@section('content')
<div class="min-h-screen bg-gray-100"
     x-data="{ 
        isSidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
        isDarkMode: localStorage.getItem('darkMode') === 'true',
        init() {
            document.documentElement.classList.toggle('dark', this.isDarkMode)
            this.$watch('isSidebarOpen', value => localStorage.setItem('sidebarOpen', value))
            this.$watch('isDarkMode', value => {
                localStorage.setItem('darkMode', value)
                document.documentElement.classList.toggle('dark', value)
            })
        }
     }">
    
    @include('layouts.sidebar')

    <div class="transition-all duration-300 ease-in-out"
         :class="{
             'lg:pl-64': isSidebarOpen,
             'lg:pl-20': !isSidebarOpen
         }">
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <button @click="isSidebarOpen = !isSidebarOpen" 
                            class="text-gray-500 hover:text-gray-600 focus:outline-none mr-4">
                        <i class="fas fa-grip-lines text-xl"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-900">Daftar Unit</h1>
                </div>
            </div>
        </header>

        <main class="p-4">
            <div class="max-w-7xl mx-auto">
                <!-- Stats Card -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-600 rounded-lg">
                                <i class="fas fa-industry text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Total Unit</h3>
                                <p class="text-3xl font-bold">{{ $units->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-600 rounded-lg">
                                <i class="fas fa-cogs text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Total Mesin</h3>
                                <p class="text-3xl font-bold">
                                    @php
                                        $totalMachines = 0;
                                        foreach($units as $unit) {
                                            $totalMachines += $unit->machines->count();
                                        }
                                    @endphp
                                    {{ $totalMachines }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-600 rounded-lg">
                                <i class="fas fa-chart-line text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Rata-rata Mesin</h3>
                                <p class="text-3xl font-bold">
                                    {{ $units->count() > 0 ? number_format($totalMachines / $units->count(), 1) : '0' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="bg-white rounded-xl shadow-sm">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Daftar Unit</h2>
                                <p class="text-sm text-gray-500">Kelola data unit pembangkit</p>
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('unit-mesin.index') }}" 
                                   class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center">
                                    <i class="fas fa-eye mr-2"></i>
                                    Lihat Status Unit dan Mesin
                                </a>
                                <a href="{{ route('unit-mesin.create') }}" 
                                   class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Unit
                                </a>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Unit</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Mesin</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($units as $unit)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('unit-mesin.show', $unit) }}" class="hover:text-blue-500">
                                                        {{ $unit->name }}
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ $unit->description ?: '-' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $unit->machines->count() }} Mesin</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Aktif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-end space-x-2">
                                                    <a href="{{ route('unit-mesin.edit', $unit) }}" 
                                                       class="text-blue-600 hover:text-blue-900">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button onclick="confirmDelete({{ $unit->id }}, '{{ $unit->name }}')" 
                                                            class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <form id="delete-form-{{ $unit->id }}" 
                                                          action="{{ route('unit-mesin.destroy', $unit) }}" 
                                                          method="POST" 
                                                          class="hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center">
                                                <div class="text-gray-500">
                                                    <i class="fas fa-inbox text-4xl mb-4"></i>
                                                    <p class="text-lg">Belum ada unit yang ditambahkan</p>
                                                    <p class="text-sm">Klik tombol "Tambah Unit" untuk menambahkan unit baru</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function confirmDelete(unitId, unitName) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        html: `Apakah Anda yakin ingin menghapus unit <strong>${unitName}</strong>?<br>Tindakan ini tidak dapat dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${unitId}`).submit();
        }
    });
}
</script>
@endsection