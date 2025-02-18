@extends('layouts.app')

@section('title', 'Kinerja Pembangkit')

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
                    <h1 class="text-xl font-semibold text-gray-900">Kinerja Pembangkit</h1>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-4">
            <div class="max-w-7xl mx-auto">
                <!-- Performance Indicators -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                    <!-- EAF Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                                <i class="fas fa-percentage text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">EAF</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('eaf'), 2) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- SOF Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 rounded-full bg-red-100 dark:bg-red-900">
                                <i class="fas fa-percentage text-red-600 dark:text-red-400"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">SOF</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('sof'), 2) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- EFOR Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                                <i class="fas fa-percentage text-yellow-600 dark:text-yellow-400"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">EFOR</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('efor'), 2) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- SdOF Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 rounded-full bg-indigo-100 dark:bg-indigo-900">
                                <i class="fas fa-percentage text-indigo-600 dark:text-indigo-400"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">SdOF</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('sdof'), 2) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- NCF Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 rounded-full bg-green-100 dark:bg-green-900">
                                <i class="fas fa-percentage text-green-600 dark:text-green-400"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">NCF</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('ncf'), 2) }}%
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Statistics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Operating Statistics -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">
                            <i class="fas fa-clock mr-2 text-gray-600 dark:text-gray-400"></i>
                            Statistik Operasi
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Jam Operasi</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('operating_hours'), 1) }} jam
                                </p>
                            </div>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Jam Standby</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('standby_hours'), 1) }} jam
                                </p>
                            </div>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Planned Outage</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('planned_outage'), 1) }} jam
                                </p>
                            </div>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Maintenance Outage</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('maintenance_outage'), 1) }} jam
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Production Statistics -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">
                            <i class="fas fa-bolt mr-2 text-gray-600 dark:text-gray-400"></i>
                            Statistik Produksi
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Produksi Bruto</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('gross_production'), 2) }} MW
                                </p>
                            </div>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Produksi Netto</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('net_production'), 2) }} MW
                                </p>
                            </div>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Beban Puncak</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('peak_load'), 2) }} MW
                                </p>
                            </div>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Beban Luar Puncak</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('off_peak_load'), 2) }} MW
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Metrics -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Fuel Usage -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">
                            <i class="fas fa-gas-pump mr-2 text-gray-600 dark:text-gray-400"></i>
                            Penggunaan Bahan Bakar
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">HSD</span>
                                <span class="font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('hsd_fuel'), 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">B35</span>
                                <span class="font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('b35_fuel'), 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">MFO</span>
                                <span class="font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('mfo_fuel'), 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total</span>
                                <span class="font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('total_fuel'), 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Oil Usage -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">
                            <i class="fas fa-oil-can mr-2 text-gray-600 dark:text-gray-400"></i>
                            Penggunaan Pelumas
                        </h3>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Meditran</span>
                                <span class="font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('meditran_oil'), 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Salyx 420</span>
                                <span class="font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('salyx_420'), 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Salyx 430</span>
                                <span class="font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('salyx_430'), 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total</span>
                                <span class="font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('total_oil'), 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Parameters -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">
                            <i class="fas fa-cogs mr-2 text-gray-600 dark:text-gray-400"></i>
                            Parameter Teknis
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">SFC/SCC</span>
                                <span class="font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('sfc_scc'), 3) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">NPHR</span>
                                <span class="font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('nphr'), 3) }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">SLC</span>
                                <span class="font-semibold text-gray-800 dark:text-white">
                                    {{ number_format($data->avg('slc'), 3) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white">
                            <i class="fas fa-chart-line mr-2 text-gray-600 dark:text-gray-400"></i>
                            Grafik Kinerja Pembangkit
                        </h3>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <canvas id="kinerjaChart" class="w-full" style="min-height: 400px;"></canvas>
                    </div>
                </div>

                <!-- Keterangan Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-800 dark:text-white">
                            <i class="fas fa-info-circle mr-2 text-gray-600 dark:text-gray-400"></i>
                            Keterangan Indikator
                        </h3>
                        <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <div class="p-4">
                        <dl class="grid grid-cols-1 gap-4">
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700 dark:text-gray-300">EAF (Equivalent Availability Factor)</dt>
                                <dd class="col-span-2 text-gray-600 dark:text-gray-400">Faktor kesiapan pembangkit untuk beroperasi.</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700 dark:text-gray-300">SOF (Scheduled Outage Factor)</dt>
                                <dd class="col-span-2 text-gray-600 dark:text-gray-400">Faktor pemeliharaan terjadwal pembangkit.</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700 dark:text-gray-300">EFOR (Equivalent Forced Outage Rate)</dt>
                                <dd class="col-span-2 text-gray-600 dark:text-gray-400">Tingkat gangguan paksa pembangkit.</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700 dark:text-gray-300">SdOF (Sudden Outage Factor)</dt>
                                <dd class="col-span-2 text-gray-600 dark:text-gray-400">Faktor gangguan mendadak pembangkit.</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700 dark:text-gray-300">NCF (Net Capacity Factor)</dt>
                                <dd class="col-span-2 text-gray-600 dark:text-gray-400">Faktor kapasitas bersih pembangkit.</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const data = @json($data);
    
    const ctx = document.getElementById('kinerjaChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map((_, index) => `Data ${index + 1}`),
            datasets: [
                {
                    label: 'EAF',
                    data: data.map(item => item.eaf),
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },
                {
                    label: 'SOF',
                    data: data.map(item => item.sof),
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                },
                {
                    label: 'EFOR',
                    data: data.map(item => item.efor),
                    borderColor: 'rgb(255, 205, 86)',
                    tension: 0.1
                },
                {
                    label: 'SdOF',
                    data: data.map(item => item.sdof),
                    borderColor: 'rgb(54, 162, 235)',
                    tension: 0.1
                },
                {
                    label: 'NCF',
                    data: data.map(item => item.ncf),
                    borderColor: 'rgb(153, 102, 255)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Grafik Kinerja Pembangkit'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush 