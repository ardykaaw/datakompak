@extends('layouts.app')

@section('title', 'Edit Mesin')

@section('content')
<div class="min-h-screen bg-gray-100"
     x-data="{ 
        isSidebarOpen: localStorage.getItem('sidebarOpen') !== 'false',
        isDarkMode: localStorage.getItem('darkMode') === 'true',
        init() {
            document.documentElement.classList.toggle('dark', this.isDarkMode)
            this.$watch('isSidebarOpen', value => localStorage.setItem('sidebarOpen', value))
            this.$watch('isDarkMode', value => {
                localStorage.setItem('darkMode', value)
                document.documentElement.classList.toggle('dark', value)
            })
        }
     }">
    
    @include('layouts.sidebar')

    <div class="transition-all duration-300 ease-in-out"
         :class="{
             'lg:pl-64': isSidebarOpen,
             'lg:pl-20': !isSidebarOpen
         }">
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <button @click="isSidebarOpen = !isSidebarOpen" 
                            class="text-gray-500 hover:text-gray-600 focus:outline-none mr-4">
                        <i class="fas fa-grip-lines text-xl"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-900">Edit Mesin</h1>
                </div>
            </div>
        </header>

        <main class="p-4">
           

            <div class="max-w-4xl mx-auto">
                <form action="{{ route('unit-mesin.machines.update', [$unit, $machine]) }}" method="POST" class="bg-white rounded-lg shadow-sm">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 gap-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Nama Mesin <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ $machine->name }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                            </div>

                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700">
                                    No. Seri <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="code" 
                                       id="code" 
                                       value="{{ $machine->code }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                            </div>

                            <div>
                                <label for="unit_id" class="block text-sm font-medium text-gray-700">
                                    Unit <span class="text-red-500">*</span>
                                </label>
                                <select name="unit_id" 
                                        id="unit_id" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        required>
                                    @foreach($units as $unitOption)
                                        <option value="{{ $unitOption->id }}" {{ $unit->id == $unitOption->id ? 'selected' : '' }}>
                                            {{ $unitOption->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="dmn" class="block text-sm font-medium text-gray-700">
                                        DMN <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           name="dmn" 
                                           id="dmn" 
                                           value="{{ $machine->dmn }}"
                                           step="0.1"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           required>
                                </div>
                                <div>
                                    <label for="dmp" class="block text-sm font-medium text-gray-700">
                                        DMP <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           name="dmp" 
                                           id="dmp" 
                                           value="{{ $machine->dmp }}"
                                           step="0.1"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           required>
                                </div>
                            </div>

                            <div>
                                <label for="specifications" class="block text-sm font-medium text-gray-700">
                                    Spesifikasi
                                </label>
                                <textarea name="specifications" 
                                          id="specifications" 
                                          rows="3" 
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $machine->specifications }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-end space-x-3">
                        <a href="{{ route('unit-mesin.mesin') }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection 