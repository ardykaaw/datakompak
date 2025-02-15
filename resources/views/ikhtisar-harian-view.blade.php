@extends('layouts.app')

@section('title', 'Data Ikhtisar Harian')

@section('content')
<div class="min-h-screen bg-gray-100"
     x-data="{ 
        isSidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
        isDarkMode: localStorage.getItem('darkMode') === 'true'
     }">
    
    @include('layouts.sidebar')

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
                    <h1 class="text-xl font-semibold text-gray-900">Data Ikhtisar Harian</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <form action="{{ route('ikhtisar-harian.export') }}" method="GET" class="inline">
                        <!-- Hidden inputs untuk menyertakan filter saat ini -->
                        <input type="hidden" name="unit" value="{{ request('unit') }}">
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        
                        <button type="submit" 
                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors duration-200">
                            <i class="fas fa-file-excel mr-2"></i>
                            Export Excel
                        </button>
                    </form>
                    
                    <a href="{{ route('ikhtisar-harian') }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>
                        Input Data
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-4">
            <div class="max-w-7xl mx-auto">
                <!-- Filter dan Pencarian -->
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <form action="{{ route('ikhtisar-harian.view') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unit</label>
                            <select name="unit" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    onchange="this.form.submit()">
                                <option value="">Semua Unit</option>
                                @foreach($units as $unit)
                                    @if($unit->name !== 'UP KENDARI')
                                        <option value="{{ $unit->id }}" {{ request('unit') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="date" 
                                   name="start_date" 
                                   value="{{ request('start_date') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                            <input type="date" 
                                   name="end_date" 
                                   value="{{ request('end_date') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-search mr-2"></i>
                                Filter
                            </button>
                        </div>
                    </form>
                </div>

                @if(request('unit'))
                    @php
                        $selectedUnit = $units->find(request('unit'));
                    @endphp
                    @if($selectedUnit && $selectedUnit->name !== 'UP KENDARI')
                        <div class="bg-white shadow rounded-lg p-6 mb-6">
                            <!-- Unit Header -->
                            <div class="mb-6">
                                <h2 class="text-xl font-bold text-gray-800">{{ $selectedUnit->name }}</h2>
                                <p class="text-sm text-gray-600">Data Operasional Harian</p>
                            </div>

                            <!-- Tabel Data -->
                            <div class="relative">
                                <div class="overflow-x-auto overflow-y-visible shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                    <div class="min-w-full inline-block align-middle">
                                        <table class="min-w-full divide-y divide-gray-200 border table-fixed" style="min-width: 3800px;">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">No.</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Unit</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="3">Daya (MW)</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="2">Beban Puncak (kW)</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Ratio Daya Kit (%)</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="2">Produksi (kWh)</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="3">Pemakaian Sendiri</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Jam Periode</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="5">Jam Operasi</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="2">Trip Non OMC</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="4">Derating</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="4">Kinerja Pembangkit</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Capability Factor (%)</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Nett Operating Factor (%)</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">JSI</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="5">Pemakaian Bahan Bakar/Baku</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="2">Pemakaian Pelumas</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="2">Effisiensi</th>
                                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ket.</th>
                                                </tr>
                                                <tr class="bg-gray-100 text-xs">
                                                    <th class="border-r"></th>
                                                    <th class="border-r"></th>
                                                    <!-- Daya -->
                                                    <th class="subcol-border px-2">Terpasang</th>
                                                    <th class="subcol-border px-2">DMN</th>
                                                    <th class="px-2">Mampu</th>
                                                    <!-- Beban Puncak -->
                                                    <th class="subcol-border px-2">Siang</th>
                                                    <th class="px-2">Malam</th>
                                                    <!-- Ratio -->
                                                    <th class="px-2">Kit</th>
                                                    <!-- Produksi -->
                                                    <th class="subcol-border px-2">Bruto</th>
                                                    <th class="px-2">Netto</th>
                                                    <!-- Pemakaian Sendiri -->
                                                    <th class="subcol-border px-2">Aux (kWh)</th>
                                                    <th class="subcol-border px-2">Susut Trafo (kWh)</th>
                                                    <th class="px-2">Persentase (%)</th>
                                                    <!-- Jam Periode -->
                                                    <th class="px-2">Jam</th>
                                                    <!-- Jam Operasi -->
                                                    <th class="subcol-border px-2">OPR</th>
                                                    <th class="subcol-border px-2">STANDBY</th>
                                                    <th class="subcol-border px-2">PO</th>
                                                    <th class="subcol-border px-2">MO</th>
                                                    <th class="px-2">FO</th>
                                                    <!-- Trip Non OMC -->
                                                    <th class="subcol-border px-2">Mesin (kali)</th>
                                                    <th class="px-2">Listrik (kali)</th>
                                                    <!-- Derating -->
                                                    <th class="subcol-border px-2">EFDH</th>
                                                    <th class="subcol-border px-2">EPDH</th>
                                                    <th class="subcol-border px-2">EUDH</th>
                                                    <th class="px-2">ESDH</th>
                                                    <!-- Kinerja Pembangkit -->
                                                    <th class="subcol-border px-2">EAF (%)</th>
                                                    <th class="subcol-border px-2">SOF (%)</th>
                                                    <th class="subcol-border px-2">EFOR (%)</th>
                                                    <th class="px-2">SdOF (Kali)</th>
                                                    <!-- Capability Factor -->
                                                    <th class="px-2">NCF</th>
                                                    <!-- NOF -->
                                                    <th class="px-2">NOF</th>
                                                    <!-- JSI -->
                                                    <th class="px-2">Jam</th>
                                                    <!-- Pemakaian Bahan Bakar -->
                                                    <th class="subcol-border px-2">HSD (Liter)</th>
                                                    <th class="subcol-border px-2">B35 (Liter)</th>
                                                    <th class="subcol-border px-2">MFO (Liter)</th>
                                                    <th class="subcol-border px-2">Total BBM (Liter)</th>
                                                    <th class="px-2">Air (M³)</th>
                                                    <!-- Pemakaian Pelumas -->
                                                    <th class="subcol-border px-2">Liter</th>
                                                    <th class="px-2">Rupiah</th>
                                                    <!-- Effisiensi -->
                                                    <th class="subcol-border px-2">SFC</th>
                                                    <th class="px-2">Heat Rate</th>
                                                    <!-- Keterangan -->
                                                    <th class="px-2">Ket.</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @forelse($selectedUnit->machines as $machine)
                                                    @php
                                                        $machineData = $data->where('unit_id', $selectedUnit->id)
                                                                  ->where('machine_id', $machine->id)
                                                                  ->first();
                                                    @endphp
                                                    <tr>
                                                        <td class="px-4 py-3 border-r text-center">{{ $loop->iteration }}</td>
                                                        <td class="px-4 py-3 border-r">{{ $machine->name }}</td>
                                                        
                                                        <!-- Daya (MW) -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->installed_power, 3) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->dmn_power, 3) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->capable_power, 3) : '-' }}</td>
                                                        
                                                        <!-- Beban Puncak -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->peak_load_day, 3) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->peak_load_night, 3) : '-' }}</td>
                                                        
                                                        <!-- Ratio -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->power_ratio, 2) : '-' }}</td>
                                                        
                                                        <!-- Produksi -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->gross_production, 3) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->net_production, 3) : '-' }}</td>
                                                        
                                                        <!-- Pemakaian Sendiri -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->aux_kwh, 3) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->trafo_losses, 3) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->self_usage_percent, 2) : '-' }}</td>
                                                        
                                                        <!-- Jam Periode -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->period_hours, 1) : '-' }}</td>
                                                        
                                                        <!-- Jam Operasi -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->operating_hours, 1) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->standby_hours, 1) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->planned_outage, 1) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->maintenance_outage, 1) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->forced_outage, 1) : '-' }}</td>
                                                        
                                                        <!-- Trip Non OMC -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->machine_trips, 0) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->electrical_trips, 0) : '-' }}</td>
                                                        
                                                        <!-- Derating -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->efdh, 2) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->epdh, 2) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->eudh, 2) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->esdh, 2) : '-' }}</td>
                                                        
                                                        <!-- Kinerja Pembangkit -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->eaf, 2) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->sof, 2) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->efor, 2) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->sdof, 0) : '-' }}</td>
                                                        
                                                        <!-- Capability Factor -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->ncf, 2) : '-' }}</td>
                                                        
                                                        <!-- NOF -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->nof, 2) : '-' }}</td>
                                                        
                                                        <!-- JSI -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->jsi, 1) : '-' }}</td>
                                                        
                                                        <!-- Pemakaian Bahan Bakar -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->hsd_fuel, 2) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->b35_fuel, 2) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->mfo_fuel, 2) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->total_fuel, 2) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->water_usage, 2) : '-' }}</td>
                                                        
                                                        <!-- Pemakaian Pelumas -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->lubricant_liter, 2) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->lubricant_rupiah, 2) : '-' }}</td>
                                                        
                                                        <!-- Effisiensi -->
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->sfc, 3) : '-' }}</td>
                                                        <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->heat_rate, 2) : '-' }}</td>
                                                        
                                                        <!-- Keterangan -->
                                                        <td class="px-4 py-3 text-center">{{ $machineData ? $machineData->notes : '-' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="40" class="px-4 py-3 text-center text-sm text-gray-500">
                                                            Tidak ada mesin yang terdaftar
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    @foreach($units as $unit)
                        @if($unit->name !== 'UP KENDARI')
                            <div class="bg-white shadow rounded-lg p-6 mb-6">
                                <!-- Unit Header -->
                                <div class="mb-6">
                                    <h2 class="text-xl font-bold text-gray-800">{{ $unit->name }}</h2>
                                    <p class="text-sm text-gray-600">Data Operasional Harian</p>
                                </div>

                                <!-- Tabel Data -->
                                <div class="relative">
                                    <div class="overflow-x-auto overflow-y-visible shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                        <div class="min-w-full inline-block align-middle">
                                            <table class="min-w-full divide-y divide-gray-200 border table-fixed" style="min-width: 3800px;">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">No.</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Unit</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="3">Daya (MW)</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="2">Beban Puncak (kW)</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Ratio Daya Kit (%)</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="2">Produksi (kWh)</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="3">Pemakaian Sendiri</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Jam Periode</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="5">Jam Operasi</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="2">Trip Non OMC</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="4">Derating</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="4">Kinerja Pembangkit</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Capability Factor (%)</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Nett Operating Factor (%)</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">JSI</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="5">Pemakaian Bahan Bakar/Baku</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="2">Pemakaian Pelumas</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r" colspan="2">Effisiensi</th>
                                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ket.</th>
                                                    </tr>
                                                    <tr class="bg-gray-100 text-xs">
                                                        <th class="border-r"></th>
                                                        <th class="border-r"></th>
                                                        <!-- Daya -->
                                                        <th class="subcol-border px-2">Terpasang</th>
                                                        <th class="subcol-border px-2">DMN</th>
                                                        <th class="px-2">Mampu</th>
                                                        <!-- Beban Puncak -->
                                                        <th class="subcol-border px-2">Siang</th>
                                                        <th class="px-2">Malam</th>
                                                        <!-- Ratio -->
                                                        <th class="px-2">Kit</th>
                                                        <!-- Produksi -->
                                                        <th class="subcol-border px-2">Bruto</th>
                                                        <th class="px-2">Netto</th>
                                                        <!-- Pemakaian Sendiri -->
                                                        <th class="subcol-border px-2">Aux (kWh)</th>
                                                        <th class="subcol-border px-2">Susut Trafo (kWh)</th>
                                                        <th class="px-2">Persentase (%)</th>
                                                        <!-- Jam Periode -->
                                                        <th class="px-2">Jam</th>
                                                        <!-- Jam Operasi -->
                                                        <th class="subcol-border px-2">OPR</th>
                                                        <th class="subcol-border px-2">STANDBY</th>
                                                        <th class="subcol-border px-2">PO</th>
                                                        <th class="subcol-border px-2">MO</th>
                                                        <th class="px-2">FO</th>
                                                        <!-- Trip Non OMC -->
                                                        <th class="subcol-border px-2">Mesin (kali)</th>
                                                        <th class="px-2">Listrik (kali)</th>
                                                        <!-- Derating -->
                                                        <th class="subcol-border px-2">EFDH</th>
                                                        <th class="subcol-border px-2">EPDH</th>
                                                        <th class="subcol-border px-2">EUDH</th>
                                                        <th class="px-2">ESDH</th>
                                                        <!-- Kinerja Pembangkit -->
                                                        <th class="subcol-border px-2">EAF (%)</th>
                                                        <th class="subcol-border px-2">SOF (%)</th>
                                                        <th class="subcol-border px-2">EFOR (%)</th>
                                                        <th class="px-2">SdOF (Kali)</th>
                                                        <!-- Capability Factor -->
                                                        <th class="px-2">NCF</th>
                                                        <!-- NOF -->
                                                        <th class="px-2">NOF</th>
                                                        <!-- JSI -->
                                                        <th class="px-2">Jam</th>
                                                        <!-- Pemakaian Bahan Bakar -->
                                                        <th class="subcol-border px-2">HSD (Liter)</th>
                                                        <th class="subcol-border px-2">B35 (Liter)</th>
                                                        <th class="subcol-border px-2">MFO (Liter)</th>
                                                        <th class="subcol-border px-2">Total BBM (Liter)</th>
                                                        <th class="px-2">Air (M³)</th>
                                                        <!-- Pemakaian Pelumas -->
                                                        <th class="subcol-border px-2">Liter</th>
                                                        <th class="px-2">Rupiah</th>
                                                        <!-- Effisiensi -->
                                                        <th class="subcol-border px-2">SFC</th>
                                                        <th class="px-2">Heat Rate</th>
                                                        <!-- Keterangan -->
                                                        <th class="px-2">Ket.</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @forelse($unit->machines as $machine)
                                                        @php
                                                            $machineData = $data->where('unit_id', $unit->id)
                                                                      ->where('machine_id', $machine->id)
                                                                      ->first();
                                                        @endphp
                                                        <tr>
                                                            <td class="px-4 py-3 border-r text-center">{{ $loop->iteration }}</td>
                                                            <td class="px-4 py-3 border-r">{{ $machine->name }}</td>
                                                            
                                                            <!-- Daya (MW) -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->installed_power, 3) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->dmn_power, 3) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->capable_power, 3) : '-' }}</td>
                                                            
                                                            <!-- Beban Puncak -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->peak_load_day, 3) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->peak_load_night, 3) : '-' }}</td>
                                                            
                                                            <!-- Ratio -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->power_ratio, 2) : '-' }}</td>
                                                            
                                                            <!-- Produksi -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->gross_production, 3) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->net_production, 3) : '-' }}</td>
                                                            
                                                            <!-- Pemakaian Sendiri -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->aux_kwh, 3) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->trafo_losses, 3) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->self_usage_percent, 2) : '-' }}</td>
                                                            
                                                            <!-- Jam Periode -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->period_hours, 1) : '-' }}</td>
                                                            
                                                            <!-- Jam Operasi -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->operating_hours, 1) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->standby_hours, 1) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->planned_outage, 1) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->maintenance_outage, 1) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->forced_outage, 1) : '-' }}</td>
                                                            
                                                            <!-- Trip Non OMC -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->machine_trips, 0) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->electrical_trips, 0) : '-' }}</td>
                                                            
                                                            <!-- Derating -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->efdh, 2) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->epdh, 2) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->eudh, 2) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->esdh, 2) : '-' }}</td>
                                                            
                                                            <!-- Kinerja Pembangkit -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->eaf, 2) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->sof, 2) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->efor, 2) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->sdof, 0) : '-' }}</td>
                                                            
                                                            <!-- Capability Factor -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->ncf, 2) : '-' }}</td>
                                                            
                                                            <!-- NOF -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->nof, 2) : '-' }}</td>
                                                            
                                                            <!-- JSI -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->jsi, 1) : '-' }}</td>
                                                            
                                                            <!-- Pemakaian Bahan Bakar -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->hsd_fuel, 2) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->b35_fuel, 2) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->mfo_fuel, 2) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->total_fuel, 2) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->water_usage, 2) : '-' }}</td>
                                                            
                                                            <!-- Pemakaian Pelumas -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->lubricant_liter, 2) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->lubricant_rupiah, 2) : '-' }}</td>
                                                            
                                                            <!-- Effisiensi -->
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->sfc, 3) : '-' }}</td>
                                                            <td class="px-4 py-3 border-r text-center">{{ $machineData ? number_format($machineData->heat_rate, 2) : '-' }}</td>
                                                            
                                                            <!-- Keterangan -->
                                                            <td class="px-4 py-3 text-center">{{ $machineData ? $machineData->notes : '-' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="40" class="px-4 py-3 text-center text-sm text-gray-500">
                                                                Tidak ada mesin yang terdaftar
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $data->links() }}
                </div>
            </div>
        </main>
    </div>
</div>

<style>
    .table-cell-border {
        @apply border-r last:border-r-0;
    }
    
    .subcol-border {
        @apply border-r last:border-r-0;
    }
    
    /* ... style lainnya sama seperti di ikhtisar-harian.blade.php ... */
</style>
@endsection