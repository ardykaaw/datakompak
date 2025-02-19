@extends('layouts.app')

@section('title', 'Laporan Kesiapan KIT')

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
                    <h1 class="text-xl font-semibold text-gray-900">Laporan Kesiapan KIT</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('laporan-kesiapan-kit.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus mr-2"></i>
                        Input Kesiapan
                    </a>
                    <span class="text-sm text-gray-500">{{ now()->format('d M Y') }}</span>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-4">
            <div class="max-w-7xl mx-auto space-y-6">
                @foreach($units as $unit)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <!-- Unit Header -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">{{ $unit->name }}</h2>
                    </div>

                    <!-- Unit's Machines Table -->
                    <div class="p-6 border border-gray-200 rounded-lg">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daya Mampu SILM (MW)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daya Mampu Pasok (MW)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beban (MW)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Update</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($unit->machines as $index => $machine)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 border-r border-gray-200">{{ $machine->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                            {{ $machine->logs->first() ? number_format($machine->logs->first()->capable_power ?? 0, 2) : '0.00' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200   ">
                                            {{ $machine->logs->first() ? number_format($machine->logs->first()->supply_power ?? 0, 2) : '0.00' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                            {{ $machine->logs->first() ? number_format($machine->logs->first()->current_load ?? 0, 2) : '0.00' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{
                                                    ($machine->logs->first()->status ?? '') === 'OPS' ? 'bg-green-100 text-green-800' :
                                                    (($machine->logs->first()->status ?? '') === 'RSH' ? 'bg-yellow-100 text-yellow-800' :
                                                    (($machine->logs->first()->status ?? '') === 'FO' ? 'bg-red-100 text-red-800' :
                                                    (($machine->logs->first()->status ?? '') === 'MO' ? 'bg-orange-100 text-orange-800' :
                                                    (($machine->logs->first()->status ?? '') === 'PO' ? 'bg-blue-100 text-blue-800' :
                                                    'bg-gray-100 text-gray-800'))))
                                                }}">
                                                {{ $machine->logs->first()->status ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-r border-gray-200">
                                            {{ $machine->logs->first() ? $machine->logs->first()->input_time->format('d/m/Y H:i') : '-' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </main>
    </div>
</div>
@endsection
