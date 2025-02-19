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
<aside class="fixed inset-y-0 left-0 z-30 transform transition-all duration-300 h-screen bg-transparent  text-white p-3"
       :class="{
           'w-[280px]': isSidebarOpen,
           'w-[80px]': !isSidebarOpen
       }">
    <!-- Container untuk background dengan padding -->
    <div class="bg-[#0A749B] dark:bg-gray-800 rounded-2xl h-full px-4 py-6 flex flex-col">
        <!-- Logo section -->
        <div class="flex items-center justify-center mb-8">
            <img src="{{ url('images/navlogo.png') }}" alt="PLN Logo" 
                 :class="{ 'w-40': isSidebarOpen, 'w-10': !isSidebarOpen }">
        </div>

        <!-- Navigation -->
        <nav class="space-y-2 flex-grow">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200"
               :class="{ 
                   'justify-start': isSidebarOpen, 
                   'justify-center': !isSidebarOpen,
                   'bg-white/10 text-white font-medium dark:bg-gray-700': {{ request()->routeIs('dashboard') }},
                   'text-gray-100 hover:bg-white/10 dark:text-gray-200 dark:hover:bg-gray-700': !{{ request()->routeIs('dashboard') }}
               }">
                <i class="fas fa-tachometer-alt w-6" :class="{ 'mr-3': isSidebarOpen }"></i>
                <span class="text-base" x-show="isSidebarOpen">Dashboard</span>
            </a>

            <a href="{{ route('ikhtisar-harian') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200"
               :class="{ 
                   'justify-start': isSidebarOpen, 
                   'justify-center': !isSidebarOpen,
                   'bg-white/10 text-white font-medium dark:bg-gray-700': {{ request()->routeIs('ikhtisar-harian') }},
                   'text-gray-100 hover:bg-white/10 dark:text-gray-200 dark:hover:bg-gray-700': !{{ request()->routeIs('ikhtisar-harian') }}
               }">
                <i class="fas fa-clipboard-list w-6" :class="{ 'mr-3': isSidebarOpen }"></i>
                <span class="text-base" x-show="isSidebarOpen">Ikhtisar Harian</span>
            </a>

            <a href="{{ route('kinerja-pembangkit.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200"
               :class="{ 
                   'justify-start': isSidebarOpen, 
                   'justify-center': !isSidebarOpen,
                   'bg-white/10 text-white font-medium dark:bg-gray-700': {{ request()->routeIs('kinerja-pembangkit.*') }},
                   'text-gray-100 hover:bg-white/10 dark:text-gray-200 dark:hover:bg-gray-700': !{{ request()->routeIs('kinerja-pembangkit.*') }}
               }">
                <i class="fas fa-chart-line w-6" :class="{ 'mr-3': isSidebarOpen }"></i>
                <span class="text-base" x-show="isSidebarOpen">Kinerja Pembangkit</span>
            </a>
            <a href="{{ route('laporan-kesiapan-kit.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200"
               :class="{ 
                   'justify-start': isSidebarOpen, 
                   'justify-center': !isSidebarOpen,
                   'bg-white/10 text-white font-medium dark:bg-gray-700': {{ request()->routeIs('laporan-kesiapan-kit.*') }},
                   'text-gray-100 hover:bg-white/10 dark:text-gray-200 dark:hover:bg-gray-700': !{{ request()->routeIs('laporan-kesiapan-kit.*') }}
               }">
                <i class="fas fa-clipboard-check w-6" :class="{ 'mr-3': isSidebarOpen }"></i>
                <span class="text-base" x-show="isSidebarOpen">Laporan Kesiapan KIT</span>
            </a>
            

            <a href="{{ route('analytics') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200"
               :class="{ 
                   'justify-start': isSidebarOpen, 
                   'justify-center': !isSidebarOpen,
                   'bg-white/10 text-white font-medium dark:bg-gray-700': {{ request()->routeIs('analytics') }},
                   'text-gray-100 hover:bg-white/10 dark:text-gray-200 dark:hover:bg-gray-700': !{{ request()->routeIs('analytics') }}
               }">
                <i class="fas fa-chart-bar w-6" :class="{ 'mr-3': isSidebarOpen }"></i>
                <span class="text-base" x-show="isSidebarOpen">Analytics</span>
            </a>

            <a href="{{ route('reports') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200"
               :class="{ 
                   'justify-start': isSidebarOpen, 
                   'justify-center': !isSidebarOpen,
                   'bg-white/10 text-white font-medium dark:bg-gray-700': {{ request()->routeIs('reports') }},
                   'text-gray-100 hover:bg-white/10 dark:text-gray-200 dark:hover:bg-gray-700': !{{ request()->routeIs('reports') }}
               }">
                <i class="fas fa-file-alt w-6" :class="{ 'mr-3': isSidebarOpen }"></i>
                <span class="text-base" x-show="isSidebarOpen">Reports</span>
            </a>
            @if(auth()->user()->isSuperAdmin())
            <a href="{{ route('unit-mesin.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200"
               :class="{ 
                   'justify-start': isSidebarOpen, 
                   'justify-center': !isSidebarOpen,
                   'bg-white/10 text-white font-medium dark:bg-gray-700': {{ request()->routeIs('unit-mesin.*') }},
                   'text-gray-100 hover:bg-white/10 dark:text-gray-200 dark:hover:bg-gray-700': !{{ request()->routeIs('unit-mesin.*') }}
               }">
                <i class="fas fa-industry w-6" :class="{ 'mr-3': isSidebarOpen }"></i>
                <span class="text-base" x-show="isSidebarOpen">Unit dan Mesin</span>
            </a>
            @endif
           

            @if(auth()->user()->isSuperAdmin())
            <a href="{{ route('user-management.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200"
               :class="{ 
                   'justify-start': isSidebarOpen, 
                   'justify-center': !isSidebarOpen,
                   'bg-white/10 text-white font-medium dark:bg-gray-700': {{ request()->routeIs('user-management.*') }},
                   'text-gray-100 hover:bg-white/10 dark:text-gray-200 dark:hover:bg-gray-700': !{{ request()->routeIs('user-management.*') }}
               }">
                <i class="fas fa-users w-6" :class="{ 'mr-3': isSidebarOpen }"></i>
                <span class="text-base" x-show="isSidebarOpen">Manajemen Pengguna</span>
            </a>
            @endif
            
            @if(auth()->user()->isSuperAdmin())
            <a href="{{ route('settings') }}" 
               class="flex items-center px-3 py-2.5 rounded-lg transition-colors duration-200"
               :class="{ 
                   'justify-start': isSidebarOpen, 
                   'justify-center': !isSidebarOpen,
                   'bg-white/10 text-white font-medium dark:bg-gray-700': {{ request()->routeIs('settings') }},
                   'text-gray-100 hover:bg-white/10 dark:text-gray-200 dark:hover:bg-gray-700': !{{ request()->routeIs('settings') }}
               }">
                <i class="fas fa-cog w-6" :class="{ 'mr-3': isSidebarOpen }"></i>
                <span class="text-base" x-show="isSidebarOpen">Settings</span>
            </a>
            @endif
        </nav>

        <!-- Bottom Section: User & Logout -->
        <div class="mt-2">
            <!-- Dark mode toggle -->
            <button @click="isDarkMode = !isDarkMode"
                    class="flex items-center w-full px-3 py-2.5 rounded-lg transition-colors duration-200"
                    :class="{ 
                        'justify-start': isSidebarOpen, 
                        'justify-center': !isSidebarOpen,
                        'bg-white/10 hover:bg-white/20 dark:bg-gray-700 dark:hover:bg-gray-600': true
                    }">
                <i class="fas fa-moon w-6 h-6 text-white dark:text-gray-200" x-show="!isDarkMode"></i>
                <i class="fas fa-sun w-6 h-6 text-white dark:text-gray-200" x-show="isDarkMode"></i>
                <span class="ml-3 text-base text-white dark:text-gray-200" x-show="isSidebarOpen">
                    <span x-show="!isDarkMode">Dark Mode</span>
                    <span x-show="isDarkMode">Light Mode</span>
                </span>
            </button>

            <div class="flex items-center px-3 py-2.5 mb-2" x-show="isSidebarOpen">
                <i class="fas fa-user-circle text-xl text-white/70 dark:text-gray-400"></i>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white dark:text-gray-200">
                        @if(auth()->user()->isSuperAdmin())
                            Super Admin
                        @elseif(auth()->user()->isAdmin())
                            Admin
                        @else
                            {{ auth()->user()->name }}
                        @endif
                    </p>
                </div>
            </div>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="flex items-center w-full px-3 py-2.5 rounded-lg transition-colors duration-200"
                    :class="{ 
                        'justify-start': isSidebarOpen, 
                        'justify-center': !isSidebarOpen,
                        'text-white bg-red-400 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600': true
                    }">
                <i class="fas fa-sign-out-alt w-6 h-6" :class="{ 'mr-3': isSidebarOpen }"></i>
                <span class="text-base" x-show="isSidebarOpen">Logout</span>
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