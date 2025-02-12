@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top header -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
                <button type="button" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </header>

        <!-- Main content area -->
        <main class="flex-1 overflow-y-auto">
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- Total Units Card -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-industry text-2xl text-blue-500"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Total Units</dt>
                                            <dd class="text-3xl font-semibold text-gray-900">4</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Active Units Card -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-power-off text-2xl text-green-500"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Active Units</dt>
                                            <dd class="text-3xl font-semibold text-gray-900">3</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Output Card -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-bolt text-2xl text-yellow-500"></i>
                                    </div>
                                    <div class="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt class="text-sm font-medium text-gray-500 truncate">Total Output</dt>
                                            <dd class="text-3xl font-semibold text-gray-900">2.4 GW</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Input and Recent Data -->
                    <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <!-- Data Input Card -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    <i class="fas fa-edit mr-2 text-blue-500"></i>
                                    Input Data
                                </h3>
                                <form action="{{ route('data.store') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                                        <select id="unit" name="unit" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                            <option value="Unit 1">Unit 1</option>
                                            <option value="Unit 2">Unit 2</option>
                                            <option value="Unit 3">Unit 3</option>
                                            <option value="Unit 4">Unit 4</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="value" class="block text-sm font-medium text-gray-700">Value (MW)</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <input type="number" name="value" id="value" step="0.01"
                                                class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-3 pr-12 sm:text-sm border-gray-300 rounded-md"
                                                placeholder="0.00">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">MW</span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-save mr-2"></i>
                                        Save Data
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Recent Data Card -->
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    <i class="fas fa-history mr-2 text-blue-500"></i>
                                    Recent Data
                                </h3>
                                <div class="flow-root">
                                    <ul class="-my-5 divide-y divide-gray-200">
                                        @forelse($recentData ?? [] as $data)
                                        <li class="py-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-chart-bar text-blue-400"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $data->unit }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ number_format($data->value, 2) }} MW
                                                    </p>
                                                </div>
                                                <div>
                                                    <span class="text-sm text-gray-500">
                                                        {{ $data->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                        @empty
                                        <li class="py-4">
                                            <div class="flex justify-center text-gray-500">
                                                <i class="fas fa-info-circle mr-2"></i>
                                                No data available
                                            </div>
                                        </li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection 