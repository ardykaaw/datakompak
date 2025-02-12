<div class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64">
        <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto bg-white border-r">
            <div class="flex items-center justify-center flex-shrink-0 px-4 -mt-2">
                <img src="{{ asset('images/logo-pln.png') }}" alt="PLN Logo" class="h-24 w-auto">
            </div>
            <div class="flex flex-col items-center -mt-2">
                <h2 class="text-lg font-semibold text-gray-900">PLN Nusantara Power</h2>
                <p class="text-sm text-gray-600">Operations Management</p>
            </div>
            <div class="mt-6 flex-grow flex flex-col">
                <nav class="flex-1 px-2 space-y-1">
                    <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-home mr-3 {{ request()->routeIs('dashboard') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                        Dashboard
                    </a>
                    <a href="#" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-chart-line mr-3 text-gray-400"></i>
                        Analytics
                    </a>
                    <a href="#" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                        <i class="fas fa-cog mr-3 text-gray-400"></i>
                        Settings
                    </a>
                    @if(auth()->user()->is_super_admin)
                    <a href="{{ route('super.admin') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('super.admin') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-user-shield mr-3 {{ request()->routeIs('super.admin') ? 'text-blue-500' : 'text-gray-400' }}"></i>
                        Super Admin
                    </a>
                    @endif
                </nav>
            </div>
            <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                <div class="flex items-center">
                    <div>
                        <i class="fas fa-user-circle text-2xl text-gray-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-medium text-gray-500 hover:text-gray-700">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 