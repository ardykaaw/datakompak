@extends('layouts.app')

@section('title', 'Ikhtisar Harian')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <h1 class="text-xl font-semibold text-gray-900">Ikhtisar Harian</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ now()->format('d M Y') }}</span>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh Data
                    </button>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                        <!-- Total Production -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-bolt text-2xl text-yellow-500"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Total Produksi</dt>
                                            <dd class="text-lg font-semibold text-gray-900">2,400 MW</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Average Efficiency -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-tachometer-alt text-2xl text-green-500"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Efisiensi Rata-rata</dt>
                                            <dd class="text-lg font-semibold text-gray-900">92.8%</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Operating Hours -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-clock text-2xl text-blue-500"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Jam Operasi</dt>
                                            <dd class="text-lg font-semibold text-gray-900">18.5 Jam</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Active Units -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-power-off text-2xl text-purple-500"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Unit Aktif</dt>
                                            <dd class="text-lg font-semibold text-gray-900">3 / 4</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Details and Input Form -->
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                        <!-- Unit Input Form -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Input Data Unit</h3>
                                <form action="{{ route('ikhtisar-harian.store') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                                        <select id="unit" name="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="Unit 1">Unit 1</option>
                                            <option value="Unit 2">Unit 2</option>
                                            <option value="Unit 3">Unit 3</option>
                                            <option value="Unit 4">Unit 4</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="value" class="block text-sm font-medium text-gray-700">Produksi (MW)</label>
                                        <input type="number" step="0.01" id="value" name="value" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label for="efficiency" class="block text-sm font-medium text-gray-700">Efisiensi (%)</label>
                                        <input type="number" step="0.01" id="efficiency" name="efficiency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label for="operating_hours" class="block text-sm font-medium text-gray-700">Jam Operasi</label>
                                        <input type="number" step="0.5" id="operating_hours" name="operating_hours" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                                        <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                            <i class="fas fa-save mr-2"></i>
                                            Simpan Data
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Recent Updates -->
                        <div class="bg-white shadow rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Terkini</h3>
                                <div class="space-y-4">
                                    @forelse($recentData as $data)
                                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-chart-line text-blue-500"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $data->unit }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ number_format($data->value, 2) }} MW | Efisiensi: {{ $data->efficiency }}%
                                            </p>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $data->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center text-gray-500">
                                        Belum ada data untuk hari ini
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Unit Status Table -->
                    <div class="mt-6 bg-white shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Status Unit</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produksi (MW)</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Efisiensi</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Operasi</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($todayData as $data)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $data->unit }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $data->status === 'operational' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $data->status === 'operational' ? 'Operasional' : 'Maintenance' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($data->value, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $data->efficiency }}%</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $data->operating_hours }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $data->notes ?? '-' }}</td>
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