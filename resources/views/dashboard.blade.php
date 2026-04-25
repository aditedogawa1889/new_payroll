<x-admin-layout>
    <x-slot name="header">
        <div class="row flex items-center justify-between">
            <div class="col-sm-6">
                <h1 class="m-0 text-2xl font-bold text-gray-800">Dashboard</h1>
            </div>
            <div class="col-sm-6 hidden md:block">
                <ol class="breadcrumb flex text-sm text-gray-500">
                    <li class="breadcrumb-item"><a href="#" class="text-adminlte-primary hover:underline">Home</a></li>
                    <li class="breadcrumb-item active ml-2 before:content-['/'] before:mr-2">Dashboard</li>
                </ol>
            </div>
        </div>
    </x-slot>

    <!-- Small boxes (Stat box) -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- small box -->
        <div class="small-box bg-adminlte-info text-white p-4 rounded relative overflow-hidden shadow-sm group">
            <div class="inner relative z-10">
                <h3 class="text-3xl font-bold">150</h3>
                <p>New Orders</p>
            </div>
            <div class="icon absolute right-4 top-4 text-black/10 group-hover:scale-110 transition-transform">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
            </div>
            <a href="#" class="small-box-footer block text-center bg-black/10 mt-4 py-1 text-xs hover:bg-black/20 transition-colors">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
        
        <!-- small box -->
        <div class="small-box bg-adminlte-success text-white p-4 rounded relative overflow-hidden shadow-sm group">
            <div class="inner relative z-10">
                <h3 class="text-3xl font-bold">53<sup class="text-xl">%</sup></h3>
                <p>Bounce Rate</p>
            </div>
            <div class="icon absolute right-4 top-4 text-black/10 group-hover:scale-110 transition-transform">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
            </div>
            <a href="#" class="small-box-footer block text-center bg-black/10 mt-4 py-1 text-xs hover:bg-black/20 transition-colors">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>

        <!-- small box -->
        <div class="small-box bg-adminlte-warning text-white p-4 rounded relative overflow-hidden shadow-sm group">
            <div class="inner relative z-10">
                <h3 class="text-3xl font-bold">44</h3>
                <p>User Registrations</p>
            </div>
            <div class="icon absolute right-4 top-4 text-black/10 group-hover:scale-110 transition-transform">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <a href="#" class="small-box-footer block text-center bg-black/10 mt-4 py-1 text-xs hover:bg-black/20 transition-colors">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>

        <!-- small box -->
        <div class="small-box bg-adminlte-danger text-white p-4 rounded relative overflow-hidden shadow-sm group">
            <div class="inner relative z-10">
                <h3 class="text-3xl font-bold">65</h3>
                <p>Unique Visitors</p>
            </div>
            <div class="icon absolute right-4 top-4 text-black/10 group-hover:scale-110 transition-transform">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20h4V4h-4v16zm-6 0h4v-8H4v8zM16 9v11h4V9h-4z"/></svg>
            </div>
            <a href="#" class="small-box-footer block text-center bg-black/10 mt-4 py-1 text-xs hover:bg-black/20 transition-colors">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- Main row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left col -->
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>
                    <span>Sales Overview</span>
                </div>
                <div class="card-tools">
                    <button class="text-gray-400 hover:text-gray-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg></button>
                </div>
            </div>
            <div class="admin-card-body">
                <div class="h-64 bg-gray-100 rounded flex items-center justify-center text-gray-400 italic">
                    [ Sales Chart Placeholder ]
                </div>
            </div>
        </div>

        <!-- Right col -->
        <div class="admin-card border-t-4 border-adminlte-success">
            <div class="admin-card-header">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>
                    <span>Recent Notifications</span>
                </div>
                <span class="badge badge-success bg-adminlte-success text-white text-[10px] px-1.5 rounded-full">4 New</span>
            </div>
            <div class="admin-card-body p-0">
                <ul class="divide-y divide-gray-100">
                    <li class="p-4 flex items-start hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 rounded-full bg-adminlte-info/10 flex items-center justify-center text-adminlte-info mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">New payroll generated</p>
                            <p class="text-xs text-gray-500">Payroll for April 2026 is ready for review.</p>
                            <p class="text-[10px] text-gray-400 mt-1">2 mins ago</p>
                        </div>
                    </li>
                    <li class="p-4 flex items-start hover:bg-gray-50 transition-colors">
                        <div class="w-10 h-10 rounded-full bg-adminlte-warning/10 flex items-center justify-center text-adminlte-warning mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">Server load high</p>
                            <p class="text-xs text-gray-500">The main server reached 90% CPU usage.</p>
                            <p class="text-[10px] text-gray-400 mt-1">1 hour ago</p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="admin-card-footer px-4 py-2 bg-gray-50 border-t border-gray-100 text-center">
                <a href="#" class="text-sm text-adminlte-primary hover:underline">View All Notifications</a>
            </div>
        </div>
    </div>
</x-admin-layout>

