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

                        <!-- Table Container -->
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                            <!-- Table Search and Actions -->
                            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                                <div class="flex items-center space-x-2">
                                    <div class="relative">
                                        <input type="text" 
                                               id="tableSearch" 
                                               class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               placeholder="Cari unit...">
                                        <div class="absolute left-3 top-2.5 text-gray-400">
                                            <i class="fas fa-search"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="bg-[#0A749B] dark:bg-gray-800">
                                            <th class="px-6 py-4 text-left text-xs font-medium tracking-wider text-white uppercase w-20">No</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium tracking-wider text-white uppercase">
                                                <div class="flex items-center space-x-1 cursor-pointer">
                                                    <span>Nama Unit</span>
                                                    <i class="fas fa-sort"></i>
                                                </div>
                                            </th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Deskripsi</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Jumlah Mesin</th>
                                            <th class="px-6 py-4 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-4 text-right text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($units as $index => $unit)
                                            <tr class="hover:bg-gray-50 transition-colors duration-200 bg-white">
                                                <td class="px-6 py-4 whitespace-nowrap bg-white text-sm text-gray-500">
                                                    {{ ($units->currentPage() - 1) * $units->perPage() + $index + 1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap bg-white">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                            <i class="fas fa-industry text-blue-500"></i>
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                <a href="{{ route('unit-mesin.show', $unit) }}" class="hover:text-blue-500">
                                                                    {{ $unit->name }}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 bg-white">
                                                    <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $unit->description }}">
                                                        {{ $unit->description ?: '-' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap bg-white">
                                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-medium bg-blue-50 text-blue-700 rounded-full">
                                                        {{ $unit->machines->count() }} Mesin
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap bg-white">
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        <i class="fas fa-circle text-xs mr-1 mt-1"></i> Aktif
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium bg-white">
                                                    <div class="flex justify-end space-x-3">
                                                        <a href="{{ route('unit-mesin.edit', $unit) }}" 
                                                           class="text-blue-600 hover:text-blue-900 bg-blue-50 p-2 rounded-lg transition-colors duration-200">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button onclick="confirmDelete({{ $unit->id }}, '{{ $unit->name }}')" 
                                                                class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-lg transition-colors duration-200">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-8 text-center">
                                                    <div class="max-w-sm mx-auto">
                                                        <div class="bg-gray-50 rounded-lg p-6">
                                                            <div class="text-center">
                                                                <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                                                                <p class="text-lg font-medium text-gray-900 mb-1">Belum ada unit</p>
                                                                <p class="text-sm text-gray-500 mb-4">Mulai dengan menambahkan unit baru ke sistem</p>
                                                                <a href="{{ route('unit-mesin.create') }}" 
                                                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                                    <i class="fas fa-plus mr-2"></i>
                                                                    Tambah Unit
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-700">
                                        Menampilkan
                                        <span class="font-medium">{{ $units->firstItem() ?? 0 }}</span>
                                        sampai
                                        <span class="font-medium">{{ $units->lastItem() ?? 0 }}</span>
                                        dari
                                        <span class="font-medium">{{ $units->total() }}</span>
                                        data
                                    </div>
                                    <div>
                                        @if ($units->hasPages())
                                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                                {{-- Previous Page Link --}}
                                                @if ($units->onFirstPage())
                                                    <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-l-md">
                                                        <i class="fas fa-chevron-left text-xs mr-1"></i>
                                                        Sebelumnya
                                                    </span>
                                                @else
                                                    <a href="{{ $units->previousPageUrl() }}" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-blue-50 hover:text-blue-700">
                                                        <i class="fas fa-chevron-left text-xs mr-1"></i>
                                                        Sebelumnya
                                                    </a>
                                                @endif

                                                {{-- Pagination Elements --}}
                                                @foreach ($units->getUrlRange(max($units->currentPage() - 2, 1), min($units->currentPage() + 2, $units->lastPage())) as $page => $url)
                                                    @if ($page == $units->currentPage())
                                                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600">
                                                            {{ $page }}
                                                        </span>
                                                    @else
                                                        <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-blue-50 hover:text-blue-700">
                                                            {{ $page }}
                                                        </a>
                                                    @endif
                                                @endforeach

                                                {{-- Next Page Link --}}
                                                @if ($units->hasMorePages())
                                                    <a href="{{ $units->nextPageUrl() }}" class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-blue-50 hover:text-blue-700">
                                                        Selanjutnya
                                                        <i class="fas fa-chevron-right text-xs ml-1"></i>
                                                    </a>
                                                @else
                                                    <span class="relative inline-flex items-center px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 cursor-default rounded-r-md">
                                                        Selanjutnya
                                                        <i class="fas fa-chevron-right text-xs ml-1"></i>
                                                    </span>
                                                @endif
                                            </nav>
                                        @endif
                                    </div>
                                </div>
                            </div>
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

// Search functionality
let searchTimer;
const searchInput = document.getElementById('tableSearch');
const tbody = document.querySelector('tbody');

searchInput.addEventListener('input', function(e) {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        const searchValue = e.target.value;
        
        fetch(`{{ route('unit-mesin.unit') }}?search=${searchValue}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            tbody.innerHTML = html;
        });
    }, 300);
});
</script>
@endsection