@extends('layouts.app')

@section('title', 'Laporan Kesiapan KIT')

@push('scripts')
<script>
// Helper function for number formatting
function number_format(number, decimals = 2) {
    return parseFloat(number).toFixed(decimals);
}

// Tambahkan fungsi untuk mendapatkan base URL yang benar
function getBaseUrl() {
    return window.location.origin + (window.location.pathname.includes('/public') ? '/public' : '');
}

// Define functions in global scope
window.generateFormattedText = function() {
    const now = new Date();
    const formattedDate = now.toLocaleDateString('id-ID', { 
        day: 'numeric', 
        month: 'long', 
        year: 'numeric' 
    });
    
    // Get selected time from filter or current time
    const selectedTime = document.getElementById('input_time').value || 
                        `${now.getHours()}:${String(now.getMinutes()).padStart(2, '0')}`;
    
    let text = `Assalamu Alaikum Wr.Wb\n`;
    text += `Laporan Kesiapan Pembangkit PLN Nusantara Power\n`;
    text += `Unit Pembangkitan Kendari, ${formattedDate}\n`;
    text += `Pukul : ${selectedTime} Wita\n\n`;

    @foreach($units as $unit)
    text += `{!! $unit->name !!}\n`;
    text += `DMN : ${number_format({!! $unit->machines->sum('dmn') !!})} MW\n`;
    text += `DMP : ${number_format({!! $unit->machines->sum(function($machine) {
        return optional($machine->logs->first())->supply_power ?? 0;
    }) !!})} MW\n`;
    text += `Beban : ${number_format({!! $unit->machines->sum(function($machine) {
        return optional($machine->logs->first())->current_load ?? 0;
    }) !!})} MW\n`;
    text += `HOP : ${number_format({!! $unit->hop ?? 0 !!})} Hari (AMAN)\n`;

    @foreach($unit->machines as $machine)
    const machineLog_{!! $machine->id !!} = {!! json_encode($machine->logs->first()) !!};
    if (machineLog_{!! $machine->id !!}) {
        text += `- {!! $machine->name !!} :${number_format(machineLog_{!! $machine->id !!}.capable_power)} MW/${number_format(machineLog_{!! $machine->id !!}.supply_power)} MW/ ${machineLog_{!! $machine->id !!}.status}`;
        if (machineLog_{!! $machine->id !!}.current_load > 0) {
            text += ` ${number_format(machineLog_{!! $machine->id !!}.current_load)} MW`;
        }
        text += `\n`;
    }
    @endforeach
    text += `\n`;
    @endforeach

    text += `\nBarakallahu Fikhum dan Terima Kasih`;
    
    return text;
};

window.copyFormattedText = function() {
    try {
        const formattedText = window.generateFormattedText();
        
        // Create temporary textarea
        const textarea = document.createElement('textarea');
        textarea.value = formattedText;
        document.body.appendChild(textarea);
        
        // Copy text
        textarea.select();
        document.execCommand('copy');
        
        // Remove temporary textarea
        document.body.removeChild(textarea);
        
        // Show success message
        Swal.fire({
            icon: 'success',
            title: 'Berhasil disalin!',
            showConfirmButton: false,
            timer: 1500
        });
    } catch (error) {
        console.error('Error copying text:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan saat menyalin teks'
        });
    }
};

// Modifikasi fungsi fetch data
window.refreshLastData = function() {
    const inputTime = document.getElementById('input_time').value;
    const inputDate = document.getElementById('input_date').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Show loading indicator
    Swal.fire({
        title: 'Memuat Data...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Debug log
    console.log('Fetching data with input time:', inputTime, 'and date:', inputDate);

    const apiUrl = `${getBaseUrl()}/api/machines/last-data`;
    
    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            input_time: inputTime,
            input_date: inputDate
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            // Update data berhasil
            updateTableData(data);
            Swal.close();
        } else {
            throw new Error(data.message || 'Terjadi kesalahan pada server');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan saat mengambil data: ' + error.message,
            confirmButtonText: 'Tutup'
        });
    });
};

// Add event listener when document is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script loaded');
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
        <!-- Simplified Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-14">
                    <div class="flex items-center">
                        <button @click="isSidebarOpen = !isSidebarOpen" 
                                class="text-gray-500 hover:text-gray-600 focus:outline-none mr-4">
                            <i class="fas fa-grip-lines text-xl"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-900">Laporan Kesiapan KIT</h1>
                    </div>
                    <span class="text-sm text-gray-500">{{ now()->format('d M Y') }}</span>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-4">
            <!-- Filter and Actions Section -->
            <div class="max-w-7xl mx-auto mb-6 bg-white rounded-lg shadow-sm p-4">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                    <!-- Left side: Filters -->
                    <form action="{{ route('laporan-kesiapan-kit.index') }}" method="GET" 
                          class="flex flex-wrap items-center gap-3">
                        <div class="w-40">
                            <label for="input_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" 
                                   name="input_date" 
                                   id="input_date"
                                   value="{{ request('input_date', now()->format('Y-m-d')) }}"
                                   onchange="this.form.submit()"
                                   class="block w-full px-2 py-1.5 text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="w-44">
                            <label for="input_time" class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                            <select name="input_time" 
                                    id="input_time" 
                                    onchange="this.form.submit()"
                                    class="block w-full px-2 py-1.5 text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Waktu</option>
                                @foreach($availableTimes as $time => $label)
                                    <option value="{{ $time }}" {{ request('input_time') == $time ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    <!-- Right side: Action Buttons -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('laporan-kesiapan-kit.create') }}" 
                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-1.5"></i>
                            Input
                        </a>
                        <button type="button"
                                onclick="window.copyFormattedText()"
                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-copy mr-1.5"></i>
                            Copy
                        </button>
                        <a href="{{ route('laporan-kesiapan-kit.pdf') }}" 
                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                           target="_blank">
                            <i class="fas fa-file-pdf mr-1.5"></i>
                            PDF
                        </a>
                    </div>
                </div>
            </div>

            <!-- Update the debug info to show both date and time -->
            @if(request('input_time') || request('input_date'))
                <div class="bg-blue-100 p-4 mb-4">
                    <p class="text-sm">
                        Filtering for date: {{ request('input_date', now()->format('Y-m-d')) }}
                        @if(request('input_time'))
                            , time: {{ request('input_time') }}
                        @endif
                    </p>
                </div>
            @endif

            <!-- Content Cards -->
            <div class="max-w-7xl mx-auto space-y-6">
                @foreach($units as $unit)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <!-- Unit Header -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-900">{{ $unit->name }}</h2>
                            <div class="w-64">
                                <div class="text-sm text-gray-600">
                                    <span class="font-medium">{{ str_contains($unit->name, 'PLTM ') ? 'Inflow' : 'HOP' }}:</span>
                                    <span class="ml-2">{{ number_format($unit->hop ?? 0, 2) }} {{ str_contains($unit->name, 'PLTM ') ? 'm³/s' : 'Hari' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unit's Machines Table -->
                    <div class="p-6">
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border border-gray-200">
                                            <div class="text-sm font-medium text-gray-900">{{ $machine->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                                            {{ $machine->logs->first() ? number_format($machine->logs->first()->capable_power ?? 0, 2) : '0.00' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                                            {{ $machine->logs->first() ? number_format($machine->logs->first()->supply_power ?? 0, 2) : '0.00' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                                            {{ $machine->logs->first() ? number_format($machine->logs->first()->current_load ?? 0, 2) : '0.00' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border border-gray-200">
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
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