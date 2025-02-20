@extends('layouts.app')

@section('title', 'Input Laporan Kesiapan KIT')

@push('scripts')
<script>
// Define the function globally
window.refreshLastData = function() {
    const inputTime = document.getElementById('input_time').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    
    // Get base URL
    const baseUrl = window.location.origin + (window.location.pathname.includes('/public') ? '/public' : '');
    const apiUrl = `${baseUrl}/api/machines/last-data`;

    // Show loading indicator
    Swal.fire({
        title: 'Memuat Data...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Debug log
    console.log('Request URL:', apiUrl);
    console.log('Input time:', inputTime);
    console.log('CSRF Token:', csrfToken ? 'Present' : 'Missing');

    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            input_time: inputTime
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', [...response.headers.entries()]);
        
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP Error: ${response.status}\nResponse: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Received data:', data);

        if (data.status === 'error') {
            throw new Error(data.message || 'Unknown error occurred');
        }

        if (data.machines) {
            // Update machine data
            data.machines.forEach(log => {
                try {
                    // Update machine inputs
                    const capablePowerInput = document.querySelector(`input[name="data[${log.machine_id}][capable_power]"]`);
                    const supplyPowerInput = document.querySelector(`input[name="data[${log.machine_id}][supply_power]"]`);
                    const currentLoadInput = document.querySelector(`input[name="data[${log.machine_id}][current_load]"]`);
                    const statusSelect = document.querySelector(`select[name="data[${log.machine_id}][status]"]`);

                    if (capablePowerInput) capablePowerInput.value = log.capable_power || '';
                    if (supplyPowerInput) supplyPowerInput.value = log.supply_power || '';
                    if (currentLoadInput) currentLoadInput.value = log.current_load || '';
                    if (statusSelect) statusSelect.value = log.status || '';
                } catch (err) {
                    console.error('Error updating machine:', log.machine_id, err);
                    throw new Error(`Error updating machine ${log.machine_id}: ${err.message}`);
                }
            });
        }

        if (data.hops) {
            // Update HOP data
            data.hops.forEach(hop => {
                try {
                    const hopInput = document.querySelector(`input[name="hop[${hop.unit_id}]"]`);
                    if (hopInput) {
                        hopInput.value = hop.hop_value || '';
                    }
                } catch (err) {
                    console.error('Error updating HOP:', hop.unit_id, err);
                    throw new Error(`Error updating HOP ${hop.unit_id}: ${err.message}`);
                }
            });
        }

        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'Data berhasil diperbarui',
            showConfirmButton: false,
            timer: 1500
        });
    })
    .catch(error => {
        console.error('Error details:', error);
        
        // Show detailed error message
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            html: `
                <div class="text-left">
                    <p class="font-semibold">Detail Error:</p>
                    <p class="text-sm text-red-600 mt-2">${error.message}</p>
                    <p class="text-xs text-gray-500 mt-2">URL: ${apiUrl}</p>
                    <p class="text-xs text-gray-500">Time: ${new Date().toLocaleString()}</p>
                </div>
            `,
            confirmButtonText: 'Tutup',
            showCancelButton: true,
            cancelButtonText: 'Lihat Console',
            cancelButtonColor: '#718096'
        }).then((result) => {
            if (!result.isConfirmed) {
                console.log('Full error details:', error);
            }
        });
    });
};

// Add event listener for debugging
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded. Available endpoints:', {
        baseUrl: window.location.origin,
        fullPath: window.location.href,
        apiEndpoint: window.location.origin + (window.location.pathname.includes('/public') ? '/public' : '') + '/api/machines/last-data'
    });
});
</script>
@endpush

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
                <form action="{{ route('laporan-kesiapan-kit.store') }}" method="POST">
                    @csrf
                    
                    <!-- Time Selection -->
                    <div class="bg-white shadow rounded-lg mb-6 p-4">
                        <div class="flex items-center justify-between">
                            <div class="w-64">
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
                            
                            <div class="flex space-x-4">
                                <a href="{{ route('laporan-kesiapan-kit.index') }}"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0A749B]">
                                    <i class="fas fa-eye mr-2"></i>
                                    Lihat Hasil Inputan
                                </a>
                                <button type="button" 
                                        onclick="refreshLastData()"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0A749B]">
                                    <i class="fas fa-sync-alt mr-2"></i>
                                    Refresh Data Terakhir
                                </button>
                                
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#0A749B] hover:bg-[#086384] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0A749B]">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan & Sinkronkan Data
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Units and Machines Input -->
                    @foreach($units as $unit)
                    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900">{{ $unit->name }}</h3>
                                <div class="w-64">
                                    <label for="hop_{{ $unit->id }}" class="block text-sm font-medium text-gray-700">HOP (Hari)</label>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0"
                                           id="hop_{{ $unit->id }}"
                                           name="hop[{{ $unit->id }}]"
                                           value="{{ optional(\App\Models\UnitHop::getLatestHop($unit->id))->hop_value }}"
                                           class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('hop.'.$unit->id) border-red-500 @enderror"
                                           placeholder="Masukkan nilai HOP">
                                    @error('hop.'.$unit->id)
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
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
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($unit->machines as $index => $machine)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-300">
                                                {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border border-gray-300">
                                            <div class="text-sm font-medium text-gray-900">{{ $machine->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border border-gray-300">
                                            <input type="number" step="0.01" 
                                                   name="data[{{ $machine->id }}][capable_power]" 
                                                   value="{{ $machine->dmn }}"
                                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border border-gray-300">
                                            <input type="number" step="0.01" 
                                                   name="data[{{ $machine->id }}][supply_power]" 
                                                   value="{{ $machine->dmp }}"
                                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border border-gray-300">
                                            <input type="number" step="0.01" 
                                                   name="data[{{ $machine->id }}][current_load]" 
                                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border border-gray-300">
                                            <select name="data[{{ $machine->id }}][status]" 
                                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                                <option value="">Pilih Status</option>
                                                <option value="OPS">OPS - Operational</option>
                                                <option value="RSH">RSH - Reserve Shutdown</option>
                                                <option value="FO">FO - Forced Outage</option>
                                                <option value="MO">MO - Maintenance Outage</option>
                                                <option value="PO">PO - Planned Outage</option>
                                            </select>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach

                    <!-- Submit Button -->
                   
                </form>

                <!-- Tambahkan di bagian notifikasi -->
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
                @endif
            </div>
        </main>
    </div>
</div>
@endsection 

