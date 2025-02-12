@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('{{ asset('images/background-login.jpg') }}');">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="max-w-md w-full space-y-6 bg-white/90 backdrop-blur-sm p-10 rounded-xl shadow-2xl relative">
        <div>
            <div class="flex justify-center -mt-4 mb-2">
                <img src="{{ asset('images/pln-logo.png') }}" alt="PLN Logo" class="h-32 w-auto">
            </div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900">
                PLN Nusantara Power
            </h2>
            <p class="mt-1 text-center text-sm text-gray-600">
                Operations Management System
            </p>
        </div>
        <form class="mt-6 space-y-4" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="flex items-center bg-gray-50 rounded-lg border border-gray-300 hover:border-blue-500 transition-colors">
                    <div class="p-2 bg-gray-50 rounded-l-lg border-r border-gray-300">
                        <i class="fas fa-user text-xl text-gray-400"></i>
                    </div>
                    <input id="email" name="email" type="email" required 
                        class="block w-full px-4 py-3 bg-gray-50 border-0 focus:ring-0 focus:outline-none rounded-r-lg text-gray-900 placeholder-gray-500 sm:text-sm" 
                        placeholder="Enter Username">
                </div>
                <div class="flex items-center bg-gray-50 rounded-lg border border-gray-300 hover:border-blue-500 transition-colors">
                    <div class="p-2 bg-gray-50 rounded-l-lg border-r border-gray-300">
                        <i class="fas fa-lock text-xl text-gray-400"></i>
                    </div>
                    <input id="password" name="password" type="password" required 
                        class="block w-full px-4 py-3 bg-gray-50 border-0 focus:ring-0 focus:outline-none rounded-r-lg text-gray-900 placeholder-gray-500 sm:text-sm" 
                        placeholder="Enter Password">
                </div>
            </div>

            @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            There were errors with your submission
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

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt text-blue-500 group-hover:text-blue-400"></i>
                    </span>
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 