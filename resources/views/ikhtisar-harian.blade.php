@extends('layouts.app')

@section('title', 'Ikhtisar Harian')

@php
    $baseUrl = url('/');
@endphp

@section('content')
<div class="min-h-screen bg-gray-100"
     x-data="{ 
        isSidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
        isDarkMode: localStorage.getItem('darkMode') === 'true',
        activeTab: 'input',
        showAlert: false,
        selectedUnit: null,
        selectedMachine: null,
        machines: [],
        showForm: false,
        selectedMachineName: '',
        init() {
            this.$watch('isSidebarOpen', value => localStorage.setItem('sidebarOpen', value))
            this.$watch('isDarkMode', value => {
                localStorage.setItem('darkMode', value)
                document.documentElement.classList.toggle('dark', value)
            })
            
            // Show alert if success message exists
            if (@json(session()->has('success'))) {
                this.showAlert = true;
            }
        },
        async loadMachines() {
            if (this.selectedUnit) {
                try {
                    const response = await fetch(`{{ $baseUrl }}/api/units/${this.selectedUnit}/machines`);
                    if (!response.ok) {
                        console.error('Response not OK:', response.status);
                        throw new Error('Network response was not ok');
                    }
                    const data = await response.json();
                    console.log('API Response:', data); // Debug log
                    this.machines = data;
                    this.selectedMachine = null;
                    this.showForm = false;
                    this.selectedMachineName = '';
                } catch (error) {
                    console.error('Error loading machines:', error);
                    this.machines = [];
                    this.selectedMachine = null;
                    this.showForm = false;
                }
            } else {
                this.machines = [];
                this.selectedMachine = null;
                this.showForm = false;
            }
        },
        watchMachine() {
            this.showForm = this.selectedUnit && this.selectedMachine ? true : false;
            if (this.selectedMachine) {
                const machine = this.machines.find(m => m.id == this.selectedMachine);
                this.selectedMachineName = machine ? machine.name : '';
            }
        }
     }">
    
    @include('layouts.sidebar')

    <!-- Success Alert -->
    <div x-show="showAlert"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <!-- Background backdrop -->
        <div x-show="showAlert"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-75"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-75"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>

        <div class="flex items-center justify-center min-h-full p-4 text-center">
            <div x-show="showAlert"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-center flex-col text-center">
                        <div class="mx-auto flex h-24 w-24 flex-shrink-0 items-center justify-center rounded-full bg-green-100 animate-[bounce_1s_ease-in-out]">
                            <i class="fas fa-check text-4xl text-green-600 animate-[scale_0.5s_ease-in-out]"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0">
                            <h3 class="text-xl font-semibold leading-6 text-gray-900 mb-2 animate-[fadeIn_0.5s_ease-in-out]">
                                Berhasil!
                            </h3>
                            <div class="mt-2">
                                <p class="text-gray-600 animate-[slideUp_0.5s_ease-in-out]">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 justify-center">
                    <button type="button" 
                            @click="showAlert = false"
                            class="inline-flex w-24 justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transform transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes scale {
            0% { transform: scale(0); }
            70% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>

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
                    <h1 class="text-xl font-semibold text-gray-900">Ikhtisar Harian</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ now()->format('d M Y') }}</span>
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
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                    <!-- Total Production -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 overflow-hidden shadow rounded-lg text-white">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="space-y-2">
                                    <div class="text-sm font-medium opacity-75">Total Produksi Bruto</div>
                                    <div class="text-2xl font-bold">{{ number_format($todayData->sum('gross_production'), 0) }} kWh</div>
                                    <div class="inline-flex">
                                        <span class="bg-blue-400 bg-opacity-50 px-2 py-1 rounded-full text-sm">
                                            {{ $todayData->where('operational_status', 'operational')->count() }} Unit Aktif
                                        </span>
                                    </div>
                                </div>
                                <div class="text-3xl opacity-75">
                                    <i class="fas fa-bolt"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Net Production -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 overflow-hidden shadow rounded-lg text-white">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="space-y-2">
                                    <div class="text-sm font-medium opacity-75">Total Produksi Netto</div>
                                    <div class="text-2xl font-bold">{{ number_format($todayData->sum('net_production'), 0) }} kWh</div>
                                    <div class="inline-flex">
                                        <span class="bg-green-400 bg-opacity-50 px-2 py-1 rounded-full text-sm">
                                            Efisiensi {{ number_format($todayData->sum('net_production') / max($todayData->sum('gross_production'), 1) * 100, 1) }}%
                                        </span>
                                    </div>
                                </div>
                                <div class="text-3xl opacity-75">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Peak Load -->
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 overflow-hidden shadow rounded-lg text-white">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="space-y-2">
                                    <div class="text-sm font-medium opacity-75">Beban Puncak</div>
                                    <div class="text-2xl font-bold">{{ number_format($todayData->max('peak_load_day'), 0) }} kW</div>
                                    <div class="inline-flex">
                                        <span class="bg-purple-400 bg-opacity-50 px-2 py-1 rounded-full text-sm">
                                            Siang = Malam
                                        </span>
                                    </div>
                                </div>
                                <div class="text-3xl opacity-75">
                                    <i class="fas fa-tachometer-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Operating Hours -->
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 overflow-hidden shadow rounded-lg text-white">
                        <div class="p-5">
                            <div class="flex items-center justify-between">
                                <div class="space-y-2">
                                    <div class="text-sm font-medium opacity-75">Total Jam Operasi</div>
                                    <div class="text-2xl font-bold">{{ number_format($todayData->sum('operating_hours'), 0) }} Jam</div>
                                    <div class="inline-flex">
                                        <span class="bg-yellow-400 bg-opacity-50 px-2 py-1 rounded-full text-sm">
                                            Dari 744 Jam
                                        </span>
                                    </div>
                                </div>
                                <div class="text-3xl opacity-75">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                            <button @click="activeTab = 'input'"
                                    :class="{'border-blue-500 text-blue-600': activeTab === 'input',
                                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'input'}"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Input Data
                            </button>
                            <button @click="activeTab = 'view'"
                                    :class="{'border-blue-500 text-blue-600': activeTab === 'view',
                                            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'view'}"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                Lihat Data
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Input Data Tab -->
                <div x-show="activeTab === 'input'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100">
                    
                    <form method="POST" action="{{ route('ikhtisar-harian.store') }}">
                        @csrf
                        
                        <!-- Submit Button at Top -->
                        <div class="mb-6 flex justify-end">
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Semua Data
                            </button>
                        </div>

                        @foreach($units as $unit)
                        <div class="bg-white shadow rounded-lg p-6 mb-6">
                            <!-- Unit Header -->
                            <div class="mb-6 text-center">
                                <h2 class="text-xl font-bold text-gray-800">{{ $unit->name }}</h2>
                                <p class="text-sm text-gray-600">Data Operasional Harian</p>
                            </div>

                            <!-- Machines Table -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Mesin</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Daya (MW)</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Beban (MW)</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Produksi (kWh)</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Operasi</th>
                                        </tr>
                                        <tr class="bg-gray-100 text-xs">
                                            <th class="border-r"></th>
                                            <th class="px-4 py-2 border-r">
                                                <div class="grid grid-cols-3 gap-2 text-center">
                                                    <span class="border-r px-2">Terpasang</span>
                                                    <span class="border-r px-2">DMN</span>
                                                    <span class="px-2">Mampu</span>
                                                </div>
                                            </th>
                                            <th class="px-4 py-2 border-r">
                                                <div class="grid grid-cols-2 gap-2 text-center">
                                                    <span class="border-r px-2">WBP</span>
                                                    <span class="px-2">LWBP</span>
                                                </div>
                                            </th>
                                            <th class="px-4 py-2 border-r">
                                                <div class="grid grid-cols-2 gap-2 text-center">
                                                    <span class="border-r px-2">Bruto</span>
                                                    <span class="px-2">Netto</span>
                                                </div>
                                            </th>
                                            <th class="px-4 py-2">
                                                <div class="grid grid-cols-4 gap-2 text-center">
                                                    <span class="border-r px-2">OPR</span>
                                                    <span class="border-r px-2">PO</span>
                                                    <span class="border-r px-2">MO</span>
                                                    <span class="px-2">FO</span>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($unit->machines as $machine)
                                        <tr>
                                            <td class="px-4 py-3 border-r">
                                                <div class="text-sm font-medium text-gray-900 text-center">{{ $machine->name }}</div>
                                                <input type="hidden" name="data[{{ $machine->id }}][unit_id]" value="{{ $unit->id }}">
                                                <input type="hidden" name="data[{{ $machine->id }}][machine_id]" value="{{ $machine->id }}">
                                            </td>
                                            <td class="px-4 py-3 border-r">
                                                <div class="grid grid-cols-3 gap-2">
                                                    <div class="border-r pr-2">
                                                        <input type="number" step="0.001" name="data[{{ $machine->id }}][installed_power]" 
                                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                    </div>
                                                    <div class="border-r pr-2">
                                                        <input type="number" step="0.001" name="data[{{ $machine->id }}][dmn_power]"
                                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                    </div>
                                                    <div>
                                                        <input type="number" step="0.001" name="data[{{ $machine->id }}][capable_power]"
                                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border-r">
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div class="border-r pr-2">
                                                        <input type="number" step="0.001" name="data[{{ $machine->id }}][peak_load]"
                                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                    </div>
                                                    <div>
                                                        <input type="number" step="0.001" name="data[{{ $machine->id }}][off_peak_load]"
                                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3 border-r">
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div class="border-r pr-2">
                                                        <input type="number" step="0.001" name="data[{{ $machine->id }}][gross_production]"
                                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                    </div>
                                                    <div>
                                                        <input type="number" step="0.001" name="data[{{ $machine->id }}][net_production]"
                                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="grid grid-cols-4 gap-2">
                                                    <div class="border-r pr-2">
                                                        <input type="number" step="0.01" name="data[{{ $machine->id }}][operating_hours]"
                                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                    </div>
                                                    <div class="border-r pr-2">
                                                        <input type="number" step="0.01" name="data[{{ $machine->id }}][planned_outage]"
                                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                    </div>
                                                    <div class="border-r pr-2">
                                                        <input type="number" step="0.01" name="data[{{ $machine->id }}][maintenance_outage]"
                                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                    </div>
                                                    <div>
                                                        <input type="number" step="0.01" name="data[{{ $machine->id }}][forced_outage]"
                                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                    </form>
                </div>

                <!-- View Data Tab -->
                <div x-show="activeTab === 'view'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100">
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Data Unit Hari Ini</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daya (MW)</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beban</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produksi</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Operasi</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($todayData as $data)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $data->unit->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $data->machine->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ number_format($data->installed_power, 2) }}</div>
                                                <div class="text-xs text-gray-500">DMN: {{ number_format($data->dmn_power, 2) }}</div>
                                                <div class="text-xs text-gray-500">Mampu: {{ number_format($data->capable_power, 2) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">WBP: {{ number_format($data->peak_load, 2) }}</div>
                                                <div class="text-xs text-gray-500">LWBP: {{ number_format($data->off_peak_load, 2) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">Bruto: {{ number_format($data->gross_production, 2) }}</div>
                                                <div class="text-xs text-gray-500">Netto: {{ number_format($data->net_production, 2) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">OPR: {{ number_format($data->operating_hours, 1) }}</div>
                                                <div class="text-xs text-gray-500">PO: {{ number_format($data->planned_outage, 1) }}</div>
                                                <div class="text-xs text-gray-500">MO: {{ number_format($data->maintenance_outage, 1) }}</div>
                                                <div class="text-xs text-gray-500">FO: {{ number_format($data->forced_outage, 1) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $data->operating_hours > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $data->operating_hours > 0 ? 'Operasi' : 'Tidak Operasi' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                Belum ada data untuk hari ini
                                            </td>
                                        </tr>
                                        @endforelse
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

<script>
    function formHandler() {
        return {
            selectedUnit: null,
            selectedMachine: null,
            machines: [],
            showForm: false,
            selectedMachineName: '',
            async loadMachines() {
                if (this.selectedUnit) {
                    try {
                        const response = await fetch(`{{ $baseUrl }}/api/units/${this.selectedUnit}/machines`);
                        if (!response.ok) {
                            console.error('Response not OK:', response.status);
                            throw new Error('Network response was not ok');
                        }
                        const data = await response.json();
                        this.machines = data;
                        this.selectedMachine = null;
                        this.showForm = false;
                        this.selectedMachineName = '';
                    } catch (error) {
                        console.error('Error loading machines:', error);
                        this.machines = [];
                        this.selectedMachine = null;
                        this.showForm = false;
                    }
                } else {
                    this.machines = [];
                    this.selectedMachine = null;
                    this.showForm = false;
                }
            },
            watchMachine() {
                this.showForm = this.selectedUnit && this.selectedMachine ? true : false;
                if (this.selectedMachine) {
                    const machine = this.machines.find(m => m.id == this.selectedMachine);
                    this.selectedMachineName = machine ? machine.name : '';
                }
            }
        }
    }
</script>
@endsection 