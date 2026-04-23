<nav class="main-header navbar navbar-expand navbar-white navbar-light fixed top-0 right-0 left-0 sm:left-64 z-30 bg-white border-b border-gray-200 transition-all duration-300">
    <div class="px-6 py-2.5 w-full flex items-center justify-between h-16">
        <!-- Left navbar links -->
        <div class="flex items-center space-x-4">
            <button data-drawer-target="main-sidebar" data-drawer-toggle="main-sidebar" aria-controls="main-sidebar" type="button" class="text-gray-500 hover:text-gray-700 sm:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <div class="hidden sm:flex items-center space-x-4">
                <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Home</a>
                <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors">Contact</a>
            </div>
        </div>

        <!-- Right navbar links -->
        <div class="flex items-center space-x-6">
            <!-- Navbar Search -->
            <button class="text-gray-500 hover:text-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>

            <!-- Notifications Dropdown -->
            <div class="relative group">
                <button class="text-gray-500 hover:text-gray-700 relative transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute -top-1.5 -right-1.5 bg-adminlte-danger text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold elevation-1">15</span>
                </button>
            </div>

            <!-- User Menu -->
            <div class="relative">
                <button type="button" class="flex items-center space-x-3 text-sm focus:outline-none group" id="user-menu-button" data-dropdown-toggle="dropdown-user">
                    <span class="hidden lg:inline text-gray-700 font-semibold group-hover:text-adminlte-primary transition-colors">{{ Auth::user()->name }}</span>
                    <div class="w-9 h-9 rounded-full bg-adminlte-primary flex items-center justify-center text-white shadow-sm elevation-2 font-bold transform group-hover:scale-105 transition-all">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </button>
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-xl border border-gray-100 min-w-[200px]" id="dropdown-user">
                    <div class="px-4 py-4 bg-gray-50 rounded-t-lg">
                        <p class="text-sm text-gray-900 font-bold leading-none mb-1">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <ul class="py-2">
                        <li>
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-adminlte-primary hover:text-white transition-colors">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Profile Settings
                            </a>
                        </li>
                        <li class="border-t border-gray-100 mt-2 pt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
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


