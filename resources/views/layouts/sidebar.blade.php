<!-- Sidebar backdrop -->
<div x-show="isSidebarOpen" 
     x-transition:enter="transition-opacity ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-20 lg:hidden bg-gray-900/50 dark:bg-black/50" 
     @click="isSidebarOpen = false">
</div>

<!-- Sidebar -->
<aside class="fixed inset-y-0 left-0 z-30 flex flex-col w-64 transform transition-all duration-300 ease-in-out bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700"
       :class="{
           'translate-x-0 w-64': isSidebarOpen,
           '-translate-x-full lg:translate-x-0 lg:w-20': !isSidebarOpen
       }">
    <!-- Logo Section -->
    <div class="flex items-center h-16 px-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center w-full"
             :class="{ 'justify-center': !isSidebarOpen }">
            <img src="{{ url('images/logo-pln.png') }}" alt="PLN Logo" 
                 class="transition-all duration-300 ease-in-out object-contain"
                 :class="{ 'h-10': isSidebarOpen, 'h-8 w-8': !isSidebarOpen }">
            <div x-show="isSidebarOpen"
                 x-transition:enter="transition-opacity ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="ml-3">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">PLN Power</h2>
                <p class="text-xs text-gray-600 dark:text-gray-400">Operations</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           :class="{ 'justify-center': !isSidebarOpen, 'justify-start': isSidebarOpen }">
            <i class="fas fa-chart-line text-lg" :class="{ 'mr-3': isSidebarOpen }"></i>
            <span x-show="isSidebarOpen" 
                  x-transition:enter="transition-opacity ease-out duration-300"
                  x-transition:enter-start="opacity-0"
                  x-transition:enter-end="opacity-100">Dashboard</span>
        </a>

        <a href="{{ route('ikhtisar-harian') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('ikhtisar-harian') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           :class="{ 'justify-center': !isSidebarOpen, 'justify-start': isSidebarOpen }">
            <i class="fas fa-clipboard-list text-lg" :class="{ 'mr-3': isSidebarOpen }"></i>
            <span x-show="isSidebarOpen"
                  x-transition:enter="transition-opacity ease-out duration-300"
                  x-transition:enter-start="opacity-0"
                  x-transition:enter-end="opacity-100">Ikhtisar Harian</span>
        </a>

        <a href="{{ route('analytics') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('analytics') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           :class="{ 'justify-center': !isSidebarOpen, 'justify-start': isSidebarOpen }">
            <i class="fas fa-chart-bar text-lg" :class="{ 'mr-3': isSidebarOpen }"></i>
            <span x-show="isSidebarOpen"
                  x-transition:enter="transition-opacity ease-out duration-300"
                  x-transition:enter-start="opacity-0"
                  x-transition:enter-end="opacity-100">Analytics</span>
        </a>

        <a href="{{ route('reports') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('reports') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           :class="{ 'justify-center': !isSidebarOpen, 'justify-start': isSidebarOpen }">
            <i class="fas fa-file-alt text-lg" :class="{ 'mr-3': isSidebarOpen }"></i>
            <span x-show="isSidebarOpen"
                  x-transition:enter="transition-opacity ease-out duration-300"
                  x-transition:enter-start="opacity-0"
                  x-transition:enter-end="opacity-100">Reports</span>
        </a>
        <a href="{{ route('unit-mesin.index') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('unit-mesin.*') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           :class="{ 'justify-center': !isSidebarOpen, 'justify-start': isSidebarOpen }">
            <i class="fas fa-industry text-lg" :class="{ 'mr-3': isSidebarOpen }"></i>
            <span x-show="isSidebarOpen"
                  x-transition:enter="transition-opacity ease-out duration-300"
                  x-transition:enter-start="opacity-0"
                  x-transition:enter-end="opacity-100">Unit dan Mesin</span>
        </a>

        @if(auth()->user()->isSuperAdmin())
        <a href="{{ route('user-management.index') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('user-management.*') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           :class="{ 'justify-center': !isSidebarOpen, 'justify-start': isSidebarOpen }">
            <i class="fas fa-users text-lg" :class="{ 'mr-3': isSidebarOpen }"></i>
            <span x-show="isSidebarOpen"
                  x-transition:enter="transition-opacity ease-out duration-300"
                  x-transition:enter-start="opacity-0"
                  x-transition:enter-end="opacity-100">Manajemen Pengguna</span>
        </a>
        @endif
        <a href="{{ route('settings') }}" 
           class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-colors duration-200 {{ request()->routeIs('settings') ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           :class="{ 'justify-center': !isSidebarOpen, 'justify-start': isSidebarOpen }">
            <i class="fas fa-cog text-lg" :class="{ 'mr-3': isSidebarOpen }"></i>
            <span x-show="isSidebarOpen"
                  x-transition:enter="transition-opacity ease-out duration-300"
                  x-transition:enter-start="opacity-0"
                  x-transition:enter-end="opacity-100">Settings</span>
        </a>

        
    </nav>

    <!-- User Section -->
    <div class="flex-shrink-0 p-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center" :class="{ 'justify-between': isSidebarOpen, 'justify-center': !isSidebarOpen }">
            <div class="flex items-center" x-show="isSidebarOpen"
                 x-transition:enter="transition-opacity ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100">
                <i class="fas fa-user-circle text-xl text-gray-400"></i>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        @if(auth()->user()->isSuperAdmin())
                            Super Admin
                        @elseif(auth()->user()->isAdmin())
                            Admin
                        @else
                            {{ auth()->user()->name }}
                        @endif
                    </p>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                       class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Sign Out</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
            
            <!-- Dark mode toggle -->
            <button @click="isDarkMode = !isDarkMode"
                    class="p-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                <i class="text-lg text-gray-600 dark:text-gray-300"
                   :class="{ 'fas fa-moon': !isDarkMode, 'fas fa-sun': isDarkMode }"></i>
            </button>
        </div>
    </div>
</aside>

<!-- Alpine.js -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('darkMode', {
            on: localStorage.getItem('darkMode') === 'true',
            toggle() {
                this.on = !this.on;
                localStorage.setItem('darkMode', this.on);
                document.documentElement.classList.toggle('dark', this.on);
            }
        });
    });
</script> 