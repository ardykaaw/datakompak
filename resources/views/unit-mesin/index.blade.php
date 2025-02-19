@extends('layouts.app')

@section('title', 'Unit dan Mesin')

@section('content')
@php
    $totalMachines = 0;
    foreach($units as $unit) {
        $totalMachines += $unit->machines->count();
    }
@endphp

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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- Total Mesin Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 dark:bg-gradient-to-br dark:from-gray-800 dark:to-blue-900/50 border border-blue-200 dark:border-blue-500/30 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
                        <div class="px-4 py-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Total Mesin</p>
                                    <h3 class="text-2xl font-bold text-blue-800 dark:text-blue-200 mt-1">{{ $totalMachines }}</h3>
                                </div>
                                <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-blue-500 bg-opacity-15 dark:bg-blue-400/20">
                                    <i class="fas fa-cogs text-xl text-blue-700 dark:text-blue-300"></i>
                                </div>
                            </div>
                            <div class="mt-3 border-t border-blue-200 dark:border-blue-500/30 pt-3">
                                <a href="{{ route('unit-mesin.mesin') }}" 
                                   class="text-sm text-blue-700 dark:text-blue-300 hover:text-blue-800 dark:hover:text-blue-100 inline-flex items-center transition-colors duration-200">
                                    <span>Kelola data mesin</span>
                                    <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Card -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100/50 dark:bg-gradient-to-br dark:from-gray-800 dark:to-purple-900/50 border border-purple-200 dark:border-purple-500/30 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
                        <div class="px-4 py-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-purple-700 dark:text-purple-300">Total Unit</p>
                                    <h3 class="text-2xl font-bold text-purple-800 dark:text-purple-200 mt-1">{{ $units->count() }}</h3>
                                </div>
                                <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-purple-500 bg-opacity-15 dark:bg-purple-400/20">
                                    <i class="fas fa-industry text-xl text-purple-700 dark:text-purple-300"></i>
                                </div>
                            </div>
                            <div class="mt-3 border-t border-purple-200 dark:border-purple-500/30 pt-3">
                                <a href="{{ route('unit-mesin.unit') }}" 
                                   class="text-sm text-purple-700 dark:text-purple-300 hover:text-purple-800 dark:hover:text-purple-100 inline-flex items-center transition-colors duration-200">
                                    <span>Kelola data unit</span>
                                    <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Card -->
                    <div class="bg-gradient-to-br from-teal-50 to-teal-100/50 dark:bg-gradient-to-br dark:from-gray-800 dark:to-teal-900/50 border border-teal-200 dark:border-teal-500/30 rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
                        <div class="px-4 py-3">
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-sm font-medium text-teal-700 dark:text-teal-300">Statistik</p>
                                <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-teal-500 bg-opacity-15 dark:bg-teal-400/20">
                                    <i class="fas fa-chart-pie text-xl text-teal-700 dark:text-teal-300"></i>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center border-b border-teal-200 dark:border-teal-500/30 pb-2">
                                    <p class="text-sm text-teal-700 dark:text-teal-300">Rata-rata Mesin/Unit</p>
                                    <span class="text-sm font-bold text-teal-800 dark:text-teal-200">
                                        {{ $units->count() > 0 ? number_format($totalMachines / $units->count(), 1) : '0' }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center pt-1">
                                    <p class="text-sm text-teal-700 dark:text-teal-300">Total Kapasitas</p>
                                    <span class="text-sm font-bold text-teal-800 dark:text-teal-200">
                                        {{ number_format($units->sum(function($unit) { 
                                            return $unit->machines->sum('capacity');
                                        }), 0) }} MW
                                    </span>
                                </div>
                            </div>
                            <div class="mt-3 border-t border-teal-200 dark:border-teal-500/30 pt-3">
                                <a href="{{ route('analytics') }}" 
                                   class="text-sm text-teal-700 dark:text-teal-300 hover:text-teal-800 dark:hover:text-teal-100 inline-flex items-center transition-colors duration-200">
                                    <span>Lihat analisis lengkap</span>
                                    <i class="fas fa-arrow-right ml-1.5 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white">
                            <i class="fas fa-history mr-2 text-gray-600 dark:text-gray-400"></i>
                            Aktivitas Terbaru
                        </h3>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($units->take(5) as $unit)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <!-- Header with Unit Name -->
                                            <div class="flex items-center space-x-3 mb-4">
                                                <div class="flex-shrink-0 p-2 rounded-full bg-blue-100 dark:bg-blue-900">
                                                    <i class="fas fa-industry text-blue-600 dark:text-blue-400"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-lg font-medium text-gray-800 dark:text-white">
                                                        <a href="{{ route('unit-mesin.show', $unit) }}" 
                                                           class="hover:text-blue-500 dark:hover:text-blue-400">
                                                            {{ $unit->name }}
                                                        </a>
                                                    </h4>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">Unit Pembangkit</p>
                                                </div>
                                            </div>

                                            <!-- Statistics Grid -->
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                <!-- Total Mesin -->
                                                <div class="p-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Mesin</p>
                                                    <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                                        {{ $unit->machines->count() }}
                                                    </p>
                                                </div>

                                                <!-- Status -->
                                                <div class="p-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                                                    <p class="text-lg font-semibold text-green-500">
                                                        Aktif
                                                    </p>
                                                </div>

                                                <!-- Kapasitas -->
                                                <div class="p-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">Kapasitas</p>
                                                    <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                                        {{ number_format($unit->machines->sum('capacity'), 0) }} MW
                                                    </p>
                                                </div>

                                                <!-- Utilisasi -->
                                                <div class="p-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">Utilisasi</p>
                                                    <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                                        {{ number_format($unit->machines->avg('utilization'), 1) }}%
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex space-x-2 ml-4">
                                            <a href="{{ route('unit-mesin.show', $unit) }}" 
                                               class="p-2 text-gray-400 hover:text-blue-500 dark:hover:text-blue-400 transition-colors duration-200"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('unit-mesin.edit', $unit) }}" 
                                               class="p-2 text-gray-400 hover:text-blue-500 dark:hover:text-blue-400 transition-colors duration-200"
                                               title="Edit Unit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if($units->isEmpty())
                                <div class="text-center py-8">
                                    <div class="text-gray-400 dark:text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-4"></i>
                                        <p class="text-lg">Belum ada data unit dan mesin.</p>
                                        <p class="text-sm mt-2">Tambahkan unit baru untuk memulai.</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($units->count() > 5)
                            <div class="mt-6 text-center">
                                <a href="{{ route('unit-mesin.unit') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
                                    <span>Lihat Semua Unit</span>
                                    <i class="fas fa-arrow-right ml-2"></i>
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