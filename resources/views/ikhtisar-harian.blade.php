@extends('layouts.app')

@section('title', 'Ikhtisar Harian')

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
                const response = await fetch(`/api/units/${this.selectedUnit}/machines`);
                this.machines = await response.json();
                this.selectedMachine = null;
            } else {
                this.machines = [];
                this.selectedMachine = null;
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
                    <h1 class="text-xl font-semibold text-gray-900">Ikhtisar Harian PLTD WUAWUA</h1>
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

                <!-- Tabs -->
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

                <!-- Form Input Section -->
                <div class="bg-white shadow rounded-lg p-6">
                    <form method="POST" action="{{ route('ikhtisar-harian.store') }}"
                          x-data="{
                              selectedUnit: null,
                              selectedMachine: null,
                              machines: [],
                              showForm: false,
                              async loadMachines() {
                                  if (this.selectedUnit) {
                                      const response = await fetch(`/api/units/${this.selectedUnit}/machines`);
                                      this.machines = await response.json();
                                      this.selectedMachine = null;
                                      this.showForm = false;
                                  } else {
                                      this.machines = [];
                                      this.selectedMachine = null;
                                      this.showForm = false;
                                  }
                              },
                              watchMachine() {
                                  this.showForm = this.selectedUnit && this.selectedMachine ? true : false;
                              }
                          }"
                          @change="watchMachine()">
                        @csrf
                        
                        <!-- Unit & Machine Selection -->
                        <div class="space-y-6 mb-8">
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Unit Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Pilih Unit
                                        </label>
                                        <select name="unit_id" 
                                                x-model="selectedUnit"
                                                @change="loadMachines()"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Pilih Unit</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Machine Selection -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Pilih Mesin
                                        </label>
                                        <select name="machine_id" 
                                                x-model="selectedMachine"
                                                :disabled="!selectedUnit"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">Pilih Mesin</option>
                                            <template x-for="machine in machines" :key="machine.id">
                                                <option :value="machine.id" x-text="machine.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Input Fields - Only shown when unit and machine are selected -->
                            <div x-show="showForm" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 class="space-y-6">
                                
                                <!-- Daya Section -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900 mb-4">Daya (MW)</h4>
                                    <div class="grid grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm text-gray-700">Terpasang</label>
                                            <input type="number" 
                                                   step="0.001" 
                                                   name="installed_power" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-700">DMN</label>
                                            <input type="number" 
                                                   step="0.001" 
                                                   name="dmn_power" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-700">Mampu</label>
                                            <input type="number" 
                                                   step="0.001" 
                                                   name="capable_power" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>

                                <!-- Beban Section -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900 mb-4">Beban (MW)</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm text-gray-700">WBP</label>
                                            <input type="number" 
                                                   step="0.001" 
                                                   name="peak_load" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-700">LWBP</label>
                                            <input type="number" 
                                                   step="0.001" 
                                                   name="off_peak_load" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>

                                <!-- Produksi Section -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900 mb-4">Produksi (kWh)</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm text-gray-700">Bruto</label>
                                            <input type="number" 
                                                   step="0.001" 
                                                   name="gross_production" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-700">Netto</label>
                                            <input type="number" 
                                                   step="0.001" 
                                                   name="net_production" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>

                                <!-- Jam Operasi Section -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900 mb-4">Jam Operasi</h4>
                                    <div class="grid grid-cols-4 gap-4">
                                        <div>
                                            <label class="block text-sm text-gray-700">OPR</label>
                                            <input type="number" 
                                                   step="0.01" 
                                                   name="operating_hours" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-700">PO</label>
                                            <input type="number" 
                                                   step="0.01" 
                                                   name="planned_outage" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-700">MO</label>
                                            <input type="number" 
                                                   step="0.01" 
                                                   name="maintenance_outage" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-700">FO</label>
                                            <input type="number" 
                                                   step="0.01" 
                                                   name="forced_outage" 
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="flex justify-end">
                                    <button type="submit" 
                                            class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <i class="fas fa-save mr-2"></i>
                                        Simpan Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- View Data -->
                <div x-show="activeTab === 'view'" class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Data Unit Hari Ini</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daya (kW)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beban Puncak</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produksi</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemakaian Sendiri</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Operasi</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($todayData as $data)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $data->powerUnit->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ number_format($data->installed_power, 0) }}</div>
                                            <div class="text-xs text-gray-500">DMN: {{ number_format($data->dmn_power, 0) }}</div>
                                            <div class="text-xs text-gray-500">Mampu: {{ number_format($data->capable_power, 0) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">WBP: {{ number_format($data->peak_load, 0) }}</div>
                                            <div class="text-xs text-gray-500">LWBP: {{ number_format($data->off_peak_load, 0) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">Bruto: {{ number_format($data->gross_production, 0) }}</div>
                                            <div class="text-xs text-gray-500">Netto: {{ number_format($data->net_production, 0) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">OPR: {{ number_format($data->operating_hours, 1) }}</div>
                                            <div class="text-xs text-gray-500">PO: {{ number_format($data->planned_outage, 1) }}</div>
                                            <div class="text-xs text-gray-500">MO: {{ number_format($data->maintenance_outage, 1) }}</div>
                                            <div class="text-xs text-gray-500">FO: {{ number_format($data->forced_outage, 1) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $data->operational_status === 'operational' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $data->operational_status }}
                                            </span>
                                            @if($data->machine_trips > 0 || $data->electrical_trips > 0)
                                            <div class="text-xs text-gray-500 mt-1">
                                                Trip: M{{ $data->machine_trips }}/L{{ $data->electrical_trips }}
                                            </div>
                                            @endif
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
        </main>
    </div>
</div>
@endsection 