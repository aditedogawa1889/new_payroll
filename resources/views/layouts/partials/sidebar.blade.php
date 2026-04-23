<aside id="main-sidebar" class="main-sidebar sidebar-dark-primary elevation-4 fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-adminlte-dark text-gray-300">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link flex items-center px-4 py-3 border-b border-gray-700 bg-adminlte-dark group">
        <div class="brand-image w-8 h-8 bg-adminlte-primary rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform elevation-3">
            <span class="text-white font-bold text-xl">P</span>
        </div>
        <span class="brand-text font-light text-xl text-white">Payroll <span class="font-bold">System</span></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar px-0 py-0 overflow-y-auto h-[calc(100vh-57px)]">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 flex items-center px-4 border-b border-gray-700">
            <div class="image mr-3">
                <div class="w-8 h-8 rounded-full bg-adminlte-gray flex items-center justify-center text-white elevation-2 text-xs">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
            <div class="info">
                <a href="{{ route('profile.edit') }}" class="block text-sm text-gray-300 hover:text-white truncate max-w-[150px]">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2 px-2">
            <ul class="nav nav-pills nav-sidebar flex-column space-y-1" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link flex items-center p-2 rounded hover:bg-white/10 transition-colors {{ request()->routeIs('dashboard') ? 'bg-adminlte-primary text-white active shadow-sm' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a2 2 0 002 2h2a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h2a2 2 0 002-2v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                <li class="nav-header text-xs uppercase font-bold text-gray-500 mt-4 mb-2 px-2">MANAGEMENT</li>

                @can('manage employees')
                <li class="nav-item">
                    <a href="#" class="nav-link flex items-center p-2 rounded hover:bg-white/10 transition-colors group">
                        <svg class="nav-icon w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                        <p class="flex-1">Employees</p>
                        <span class="badge badge-info bg-adminlte-info text-[10px] px-1.5 rounded-full">New</span>
                    </a>
                </li>
                @endcan

                @can('calculate payroll')
                <li class="nav-item">
                    <a href="#" class="nav-link flex items-center p-2 rounded hover:bg-white/10 transition-colors">
                        <svg class="nav-icon w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                        <p>Payroll Calculation</p>
                    </a>
                </li>
                @endcan

                <li class="nav-header text-xs uppercase font-bold text-gray-500 mt-4 mb-2 px-2">SETTINGS</li>
                
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link flex items-center p-2 rounded hover:bg-white/10 transition-colors {{ request()->routeIs('profile.edit') ? 'bg-adminlte-primary text-white active' : '' }}">
                        <svg class="nav-icon w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
                        <p>User Profile</p>
                    </a>
                </li>

                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link flex items-center w-full text-left p-2 rounded hover:bg-adminlte-danger/20 text-red-400 transition-colors">
                            <svg class="nav-icon w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path></svg>
                            <p>Logout</p>
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>

