<aside id="main-sidebar" class="main-sidebar fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 !bg-[#c3f5ba] text-gray-800 border-r border-gray-200 elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link flex items-center px-4 !bg-[#c3f5ba] border-b border-gray-300 h-16 group transition-all duration-300">
        <div class="brand-image w-16 h-6 flex items-center justify-center mr-2 group-hover:scale-110 transition-transform duration-300">
            <img src="{{ asset('images/metland_logo.png') }}" alt="Metland Logo" class="h-full object-contain">
        </div>
        <div class="brand-text flex flex-col leading-none ml-1">
            <span class="text-sm font-bold text-gray-800 tracking-tight">System Payroll</span>
        </div>
    </a>

    <!-- Sidebar -->
    <div class="sidebar px-0 py-0 overflow-y-auto h-[calc(100vh-64px)]">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 flex items-center px-4 border-b border-gray-100">
            <div class="image mr-3">
                <div class="w-8 h-8 rounded-full bg-[#4a7c44] flex items-center justify-center text-white elevation-2 text-xs font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
            <div class="info">
                <a href="{{ route('profile.edit') }}" class="block text-sm text-gray-600 hover:text-adminlte-primary font-semibold truncate max-w-[150px]">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2 px-2">
            <ul class="nav nav-pills nav-sidebar flex-column space-y-1" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link flex items-center p-2 rounded hover:bg-black/5 transition-colors {{ request()->routeIs('dashboard') ? 'bg-white/50 text-gray-900 font-bold active shadow-sm' : 'text-gray-700' }}">
                        <i class="nav-icon fas fa-tachometer-alt w-5 h-5 mr-3 flex items-center justify-center"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                <!-- Dynamic Sidebar Menu -->
                @foreach($sidebarMenus as $menu)
                    @if($menu->is_parent)
                        <li class="nav-item has-treeview" x-data="{ open: {{ collect($menu->submenus)->pluck('uri')->contains(request()->route()->getName()) ? 'true' : 'false' }} }">
                            <a href="#" @click.prevent="open = !open" class="nav-link flex items-center p-2 rounded hover:bg-black/5 transition-colors group text-gray-700" :class="{ 'bg-black/5 text-gray-900 font-bold': open }">
                                <i class="nav-icon {{ $menu->icon ?? 'fas fa-circle' }} w-5 h-5 mr-3"></i>
                                <p class="flex-1">
                                    {{ $menu->nama_menu }}
                                    <i class="right fas fa-angle-left ml-auto transition-transform" :class="{ '-rotate-90': open }"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview ml-4 mt-1 space-y-1" x-show="open" x-transition>
                                @foreach($menu->submenus as $submenu)
                                    <li class="nav-item">
                                        <a href="{{ Route::has($submenu->uri) ? route($submenu->uri) : '#' }}" class="nav-link flex items-center p-2 rounded hover:bg-black/5 transition-colors {{ request()->routeIs($submenu->uri) ? 'bg-white/50 text-gray-900 font-bold shadow-sm' : 'text-gray-600' }}">
                                            <i class="{{ $submenu->icon ?? 'far fa-circle' }} nav-icon w-4 h-4 mr-3 text-xs"></i>
                                            <p class="text-sm">{{ $submenu->nama_menu }}</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ Route::has($menu->uri) ? route($menu->uri) : '#' }}" class="nav-link flex items-center p-2 rounded hover:bg-black/5 transition-colors {{ request()->routeIs($menu->uri) ? 'bg-white/50 text-gray-900 font-bold shadow-sm' : 'text-gray-700' }}">
                                <i class="nav-icon {{ $menu->icon ?? 'fas fa-circle' }} w-5 h-5 mr-3"></i>
                                <p>{{ $menu->nama_menu }}</p>
                            </a>
                        </li>
                    @endif
                @endforeach

                <li class="nav-header text-xs uppercase font-bold text-gray-400 mt-4 mb-2 px-2">SETTINGS</li>
                
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link flex items-center p-2 rounded hover:bg-black/5 transition-colors {{ request()->routeIs('profile.edit') ? 'bg-white/50 text-gray-900 font-bold active' : 'text-gray-700' }}">
                        <i class="nav-icon fas fa-user-cog w-5 h-5 mr-3 flex items-center justify-center"></i>
                        <p>User Profile</p>
                    </a>
                </li>

                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link flex items-center w-full text-left p-2 rounded hover:bg-red-50 text-red-500 transition-colors">
                            <i class="nav-icon fas fa-sign-out-alt w-5 h-5 mr-3 flex items-center justify-center"></i>
                            <p>Logout</p>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>

