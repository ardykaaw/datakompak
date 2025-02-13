@extends('layouts.app')

@section('title', 'Unit dan Mesin')

@section('content')
<div x-data="{ isSidebarOpen: true, isDarkMode: localStorage.getItem('darkMode') === 'true' }">
    @include('layouts.sidebar')
    
    <div class="flex-1" :class="{ 'ml-64': isSidebarOpen, 'ml-20': !isSidebarOpen }">
        <div class="container mx-auto px-4 py-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Unit dan Mesin</h1>
                <a href="{{ route('unit-mesin.create') }}" 
                   class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg">
                    Tambah Unit
                </a>
            </div>

            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($units as $unit)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    <a href="{{ route('unit-mesin.show', $unit) }}" class="hover:text-primary-600">
                                        {{ $unit->name }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Jumlah Mesin: {{ $unit->machines->count() }}
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('unit-mesin.edit', $unit) }}" 
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('unit-mesin.destroy', $unit) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus unit ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                        Belum ada unit yang ditambahkan.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection