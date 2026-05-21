<nav class="main-header navbar navbar-expand navbar-light fixed top-0 right-0 left-0 z-30 bg-white border-b border-gray-200 shadow-sm transition-all duration-300 h-16" :class="sidebarOpen ? 'sm:left-64' : 'sm:left-16 left-0'">
    <div class="px-6 w-full flex items-center justify-between h-full">
        <!-- Left navbar links -->
        <div class="flex items-center space-x-4 mt-4">
            <button @click="sidebarOpen = !sidebarOpen" type="button" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <div class="hidden sm:flex items-center space-x-4">
            </div>
        </div>

        <!-- Right navbar links -->
        <div class="flex items-center space-x-6 mt-4"> 
            <!-- Left blank, intentionally removed Search and Notifications -->

            <!-- User Menu -->
            <div class="relative">
                <button type="button" class="flex items-center space-x-3 text-sm focus:outline-none group" id="user-menu-button" data-dropdown-toggle="dropdown-user">
                    <span class="hidden lg:inline text-gray-700 font-semibold group-hover:text-adminlte-primary transition-colors">{{ Auth::user()->name }}</span>
                    <div class="w-9 h-9 rounded-full bg-adminlte-primary flex items-center justify-center text-white shadow-sm elevation-2 font-bold transform group-hover:scale-105 transition-all">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </button>
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-xl border border-gray-100 min-w-[200px]" id="dropdown-user">
                    <div class="px-4 py-4 bg-[#edf7ec] rounded-t-lg border-b border-[#d3f0ce]">
                        <p class="text-sm text-gray-900 font-bold leading-none mb-1">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-600 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <ul class="py-2">
                        <li>
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-adminlte-primary hover:text-white transition-colors">
                                <i class="fas fa-user-circle mr-3 text-lg"></i>
                                Profile Settings
                            </a>
                        </li>
                        <li class="border-t border-gray-100 mt-2 pt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold transition-colors">
                                    <i class="fas fa-sign-out-alt mr-3 text-lg text-red-500"></i>
                                    Sign out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>


