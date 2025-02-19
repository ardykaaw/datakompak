@csrf
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="space-y-3">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-300 mb-1">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                    <i class="fas fa-user text-gray-500 text-sm"></i>
                </div>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="block w-full pl-8 pr-3 py-1.5 text-sm rounded-md bg-gray-700 border-gray-600 text-gray-100 focus:ring-blue-500 focus:border-blue-500" 
                       required>
            </div>
            @error('name')
            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-1">
                Email <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-500 text-sm"></i>
                </div>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       class="block w-full pl-8 pr-3 py-1.5 text-sm rounded-md bg-gray-700 border-gray-600 text-gray-100 focus:ring-blue-500 focus:border-blue-500" 
                       required>
            </div>
            @error('email')
            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-1">
                Password <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-500 text-sm"></i>
                </div>
                <input type="password" name="password" id="password"
                       class="block w-full pl-8 pr-3 py-1.5 text-sm rounded-md bg-gray-700 border-gray-600 text-gray-100 focus:ring-blue-500 focus:border-blue-500" 
                       required>
            </div>
            @error('password')
            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="space-y-3">
        <!-- Unit -->
        <div>
            <label for="unit" class="block text-sm font-medium text-gray-300 mb-1">
                Unit <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                    <i class="fas fa-building text-gray-500 text-sm"></i>
                </div>
                <select name="unit" id="unit"
                        class="block w-full pl-8 pr-3 py-1.5 text-sm rounded-md bg-gray-700 border-gray-600 text-gray-100 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">Pilih Unit</option>
                    <option value="unit_default" {{ old('unit') == 'unit_default' ? 'selected' : '' }}>Unit Default</option>
                </select>
            </div>
            @error('unit')
            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Role -->
        <div>
            <label for="role" class="block text-sm font-medium text-gray-300 mb-1">
                Role <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                    <i class="fas fa-user-shield text-gray-500 text-sm"></i>
                </div>
                <select name="role" id="role"
                        class="block w-full pl-8 pr-3 py-1.5 text-sm rounded-md bg-gray-700 border-gray-600 text-gray-100 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">Pilih Role</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    @if(auth()->user()->role === 'super_admin')
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    @endif
                </select>
            </div>
            @error('role')
            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        <div>
            <label for="is_active" class="block text-sm font-medium text-gray-300 mb-1">
                Status <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                    <i class="fas fa-toggle-on text-gray-500 text-sm"></i>
                </div>
                <select name="is_active" id="is_active"
                        class="block w-full pl-8 pr-3 py-1.5 text-sm rounded-md bg-gray-700 border-gray-600 text-gray-100 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
        </div>
    </div>
</div> 