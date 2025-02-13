@extends('layouts.app')

@section('title', 'Unit dan Mesin')

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
                    <h1 class="text-xl font-semibold text-gray-900">Unit dan Mesin</h1>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="p-4">
            <div class="max-w-7xl mx-auto">
                <!-- Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Total Mesin Card -->
                    <div class="bg-white rounded-xl shadow-sm">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-800">Total Mesin</h2>
                                    <p class="text-sm text-gray-500">Daftar seluruh mesin</p>
                                </div>
                                <button onclick="Swal.fire({
                                    title: 'Tambah Mesin Baru',
                                    html: `
                                        <form id='addMachineForm' class='space-y-4'>
                                            <select id='unitSelect' class='w-full px-3 py-2 border rounded-lg'>
                                                <option value=''>Pilih Unit</option>
                                                @foreach($units as $unit)
                                                    <option value='{{ $unit->id }}'>{{ $unit->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type='text' id='machineName' class='w-full px-3 py-2 border rounded-lg' placeholder='Nama Mesin'>
                                            <input type='text' id='machineCode' class='w-full px-3 py-2 border rounded-lg' placeholder='Kode Mesin'>
                                            <textarea id='machineSpecs' class='w-full px-3 py-2 border rounded-lg' placeholder='Spesifikasi'></textarea>
                                        </form>
                                    `,
                                    showCancelButton: true,
                                    confirmButtonText: 'Simpan',
                                    cancelButtonText: 'Batal',
                                    confirmButtonColor: '#3B82F6',
                                    preConfirm: () => {
                                        const unitId = document.getElementById('unitSelect').value;
                                        if (!unitId) {
                                            Swal.showValidationMessage('Silakan pilih unit');
                                            return false;
                                        }
                                        
                                        const form = document.createElement('form');
                                        form.method = 'POST';
                                        form.action = `/unit-mesin/${unitId}/machines`;
                                        
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
                                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                    + Tambah Mesin
                                </button>
                            </div>
                            
                            <div class="divide-y divide-gray-200">
                                @php $totalMachines = 0; @endphp
                                @foreach($units as $unit)
                                    @foreach($unit->machines as $machine)
                                        @php $totalMachines++; @endphp
                                        <div class="py-3">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="text-base font-medium text-gray-800">{{ $machine->name }}</h4>
                                                    <p class="text-sm text-gray-500">Unit: {{ $unit->name }}</p>
                                                    @if($machine->code)
                                                        <p class="text-sm text-gray-500">Kode: {{ $machine->code }}</p>
                                                    @endif
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button class="text-gray-400 hover:text-blue-500">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <form action="{{ route('unit-mesin.machines.destroy', [$unit, $machine]) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-gray-400 hover:text-red-500"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus mesin ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                                @if($totalMachines === 0)
                                    <div class="py-3 text-center text-gray-500">
                                        Belum ada mesin yang ditambahkan.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Unit Card -->
                    <div class="bg-white rounded-xl shadow-sm">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-800">Unit</h2>
                                    <p class="text-sm text-gray-500">Daftar unit pembangkit</p>
                                </div>
                                <a href="{{ route('unit-mesin.create') }}" 
                                   class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                    + Tambah Unit
                                </a>
                            </div>
                            
                            <div class="divide-y divide-gray-200">
                                @forelse($units as $unit)
                                <div class="py-3">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="text-base font-medium text-gray-800">
                                                <a href="{{ route('unit-mesin.show', $unit) }}" class="hover:text-blue-500">
                                                    {{ $unit->name }}
                                                </a>
                                            </h3>
                                            <p class="text-sm text-gray-500">
                                                Jumlah Mesin: {{ $unit->machines->count() }}
                                            </p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('unit-mesin.edit', $unit) }}" 
                                               class="text-gray-400 hover:text-blue-500">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('unit-mesin.destroy', $unit) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-gray-400 hover:text-red-500"
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus unit ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="py-3 text-center text-gray-500">
                                    Belum ada unit yang ditambahkan.
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection