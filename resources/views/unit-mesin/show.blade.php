@extends('layouts.app')

@section('title', $unit->name)

@section('content')
<div x-data="{ isSidebarOpen: true, isDarkMode: localStorage.getItem('darkMode') === 'true' }">
    @include('layouts.sidebar')
    
    <div class="flex-1" :class="{ 'ml-64': isSidebarOpen, 'ml-20': !isSidebarOpen }">
        <div class="container mx-auto px-4 py-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $unit->name }}</h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('unit-mesin.edit', $unit) }}" 
                               class="px-4 py-2 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 dark:text-blue-400 dark:bg-blue-900/50 dark:hover:bg-blue-900">
                                Edit Unit
                            </a>
                        </div>
                    </div>

                    @if($unit->description)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $unit->description }}</p>
                    </div>
                    @endif

                    <!-- Daftar Mesin -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-medium text-gray-800 dark:text-white">Daftar Mesin</h3>
                            <button onclick="Swal.fire({
                                title: 'Tambah Mesin Baru',
                                html: `
                                    <form id='addMachineForm'>
                                        <input type='text' id='machineName' class='swal2-input' placeholder='Nama Mesin'>
                                        <input type='text' id='machineCode' class='swal2-input' placeholder='Kode Mesin'>
                                        <textarea id='machineSpecs' class='swal2-textarea' placeholder='Spesifikasi'></textarea>
                                    </form>
                                `,
                                showCancelButton: true,
                                confirmButtonText: 'Simpan',
                                cancelButtonText: 'Batal',
                                preConfirm: () => {
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = '{{ route('unit-mesin.machines.store', $unit) }}';
                                    
                                    const csrf = document.createElement('input');
                                    csrf.type = 'hidden';
                                    csrf.name = '_token';
                                    csrf.value = '{{ csrf_token() }}';
                                    
                                    const name = document.createElement('input');
                                    name.type = 'hidden';
                                    name.name = 'name';
                                    name.value = document.getElementById('machineName').value;
                                    
                                    const code = document.createElement('input');
                                    code.type = 'hidden';
                                    code.name = 'code';
                                    code.value = document.getElementById('machineCode').value;
                                    
                                    const specs = document.createElement('input');
                                    specs.type = 'hidden';
                                    specs.name = 'specifications';
                                    specs.value = document.getElementById('machineSpecs').value;
                                    
                                    form.appendChild(csrf);
                                    form.appendChild(name);
                                    form.appendChild(code);
                                    form.appendChild(specs);
                                    
                                    document.body.appendChild(form);
                                    form.submit();
                                }
                            })" 
                            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                                Tambah Mesin
                            </button>
                        </div>

                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($unit->machines as $machine)
                            <div class="py-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-800 dark:text-white">{{ $machine->name }}</h4>
                                        @if($machine->code)
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Kode: {{ $machine->code }}</p>
                                        @endif
                                        @if($machine->specifications)
                                        <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $machine->specifications }}</p>
                                        @endif
                                    </div>
                                    <form action="{{ route('unit-mesin.machines.destroy', [$unit, $machine]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus mesin ini?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <div class="py-4 text-center text-gray-500 dark:text-gray-400">
                                Belum ada mesin yang ditambahkan.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection