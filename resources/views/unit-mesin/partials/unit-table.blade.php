@forelse($units as $index => $unit)
    <tr class="hover:bg-gray-50 transition-colors duration-200 bg-white">
        <td class="px-6 py-4 whitespace-nowrap bg-white text-sm text-gray-500">
            {{ ($units->currentPage() - 1) * $units->perPage() + $index + 1 }}
        </td>
        <td class="px-6 py-4 whitespace-nowrap bg-white">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-industry text-blue-500"></i>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">
                        <a href="{{ route('unit-mesin.show', $unit) }}" class="hover:text-blue-500">
                            {{ $unit->name }}
                        </a>
                    </div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 bg-white">
            <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $unit->description }}">
                {{ $unit->description ?: '-' }}
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap bg-white">
            <span class="px-3 py-1 inline-flex text-sm leading-5 font-medium bg-blue-50 text-blue-700 rounded-full">
                {{ $unit->machines->count() }} Mesin
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap bg-white">
            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                <i class="fas fa-circle text-xs mr-1 mt-1"></i> Aktif
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium bg-white">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('unit-mesin.edit', $unit) }}" 
                   class="text-blue-600 hover:text-blue-900 bg-blue-50 p-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-edit"></i>
                </a>
                <button onclick="confirmDelete({{ $unit->id }}, '{{ $unit->name }}')" 
                        class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-lg transition-colors duration-200">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-6 py-8 text-center">
            <div class="max-w-sm mx-auto">
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="text-center">
                        <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900 mb-1">Tidak ada hasil</p>
                        <p class="text-sm text-gray-500">Coba kata kunci lain</p>
                    </div>
                </div>
            </div>
        </td>
    </tr>
@endforelse 