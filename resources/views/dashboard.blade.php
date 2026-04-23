<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Dashboard</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <!-- Widgets -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
        <!-- Card 1 -->
        <div class="p-4 bg-blue-600 rounded-lg shadow-sm flex items-center justify-between text-white overflow-hidden relative group">
            <div>
                <p class="text-3xl font-bold">150</p>
                <p class="text-sm font-medium opacity-80">Total Employees</p>
            </div>
            <div class="opacity-20 group-hover:scale-110 transition-transform">
                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
            </div>
            <a href="#" class="absolute bottom-0 left-0 w-full bg-black/10 py-1 text-center text-xs hover:bg-black/20 transition-colors">More info →</a>
        </div>
        
        <!-- Card 2 -->
        <div class="p-4 bg-emerald-500 rounded-lg shadow-sm flex items-center justify-between text-white overflow-hidden relative group">
            <div>
                <p class="text-3xl font-bold">85%</p>
                <p class="text-sm font-medium opacity-80">Attendance Rate</p>
            </div>
            <div class="opacity-20 group-hover:scale-110 transition-transform">
                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2h6v4H7V4zm6 6H7v2h6v-2zm-6 4h6v2H7v-2z" clip-rule="evenodd"></path></svg>
            </div>
            <a href="#" class="absolute bottom-0 left-0 w-full bg-black/10 py-1 text-center text-xs hover:bg-black/20 transition-colors">More info →</a>
        </div>

        <!-- Card 3 -->
        <div class="p-4 bg-amber-500 rounded-lg shadow-sm flex items-center justify-between text-white overflow-hidden relative group">
            <div>
                <p class="text-3xl font-bold">12</p>
                <p class="text-sm font-medium opacity-80">Pending Payroll</p>
            </div>
            <div class="opacity-20 group-hover:scale-110 transition-transform">
                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
            </div>
            <a href="#" class="absolute bottom-0 left-0 w-full bg-black/10 py-1 text-center text-xs hover:bg-black/20 transition-colors">More info →</a>
        </div>

        <!-- Card 4 -->
        <div class="p-4 bg-rose-500 rounded-lg shadow-sm flex items-center justify-between text-white overflow-hidden relative group">
            <div>
                <p class="text-3xl font-bold">5</p>
                <p class="text-sm font-medium opacity-80">New Tickets</p>
            </div>
            <div class="opacity-20 group-hover:scale-110 transition-transform">
                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20"><path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path><path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path></svg>
            </div>
            <a href="#" class="absolute bottom-0 left-0 w-full bg-black/10 py-1 text-center text-xs hover:bg-black/20 transition-colors">More info →</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Main Content Box -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Recent Notifications</h3>
                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">New</span>
            </div>
            <div class="p-4">
                <ul class="divide-y divide-gray-100">
                    <li class="py-3 flex items-start">
                        <div class="w-2 h-2 mt-2 bg-blue-500 rounded-full mr-3 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Payroll for April 2026 has been generated.</p>
                            <p class="text-xs text-gray-500">2 hours ago</p>
                        </div>
                    </li>
                    <li class="py-3 flex items-start">
                        <div class="w-2 h-2 mt-2 bg-emerald-500 rounded-full mr-3 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">New employee "Jane Doe" added to HR department.</p>
                            <p class="text-xs text-gray-500">Yesterday</p>
                        </div>
                    </li>
                    <li class="py-3 flex items-start">
                        <div class="w-2 h-2 mt-2 bg-amber-500 rounded-full mr-3 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">System maintenance scheduled for Sunday.</p>
                            <p class="text-xs text-gray-500">2 days ago</p>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 text-center">
                <a href="#" class="text-sm font-medium text-blue-600 hover:underline">View all notifications</a>
            </div>
        </div>

        <!-- Another Content Box -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Quick Stats</h3>
            </div>
            <div class="p-4">
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Budget Usage</span>
                            <span class="text-sm font-medium text-gray-700">75%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Tasks Completed</span>
                            <span class="text-sm font-medium text-gray-700">45/60</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-emerald-500 h-2 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">Server Load</span>
                            <span class="text-sm font-medium text-gray-700">22%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-rose-500 h-2 rounded-full" style="width: 22%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
