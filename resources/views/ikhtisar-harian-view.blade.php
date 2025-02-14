@extends('layouts.app')

@section('content')
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
                <a href="{{ route('ikhtisar-harian.index') }}" 
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
                        <select name="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-search mr-2"></i>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Data -->
            <div class="overflow-x-auto shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <!-- Gunakan struktur tabel yang sama seperti form input -->
                <table class="min-w-full divide-y divide-gray-200 border table-fixed" style="min-width: 2200px;">
                    <!-- ... header dan struktur tabel sama ... -->
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($data as $item)
                            <tr>
                                <!-- Sesuaikan dengan struktur data -->
                                <td>{{ $item->machine->name }}</td>
                                <!-- ... kolom-kolom lainnya ... -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $data->links() }}
            </div>
        </div>
    </main>
</div>
@endsection 