<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - NamaAplikasi</title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transition-transform transform lg:translate-x-0 lg:static lg:inset-0 shadow-xl"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <div class="flex items-center justify-center h-16 bg-slate-800 border-b border-slate-700">
                <span class="text-xl font-bold tracking-wider uppercase">Admin Panel</span>
            </div>

            <nav class="mt-5 px-4 space-y-2">
                <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-indigo-600 hover:text-white rounded-md transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    Dashboard
                </a>
                
                <a href="#" class="flex items-center px-4 py-2 text-gray-300 hover:bg-indigo-600 hover:text-white rounded-md transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Users
                </a>
                
                </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">

            <header class="flex justify-between items-center py-4 px-6 bg-white border-b border-gray-200 shadow-sm">
                
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <h2 class="text-xl font-semibold text-gray-800 hidden sm:block">
                    @yield('header_title', 'Dashboard')
                </h2>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Halo, Admin</span>
                    <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">
                        A
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @yield('content')
            </main>

        </div>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black opacity-50 lg:hidden"></div>
    </div>

</body>
</html>