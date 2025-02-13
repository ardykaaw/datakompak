@extends('layouts.app')

@section('title', 'Settings')

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
                    <h1 class="text-xl font-semibold text-gray-900">Settings</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- General Settings -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Umum</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="site-name" class="block text-sm font-medium text-gray-700">Nama Situs</label>
                                        <input type="text" id="site-name" value="PLN Nusantara Power" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label for="timezone" class="block text-sm font-medium text-gray-700">Zona Waktu</label>
                                        <select id="timezone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option>Asia/Jakarta (WIB)</option>
                                            <option>Asia/Makassar (WITA)</option>
                                            <option>Asia/Jayapura (WIT)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="language" class="block text-sm font-medium text-gray-700">Bahasa</label>
                                        <select id="language" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option>Bahasa Indonesia</option>
                                            <option>English</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Notifikasi</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">Email Notifications</h4>
                                            <p class="text-sm text-gray-500">Terima notifikasi via email</p>
                                        </div>
                                        <button type="button" class="bg-gray-200 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            <span class="translate-x-5 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                        </button>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">Alert Notifications</h4>
                                            <p class="text-sm text-gray-500">Terima notifikasi alert sistem</p>
                                        </div>
                                        <button type="button" class="bg-blue-600 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            <span class="translate-x-5 inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                        </button>
                                    </div>
                                    <div>
                                        <label for="alert-threshold" class="block text-sm font-medium text-gray-700">Alert Threshold (%)</label>
                                        <input type="number" id="alert-threshold" value="90" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Unit Settings -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Unit</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="unit-count" class="block text-sm font-medium text-gray-700">Jumlah Unit</label>
                                        <input type="number" id="unit-count" value="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label for="maintenance-schedule" class="block text-sm font-medium text-gray-700">Jadwal Maintenance</label>
                                        <select id="maintenance-schedule" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option>Setiap Bulan</option>
                                            <option>Setiap 3 Bulan</option>
                                            <option>Setiap 6 Bulan</option>
                                            <option>Custom</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="efficiency-target" class="block text-sm font-medium text-gray-700">Target Efisiensi (%)</label>
                                        <input type="number" id="efficiency-target" value="95" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Backup Settings -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Backup</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label for="backup-frequency" class="block text-sm font-medium text-gray-700">Frekuensi Backup</label>
                                        <select id="backup-frequency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option>Setiap Hari</option>
                                            <option>Setiap Minggu</option>
                                            <option>Setiap Bulan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="backup-retention" class="block text-sm font-medium text-gray-700">Retention Period (hari)</label>
                                        <input type="number" id="backup-retention" value="30" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div class="pt-4">
                                        <button class="bg-blue-50 text-blue-600 px-4 py-2 rounded-md hover:bg-blue-100 w-full">
                                            <i class="fas fa-download mr-2"></i>
                                            Backup Manual
                                        </button>
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
@endsection 