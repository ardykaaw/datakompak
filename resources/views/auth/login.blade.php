@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ url('images/powerplant.jpeg') }}');">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 to-black/80"></div>
    <div class="max-w-lg w-full space-y-6 bg-white/95 backdrop-blur-md p-10 rounded-2xl shadow-2xl relative">
        <div class="space-y-3">
            <div class="flex justify-center -mt-8">
                <img src="{{ url('images/logo-pln.png') }}" alt="PLN Logo" class="h-36 w-auto">
            </div>
            <h2 class="text-center text-3xl font-bold text-blue-900 -mt-2">
                PLN Nusantara Power
            </h2>
            <p class="text-center text-base text-gray-600 font-medium border-t border-gray-200 pt-3">
                Operations Management System
            </p>
        </div>

        <form class="mt-8 space-y-4" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="group">
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                    <div class="flex items-center bg-gray-50 rounded-lg border-2 border-gray-200 group-hover:border-blue-500 transition-all duration-300">
                        <div class="p-3 bg-gray-100 rounded-l-lg border-r border-gray-200">
                            <i class="fas fa-building text-lg text-blue-600"></i>
                        </div>
                        <select id="unit" name="unit" required 
                            class="block w-full px-4 py-3 bg-gray-50 border-0 focus:ring-0 focus:outline-none rounded-r-lg text-gray-900">
                            <option value="">Pilih Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="group">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="flex items-center bg-gray-50 rounded-lg border-2 border-gray-200 group-hover:border-blue-500 transition-all duration-300">
                        <div class="p-3 bg-gray-100 rounded-l-lg border-r border-gray-200">
                            <i class="fas fa-user text-lg text-blue-600"></i>
                        </div>
                        <input id="email" name="email" type="email" required 
                            class="block w-full px-4 py-3 bg-gray-50 border-0 focus:ring-0 focus:outline-none rounded-r-lg text-gray-900 placeholder-gray-400 text-base" 
                            placeholder="Masukkan username Anda">
                    </div>
                </div>

                <div class="group">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="flex items-center bg-gray-50 rounded-lg border-2 border-gray-200 group-hover:border-blue-500 transition-all duration-300">
                        <div class="p-3 bg-gray-100 rounded-l-lg border-r border-gray-200">
                            <i class="fas fa-lock text-lg text-blue-600"></i>
                        </div>
                        <input id="password" name="password" type="password" required 
                            class="block w-full px-4 py-3 bg-gray-50 border-0 focus:ring-0 focus:outline-none rounded-r-lg text-gray-900 placeholder-gray-400 text-base" 
                            placeholder="Masukkan password Anda">
                    </div>
                </div>
            </div>

            @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4 border border-red-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-red-800">
                            Terjadi kesalahan pada login
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="pt-2">
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-base font-semibold rounded-lg text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt text-blue-300 group-hover:text-white transition-colors"></i>
                    </span>
                    Masuk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 