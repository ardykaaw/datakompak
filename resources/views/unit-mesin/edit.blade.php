@extends('layouts.app')

@section('title', 'Edit Unit')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-lg mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit Unit</h2>
            
            <form action="{{ route('unit-mesin.update', $unit) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nama Unit
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ $unit->name }}"
                           class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                           required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Deskripsi
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="3" 
                              class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ $unit->description }}</textarea>
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('unit-mesin.index') }}" 
                       class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection