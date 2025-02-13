@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen bg-gray-100"
     x-data="{ 
        isSidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
        isDarkMode: localStorage.getItem('darkMode') === 'true',
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
                    <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">Last Updated: {{ now()->format('d M Y H:i') }}</span>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh Data
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-4">
            <div class="max-w-7xl mx-auto">
                <!-- Main Stats -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-6">
                    <!-- Total Produksi Card -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow rounded-lg text-white">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="space-y-2">
                                    <div class="text-sm font-medium opacity-75">Total Produksi Hari Ini</div>
                                    <div class="text-2xl font-bold">2,400 MW</div>
                                    <div class="inline-flex">
                                        <span class="bg-blue-400 bg-opacity-50 px-2 py-1 rounded-full text-sm">
                                            3 Unit Aktif
                                        </span>
                                    </div>
                                </div>
                                <div class="text-3xl opacity-75">
                                    <i class="fas fa-bolt"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-blue-600 px-5 py-3">
                            <a href="{{ route('ikhtisar-harian') }}" class="flex items-center justify-between text-sm hover:opacity-75 transition-opacity">
                                <span>Lihat Detail Ikhtisar</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- From Analytics -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow rounded-lg text-white">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium opacity-75">Efisiensi Rata-rata</div>
                                    <div class="text-2xl font-bold mt-2">92.8%</div>
                                    <div class="text-sm mt-2">
                                        <span class="bg-green-400 bg-opacity-50 px-2 py-1 rounded-full">
                                            +2.3% dari bulan lalu
                                        </span>
                                    </div>
                                </div>
                                <div class="text-3xl opacity-75">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-600 px-5 py-3">
                            <a href="{{ route('analytics') }}" class="flex items-center justify-between text-sm">
                                <span>Lihat Analytics</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- From Reports -->
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 overflow-hidden shadow rounded-lg text-white">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium opacity-75">Laporan Bulan Ini</div>
                                    <div class="text-2xl font-bold mt-2">28 Laporan</div>
                                    <div class="text-sm mt-2">
                                        <span class="bg-purple-400 bg-opacity-50 px-2 py-1 rounded-full">
                                            5 Perlu Review
                                        </span>
                                    </div>
                                </div>
                                <div class="text-3xl opacity-75">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-purple-600 px-5 py-3">
                            <a href="{{ route('reports') }}" class="flex items-center justify-between text-sm">
                                <span>Lihat Semua Laporan</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Access & Recent Activities -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Quick Access -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Akses Cepat</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <a href="{{ route('ikhtisar-harian') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                    <i class="fas fa-clipboard-list text-2xl text-blue-500 mr-4"></i>
                                    <div>
                                        <div class="font-medium">Ikhtisar Harian</div>
                                        <div class="text-sm text-gray-500">Input data hari ini</div>
                                    </div>
                                </a>
                                <a href="{{ route('analytics') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                    <i class="fas fa-chart-bar text-2xl text-green-500 mr-4"></i>
                                    <div>
                                        <div class="font-medium">Analytics</div>
                                        <div class="text-sm text-gray-500">Lihat performa</div>
                                    </div>
                                </a>
                                <a href="{{ route('reports') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                    <i class="fas fa-file-alt text-2xl text-purple-500 mr-4"></i>
                                    <div>
                                        <div class="font-medium">Reports</div>
                                        <div class="text-sm text-gray-500">Generate laporan</div>
                                    </div>
                                </a>
                                <a href="{{ route('settings') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                    <i class="fas fa-cog text-2xl text-gray-500 mr-4"></i>
                                    <div>
                                        <div class="font-medium">Settings</div>
                                        <div class="text-sm text-gray-500">Konfigurasi sistem</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Aktivitas Terkini</h3>
                            <div class="space-y-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <span class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-clipboard-check text-blue-500"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            Ikhtisar Harian Diperbarui
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Unit 1: 600 MW, Efisiensi 98%
                                        </p>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        5 menit yang lalu
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <span class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                            <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            Maintenance Alert
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Unit 2 dijadwalkan untuk maintenance
                                        </p>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        1 jam yang lalu
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <span class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-chart-line text-green-500"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900">
                                            Laporan Analytics
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Efisiensi meningkat 2.3%
                                        </p>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        3 jam yang lalu
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button class="text-sm text-blue-600 hover:text-blue-800">
                                    Lihat semua aktivitas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Overview -->
                <div class="mt-6 bg-white shadow rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Overview Performa Unit</h3>
                            <select class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option>7 Hari Terakhir</option>
                                <option>30 Hari Terakhir</option>
                                <option>3 Bulan Terakhir</option>
                            </select>
                        </div>
                        <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                            <div class="text-center text-gray-500">
                                <i class="fas fa-chart-area text-4xl mb-2"></i>
                                <p>Grafik performa akan ditampilkan di sini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection