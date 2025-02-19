@extends('layouts.app')

@section('title', 'Input Laporan Kesiapan KIT')

@section('content')
<div class="min-h-screen bg-gray-100"
     x-data="{ 
        isSidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
        isDarkMode: localStorage.getItem('darkMode') === 'true',
        selectedUnit: null,
        selectedMachine: null,
        machines: [],
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
                    <h1 class="text-xl font-semibold text-gray-900">Input Laporan Kesiapan KIT</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ now()->format('d M Y') }}</span>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-4">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <form action="{{ route('laporan-kesiapan-kit.store') }}" method="POST" class="p-6 space-y-6">
                        @csrf
                        
                        <!-- Time Selection -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <label for="input_time" class="block text-sm font-medium text-gray-700">Waktu Input</label>
                                <select name="input_time" id="input_time" required
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    <option value="">Pilih Waktu</option>
                                    <option value="06:00">06:00 (Pagi)</option>
                                    <option value="11:00">11:00 (Siang)</option>
                                    <option value="14:00">14:00 (Siang)</option>
                                    <option value="18:00">18:00 (Malam)</option>
                                    <option value="19:00">19:00 (Malam)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Units and Machines Input -->
                        @foreach($units as $unit)
                        <div class="border rounded-lg p-4 space-y-4 bg-gray-50">
                            <h3 class="text-lg font-medium text-gray-900">{{ $unit->name }}</h3>
                            
                            @foreach($unit->machines as $machine)
                            <div class="bg-white p-4 rounded-lg shadow-sm space-y-4">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-md font-medium text-gray-700">{{ $machine->name }}</h4>
                                </div>
                                
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                    <div>
                                        <label for="capable_power_{{ $machine->id }}" class="block text-sm font-medium text-gray-700">
                                            Daya Mampu SILM (MW)
                                        </label>
                                        <input type="number" step="0.01" 
                                               name="data[{{ $machine->id }}][capable_power]" 
                                               id="capable_power_{{ $machine->id }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               required>
                                    </div>
                                    
                                    <div>
                                        <label for="supply_power_{{ $machine->id }}" class="block text-sm font-medium text-gray-700">
                                            Daya Mampu Pasok (MW)
                                        </label>
                                        <input type="number" step="0.01" 
                                               name="data[{{ $machine->id }}][supply_power]" 
                                               id="supply_power_{{ $machine->id }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               required>
                                    </div>
                                    
                                    <div>
                                        <label for="current_load_{{ $machine->id }}" class="block text-sm font-medium text-gray-700">
                                            Beban (MW)
                                        </label>
                                        <input type="number" step="0.01" 
                                               name="data[{{ $machine->id }}][current_load]" 
                                               id="current_load_{{ $machine->id }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               required>
                                    </div>
                                </div>

                                <div>
                                    <label for="status_{{ $machine->id }}" class="block text-sm font-medium text-gray-700">
                                        Status
                                    </label>
                                    <select name="data[{{ $machine->id }}][status]" 
                                            id="status_{{ $machine->id }}"
                                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                            required>
                                        <option value="">Pilih Status</option>
                                        <option value="OPS">OPS - Operational</option>
                                        <option value="RSH">RSH - Reserve Shutdown</option>
                                        <option value="FO">FO - Forced Outage</option>
                                        <option value="MO">MO - Maintenance Outage</option>
                                        <option value="PO">PO - Planned Outage</option>
                                    </select>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-5">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection 