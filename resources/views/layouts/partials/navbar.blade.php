<nav class="main-header navbar navbar-expand navbar-white navbar-light sticky top-0 z-30 bg-white border-b border-gray-200">
    <div class="px-4 py-2 w-full flex items-center justify-between">
        <!-- Left navbar links -->
        <div class="flex items-center space-x-4">
            <button data-drawer-target="main-sidebar" data-drawer-toggle="main-sidebar" aria-controls="main-sidebar" type="button" class="text-gray-500 hover:text-gray-700 sm:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <a href="#" class="text-gray-600 hover:text-gray-900 hidden sm:block">Home</a>
            <a href="#" class="text-gray-600 hover:text-gray-900 hidden sm:block">Contact</a>
        </div>

        <!-- Right navbar links -->
        <div class="flex items-center space-x-4">
            <!-- Navbar Search -->
            <button class="text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>

            <!-- Notifications Dropdown -->
            <div class="relative">
                <button class="text-gray-500 hover:text-gray-700 relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute -top-1 -right-1 bg-adminlte-danger text-white text-[10px] px-1 rounded-full">15</span>
                </button>
            </div>

            <!-- User Menu -->
            <div class="relative">
                <button type="button" class="flex items-center space-x-2 text-sm focus:outline-none" id="user-menu-button" data-dropdown-toggle="dropdown-user">
                    <span class="hidden md:inline text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                    <div class="w-8 h-8 rounded-full bg-adminlte-primary flex items-center justify-center text-white">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </button>
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow-lg border" id="dropdown-user">
                    <div class="px-4 py-3">
                        <p class="text-sm text-gray-900 font-bold">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <ul class="py-1">
                        <li><a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile Settings</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 font-semibold">Sign out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

