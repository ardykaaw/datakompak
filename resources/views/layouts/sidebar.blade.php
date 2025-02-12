<div class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64">
        <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto bg-white border-r">
            <div class="flex items-center justify-center flex-shrink-0 px-4">
                <img src="{{ url('images/logo-pln.png') }}" alt="PLN Logo" class="h-16 w-auto">
            </div>
            <div class="flex flex-col items-center">
                <h2 class="text-lg font-semibold text-gray-900">PLN Nusantara Power</h2>
                <p class="text-sm text-gray-600">Operations Management</p>
            </div>
            <div class="mt-8 flex-grow flex flex-col">
                <nav class="flex-1 px-2 space-y-1">
                    <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-chart-line mr-3 flex-shrink-0 h-6 w-6"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('ikhtisar-harian') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('ikhtisar-harian') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-clipboard-list mr-3 flex-shrink-0 h-6 w-6"></i>
                        Ikhtisar Harian
                    </a>
                    <a href="{{ route('analytics') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('analytics') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-chart-bar mr-3 flex-shrink-0 h-6 w-6"></i>
                        Analytics
                    </a>
                    <a href="{{ route('reports') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('reports') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-file-alt mr-3 flex-shrink-0 h-6 w-6"></i>
                        Reports
                    </a>
                    <a href="{{ route('settings') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('settings') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-cog mr-3 flex-shrink-0 h-6 w-6"></i>
                        Settings
                    </a>
                </nav>
            </div>
            <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                <div class="flex items-center">
                    <div>
                        <i class="fas fa-user-circle text-gray-400 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'User' }}</p>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-xs font-medium text-gray-500 hover:text-gray-700">
                            Sign Out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 