@extends('layouts.app')

@section('title', 'Daftar Mesin')

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
                    <h1 class="text-xl font-semibold text-gray-900">Daftar Mesin</h1>
                </div>
            </div>
        </header>

        <main class="p-4">
            <div class="max-w-7xl mx-auto">
                <!-- Stats Card -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-600 rounded-lg">
                                <i class="fas fa-cogs text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Total Mesin</h3>
                                <p class="text-3xl font-bold">
                                    @php
                                        $totalMachines = 0;
                                        foreach($units as $unit) {
                                            $totalMachines += $unit->machines->count();
                                        }
                                    @endphp
                                    {{ $totalMachines }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-600 rounded-lg">
                                <i class="fas fa-industry text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Total Unit</h3>
                                <p class="text-3xl font-bold">{{ $units->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white">
                        <div class="flex items-center">
                            <div class="p-3 bg-purple-600 rounded-lg">
                                <i class="fas fa-chart-line text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold">Rata-rata</h3>
                                <p class="text-3xl font-bold">
                                    {{ $units->count() > 0 ? number_format($totalMachines / $units->count(), 1) : '0' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="bg-white rounded-xl shadow-sm">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Daftar Mesin</h2>
                                <p class="text-sm text-gray-500">Kelola data mesin di setiap unit</p>
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
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Mesin
                            </button>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Mesin</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Spesifikasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php $totalMachines = 0; @endphp
                                    @foreach($units as $unit)
                                        @foreach($unit->machines as $machine)
                                            @php $totalMachines++; @endphp
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $machine->name }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $unit->name }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $machine->code ?: '-' }}</div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="text-sm text-gray-900">{{ $machine->specifications ?: '-' }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Aktif
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex justify-end space-x-2">
                                                        <button class="text-blue-600 hover:text-blue-900">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('unit-mesin.machines.destroy', [$unit, $machine]) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="text-red-600 hover:text-red-900"
                                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus mesin ini?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>

                            @if($totalMachines === 0)
                                <div class="text-center py-8">
                                    <div class="text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-4"></i>
                                        <p class="text-lg">Belum ada mesin yang ditambahkan</p>
                                        <p class="text-sm">Klik tombol "Tambah Mesin" untuk menambahkan mesin baru</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection