@extends('layouts.app')

@section('title', 'Reports')

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
                    <h1 class="text-xl font-semibold text-gray-900">Reports</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-file-export mr-2"></i>
                        Export Report
                    </button>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Report Filters -->
                    <div class="bg-white shadow rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Laporan</h3>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                <div>
                                    <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                                    <select id="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Semua Unit</option>
                                        <option>Unit 1</option>
                                        <option>Unit 2</option>
                                        <option>Unit 3</option>
                                        <option>Unit 4</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="date-range" class="block text-sm font-medium text-gray-700">Rentang Waktu</label>
                                    <select id="date-range" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option>Hari Ini</option>
                                        <option>Minggu Ini</option>
                                        <option>Bulan Ini</option>
                                        <option>Custom Range</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Tipe Report</label>
                                    <select id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option>Produksi</option>
                                        <option>Efisiensi</option>
                                        <option>Maintenance</option>
                                        <option>Full Report</option>
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <button class="w-full bg-blue-50 text-blue-600 px-4 py-2 rounded-md hover:bg-blue-100">
                                        <i class="fas fa-filter mr-2"></i>
                                        Terapkan Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Summary -->
                    <div class="bg-white shadow rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Bulan Ini</h3>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <div class="text-sm font-medium text-blue-600">Total Produksi</div>
                                    <div class="mt-1">
                                        <div class="text-2xl font-semibold text-blue-900">48,560 MW</div>
                                        <div class="text-sm text-blue-600">+2.5% dari bulan lalu</div>
                                    </div>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <div class="text-sm font-medium text-green-600">Rata-rata Efisiensi</div>
                                    <div class="mt-1">
                                        <div class="text-2xl font-semibold text-green-900">92.8%</div>
                                        <div class="text-sm text-green-600">+1.2% dari bulan lalu</div>
                                    </div>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg">
                                    <div class="text-sm font-medium text-purple-600">Total Jam Operasi</div>
                                    <div class="mt-1">
                                        <div class="text-2xl font-semibold text-purple-900">720 Jam</div>
                                        <div class="text-sm text-purple-600">98.3% uptime</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Data Table -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Data Bulanan</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produksi (MW)</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Efisiensi</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Operasi</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($monthlyData as $data)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $data['tanggal'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $data['unit'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $data['machine'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($data['production']['net'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $data['production']['efficiency'] }}%
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $data['operating_data']['hours'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $data['status'] === 'operational' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $data['status'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <button class="text-blue-600 hover:text-blue-900 mr-3">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection 