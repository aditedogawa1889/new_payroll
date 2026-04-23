<aside id="main-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-slate-900 text-slate-300">
    <div class="h-full px-3 py-4 overflow-y-auto">
        <a href="{{ route('dashboard') }}" class="flex items-center ps-2.5 mb-5 group">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                <span class="text-white font-bold text-xl">P</span>
            </div>
            <span class="self-center text-xl font-semibold whitespace-nowrap text-white">Payroll System</span>
        </a>
        
        <div class="flex items-center p-2 mb-6 bg-slate-800 rounded-lg">
            <div class="w-10 h-10 rounded-full bg-slate-600 flex items-center justify-center text-white mr-3">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</p>
            </div>
        </div>

        <ul class="space-y-2 font-medium">
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : '' }} group">
                    <svg class="w-5 h-5 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>
            
            @can('manage employees')
            <li>
                <button type="button" class="flex items-center w-full p-2 text-base transition duration-75 rounded-lg group hover:bg-slate-800" aria-controls="dropdown-employees" data-collapse-toggle="dropdown-employees">
                    <svg class="flex-shrink-0 w-5 h-5 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Employees</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <ul id="dropdown-employees" class="hidden py-2 space-y-2">
                    <li>
                        <a href="#" class="flex items-center w-full p-2 transition duration-75 rounded-lg ps-11 group hover:bg-slate-800">All Employees</a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center w-full p-2 transition duration-75 rounded-lg ps-11 group hover:bg-slate-800">Departments</a>
                    </li>
                </ul>
            </li>
            @endcan

            @can('calculate payroll')
            <li>
                <a href="#" class="flex items-center p-2 rounded-lg hover:bg-slate-800 group">
                    <svg class="flex-shrink-0 w-5 h-5 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Payroll</span>
                </a>
            </li>
            @endcan

            <li>
                <a href="#" class="flex items-center p-2 rounded-lg hover:bg-slate-800 group">
                    <svg class="flex-shrink-0 w-5 h-5 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zm-7 0h5V8H4v2z" clip-rule="evenodd"></path></svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Attendance</span>
                </a>
            </li>

            <li class="pt-4 mt-4 space-y-2 border-t border-slate-700">
                <a href="{{ route('profile.edit') }}" class="flex items-center p-2 rounded-lg hover:bg-slate-800 group {{ request()->routeIs('profile.edit') ? 'bg-blue-600 text-white' : '' }}">
                    <svg class="flex-shrink-0 w-5 h-5 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
                    <span class="ms-3">Settings</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full p-2 rounded-lg hover:bg-red-900/20 text-red-400 group">
                        <svg class="flex-shrink-0 w-5 h-5 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path></svg>
                        <span class="ms-3">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>
