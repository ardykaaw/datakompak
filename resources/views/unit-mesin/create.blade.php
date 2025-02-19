@extends('layouts.app')

@section('title', 'Tambah Unit Baru')

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
                    <h1 class="text-xl font-semibold text-gray-900">Tambah Unit Baru</h1>
                </div>
            </div>
        </header>

        <main class="p-4">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-sm">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Form Unit Baru</h2>
                                <p class="text-sm text-gray-500">Isi informasi detail unit pembangkit baru</p>
                            </div>
                            <a href="{{ route('unit-mesin.unit') }}" 
                               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-8">
                            <div class="overflow-hidden h-2 text-xs flex rounded bg-green-100">
                                <div class="animate-pulse shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500 w-1/3"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>Langkah 1 dari 3</span>
                                <span>Unit Baru</span>
                            </div>
                        </div>

                        <form action="{{ route('unit-mesin.store') }}" method="POST" class="space-y-8">
                            @csrf

                            <div class="grid grid-cols-1 gap-8">
                                <!-- Nama Unit -->
                                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center mb-4">
                                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                            <i class="fas fa-industry text-green-500"></i>
                                        </div>
                                        <div>
                                            <label for="name" class="block text-sm font-semibold text-gray-700">Nama Unit</label>
                                            <p class="text-xs text-gray-500">Masukkan nama unit pembangkit</p>
                                        </div>
                                    </div>
                                    <div class="mt-1">
                                        <input type="text" 
                                               name="name" 
                                               id="name" 
                                               placeholder="Contoh: Unit Pembangkit 1"
                                               class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border-gray-300 rounded-lg @error('name') border-red-500 @enderror" 
                                               value="{{ old('name') }}"
                                               required>
                                    </div>
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-500 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Deskripsi -->
                                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center mb-4">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                            <i class="fas fa-align-left text-blue-500"></i>
                                        </div>
                                        <div>
                                            <label for="description" class="block text-sm font-semibold text-gray-700">Deskripsi Unit</label>
                                            <p class="text-xs text-gray-500">Jelaskan detail dan informasi tambahan unit</p>
                                        </div>
                                    </div>
                                    <div class="mt-1">
                                        <textarea name="description" 
                                                  id="description" 
                                                  rows="4" 
                                                  placeholder="Masukkan deskripsi unit..."
                                                  class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-lg @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                    </div>
                                    @error('description')
                                        <p class="mt-2 text-sm text-red-500 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center mb-4">
                                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                            <i class="fas fa-toggle-on text-purple-500"></i>
                                        </div>
                                        <div>
                                            <label for="status" class="block text-sm font-semibold text-gray-700">Status Unit</label>
                                            <p class="text-xs text-gray-500">Pilih status operasional unit</p>
                                        </div>
                                    </div>
                                    <div class="mt-1">
                                        <select name="status" 
                                                id="status" 
                                                class="shadow-sm focus:ring-purple-500 focus:border-purple-500 block w-full sm:text-sm border-gray-300 rounded-lg">
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                                ✅ Aktif - Unit siap beroperasi
                                            </option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                                ⏸️ Tidak Aktif - Unit sedang tidak beroperasi
                                            </option>
                                        </select>
                                    </div>
                                    @error('status')
                                        <p class="mt-2 text-sm text-red-500 flex items-center">
                                            <i class="fas fa-exclamation-circle mr-2"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <div class="pt-6">
                                <div class="flex justify-end space-x-4">
                                    <button type="reset" 
                                            class="px-6 py-2.5 border-2 border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 flex items-center">
                                        <i class="fas fa-undo mr-2"></i>
                                        Reset Form
                                    </button>
                                    <button type="submit" 
                                            class="px-6 py-2.5 border-2 border-green-500 rounded-lg shadow-sm text-sm font-medium text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 flex items-center">
                                        <i class="fas fa-save mr-2"></i>
                                        Simpan Unit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection 