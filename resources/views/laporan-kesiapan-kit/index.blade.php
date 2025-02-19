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
                <div class="flex items-center space-x-3">
                    <!-- Export Buttons -->
                    <div class="flex space-x-2">
                        <a href="{{ route('laporan-kesiapan-kit.export-pdf') }}" 
                           class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                            <i class="fas fa-file-pdf mr-2"></i>
                            PDF
                        </a>
                        
                        <button type="button" onclick="window.exportAsExcel()" 
                                class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                            <i class="fas fa-file-excel mr-2"></i>
                            Excel
                        </button>
                        
                        <button type="button" id="copyTextBtn"
                                class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-copy mr-2"></i>
                            Copy Text
                        </button>
                    </div>

                    <a href="{{ route('laporan-kesiapan-kit.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#0A749B] hover:bg-[#086384]">
                        <i class="fas fa-plus mr-2"></i>
                        Input Kesiapan
                    </a>
                    <span class="text-sm text-gray-500">{{ now()->format('d M Y') }}</span>
                </div>
            </div>
        </header>

        <!-- Tambahkan filter di bawah header, sebelum content utama -->
        <div class="  p-4 mb-4">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center justify-end">
                    <div class="w-64">
                        <label for="filter_time" class="block text-sm font-medium text-gray-700 mb-1">Filter Waktu Input</label>
                        <select id="filter_time" 
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="">Semua Waktu</option>
                            <option value="06:00">06:00 (Pagi)</option>
                            <option value="11:00">11:00 (Siang)</option>
                            <option value="14:00">14:00 (Siang)</option>
                            <option value="18:00">18:00 (Malam)</option>
                            <option value="19:00">19:00 (Malam)</option>
                        </select>
                    </div>
                    <div class="text-sm text-gray-500">
                        <span id="current_time_display"></span>
                    </div>
                </div>
            </div>
        </div>

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
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">No</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Unit</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Mesin</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Daya Mampu SILM (MW)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Daya Mampu Pasok (MW)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Beban (MW)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Terakhir Update</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($unit->machines as $index => $machine)
                                    <tr data-machine-id="{{ $machine->id }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                            <div class="text-sm font-medium text-gray-900 border-r border-gray-200">{{ $machine->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capable-power border-r border-gray-200">
                                            {{ $machine->logs->first() ? number_format($machine->logs->first()->capable_power ?? 0, 2) : '0.00' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 supply-power border-r border-gray-200">
                                            {{ $machine->logs->first() ? number_format($machine->logs->first()->supply_power ?? 0, 2) : '0.00' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 current-load border-r border-gray-200">
                                            {{ $machine->logs->first() ? number_format($machine->logs->first()->current_load ?? 0, 2) : '0.00' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap status border-r border-gray-200">
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 last-update border-r border-gray-200">
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

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kesiapan Pembangkit</title>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyButton = document.getElementById('copyTextBtn');
            
            if (copyButton) {
                copyButton.addEventListener('click', function() {
                    copyToClipboard();
                });
            }

            async function copyToClipboard() {
                try {
                    const content = generateFormattedContent();
                    
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(content);
                        showNotification('Text berhasil disalin!', 'success');
                    } else {
                        const textArea = document.createElement('textarea');
                        textArea.value = content;
                        document.body.appendChild(textArea);
                        
                        try {
                            document.execCommand('copy');
                            showNotification('Text berhasil disalin!', 'success');
                        } catch (err) {
                            showNotification('Gagal menyalin text', 'error');
                        } finally {
                            document.body.removeChild(textArea);
                        }
                    }
                } catch (err) {
                    console.error('Error copying text:', err);
                    showNotification('Gagal menyalin text', 'error');
                }
            }

            function showNotification(message, type = 'success') {
                const notification = document.createElement('div');
                notification.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white ${
                    type === 'success' ? 'bg-green-500' : 'bg-red-500'
                }`;
                notification.style.zIndex = '9999';
                notification.textContent = message;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }

            function generateFormattedContent() {
                let content = 'Assalamu Alaikum Wr.Wb\n\n';
                content += 'Laporan Kesiapan Pembangkit PLN Nusantara Power\n';
                content += `Unit Pembangkitan Kendari, ${new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}\n`;
                content += `Pukul: ${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })} WITA\n\n`;

                let totalMachines = 0;
                let inputtedMachines = 0;
                @foreach($units as $unit)
                    totalMachines += {{ $unit->machines->count() }};
                    inputtedMachines += {{ $unit->machines->filter(function($machine) {
                        return $machine->logs->isNotEmpty();
                    })->count() }};
                @endforeach
                const persentaseInput = ((inputtedMachines / totalMachines) * 100).toFixed(1);
                content += `Persentase Input Navitas: ${persentaseInput}%\n\n`;

                @foreach($units as $unit)
                    content += `${$unit->name}\n`;
                    const dmn = {{ $unit->machines->sum('dmn') ?? 0 }};
                    const dmp = {{ $unit->machines->sum('dmp') ?? 0 }};
                    let totalBeban = 0;

                    @foreach($unit->machines as $machine)
                        @if($machine->logs->first())
                            totalBeban += {{ $machine->logs->first()->current_load ?? 0 }};
                        @endif
                    @endforeach

                    content += `DMN: ${dmn.toFixed(2)} MW\n`;
                    content += `DMP: ${dmp.toFixed(2)} MW\n`;
                    content += `Beban: ${totalBeban.toFixed(2)} MW\n`;

                    @if(isset($unit->hop))
                        content += `HOP: ${{{ $unit->hop }}} Hari(AMAN)\n`;
                    @endif

                    @if(str_contains(strtoupper($unit->name), 'PLTM'))
                        @if(isset($unit->tma))
                            content += `TMA: ${{{ $unit->tma }}} M\n`;
                        @endif
                        content += `Free Gov: ON\n`;
                    @endif

                    @foreach($unit->machines as $machine)
                        @if($machine->logs->first())
                            const log = {!! json_encode($machine->logs->first()) !!};
                            const capablePower = log.capable_power ? parseFloat(log.capable_power).toFixed(2) : '0.00';
                            const supplyPower = log.supply_power ? parseFloat(log.supply_power).toFixed(2) : '0.00';
                            const currentLoad = log.current_load ? ` ${parseFloat(log.current_load).toFixed(2)} MW` : '';
                            const status = log.status || 'N/A';
                            
                            content += `- ${$machine->name}: ${capablePower}MW/${supplyPower}MW/ ${status}`;
                            if (status === 'OPS' && currentLoad) {
                                content += `${currentLoad}`;
                            }
                            content += '\n';
                        @else
                            content += `- ${$machine->name}: N/A\n`;
                        @endif
                    @endforeach
                    content += '\n\n';
                @endforeach

                content += 'Barakallahu Fikhum dan Terima Kasih';
                return content;
            }

            const filterTime = document.getElementById('filter_time');
            const currentTimeDisplay = document.getElementById('current_time_display');
            
            // Update tampilan waktu terpilih
            function updateTimeDisplay(selectedTime) {
                if (selectedTime) {
                    currentTimeDisplay.textContent = `Menampilkan data untuk waktu input: ${selectedTime}`;
                } else {
                    currentTimeDisplay.textContent = 'Menampilkan semua data';
                }
            }

            // Event listener untuk perubahan filter
            filterTime.addEventListener('change', function() {
                const selectedTime = this.value;
                updateTimeDisplay(selectedTime);
                
                // Reload halaman dengan parameter filter
                window.location.href = `${window.location.pathname}?input_time=${selectedTime}`;
            });

            // Set nilai awal filter berdasarkan URL
            const urlParams = new URLSearchParams(window.location.search);
            const initialTime = urlParams.get('input_time');
            if (initialTime) {
                filterTime.value = initialTime;
                updateTimeDisplay(initialTime);
            }
        });
    </script>
    <style>
        .copy-btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        .copy-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

    <button id="copyTextBtn" class="copy-btn">Copy Text</button>

</body>
</html>

@endpush
@endsection
