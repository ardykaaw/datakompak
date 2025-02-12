@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="flex w-full max-w-4xl bg-white rounded-2xl shadow-lg overflow-hidden">
        <!-- Left side - Logo -->
        <div class="w-1/2 bg-blue-600 p-12 flex items-center justify-center">
            <img src="{{ url('images/logo-pln.png') }}" alt="PLN Logo" class="w-full max-w-md">
        </div>

        <!-- Right side - Login Form -->
        <div class="w-1/2 p-12">
            <h2 class="text-2xl font-semibold mb-1">Login Now</h2>
            
            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <input id="email" name="email" type="email" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Enter Username">
                    </div>

                    <div>
                        <input id="password" name="password" type="password" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Enter Password">
                    </div>

                    <div>
                        <label class="text-sm text-blue-600">Pilih Unit:</label>
                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option>UP Kendari</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="remember_me" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="remember_me" class="ml-2 text-sm text-gray-600">Remember Me</label>
                    </div>
                </div>

                @if ($errors->any())
                <div class="mt-4 text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <button type="submit" class="w-full px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Login
                </button>
            </form>
        </div>
    </div>
</div>
@endsection