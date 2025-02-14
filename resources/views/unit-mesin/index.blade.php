@extends('layouts.app')

@section('title', 'Unit dan Mesin')

@section('content')
<div class="min-h-screen bg-gray-100"
     x-data="{ 
        isSidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
        isDarkMode: localStorage.getItem('darkMode') === 'true',
        init() {
            // Apply dark mode immediately on component initialization
            document.documentElement.classList.toggle('dark', this.isDarkMode)
            
            // Watch for changes
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
                    <h1 class="text-xl font-semibold text-gray-900">Unit dan Mesin</h1>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-4">
            <div class="max-w-7xl mx-auto">
                <!-- Overview Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Mesin Card -->
                    <a href="{{ route('unit-mesin.mesin') }}" class="block">
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6 text-white">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-xl font-semibold">Total Mesin</h2>
                                    <p class="text-sm opacity-75">Kelola data mesin</p>
                                </div>
                                <div class="text-3xl">
                                    <i class="fas fa-cogs"></i>
                                </div>
                            </div>
                            <div class="text-2xl font-bold mb-2">
                                @php
                                    $totalMachines = 0;
                                    foreach($units as $unit) {
                                        $totalMachines += $unit->machines->count();
                                    }
                                @endphp
                                {{ $totalMachines }}
                            </div>
                            <div class="text-sm opacity-75">Lihat semua mesin →</div>
                        </div>
                    </a>

                    <!-- Unit Card -->
                    <a href="{{ route('unit-mesin.unit') }}" class="block">
                        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-6 text-white">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2 class="text-xl font-semibold">Unit</h2>
                                    <p class="text-sm opacity-75">Kelola data unit</p>
                                </div>
                                <div class="text-3xl">
                                    <i class="fas fa-industry"></i>
                                </div>
                            </div>
                            <div class="text-2xl font-bold mb-2">
                                {{ $units->count() }}
                            </div>
                            <div class="text-sm opacity-75">Lihat semua unit →</div>
                        </div>
                    </a>

                    <!-- Overview Card -->
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-sm p-6 text-white">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-xl font-semibold">Ringkasan</h2>
                                <p class="text-sm opacity-75">Statistik umum</p>
                            </div>
                            <div class="text-3xl">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-75">Rata-rata Mesin per Unit:</span>
                                <span class="font-semibold">
                                    {{ $units->count() > 0 ? number_format($totalMachines / $units->count(), 1) : '0' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-75">Unit Aktif:</span>
                                <span class="font-semibold">{{ $units->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm opacity-75">Total Mesin:</span>
                                <span class="font-semibold">{{ $totalMachines }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-sm">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Aktivitas Terbaru</h2>
                                <p class="text-sm text-gray-500">Daftar unit dan mesin terbaru</p>
                            </div>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            @foreach($units->take(5) as $unit)
                                <div class="py-3">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="text-base font-medium text-gray-800">
                                                <a href="{{ route('unit-mesin.show', $unit) }}" class="hover:text-blue-500">
                                                    {{ $unit->name }}
                                                </a>
                                            </h3>
                                            <p class="text-sm text-gray-500">
                                                Jumlah Mesin: {{ $unit->machines->count() }}
                                            </p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('unit-mesin.edit', $unit) }}" 
                                               class="text-gray-400 hover:text-blue-500">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if($units->isEmpty())
                                <div class="py-3 text-center text-gray-500">
                                    Belum ada data unit dan mesin.
                                </div>
                            @endif
                        </div>

                        @if($units->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('unit-mesin.unit') }}" 
                                   class="text-blue-500 hover:text-blue-600 text-sm">
                                    Lihat semua unit →
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection