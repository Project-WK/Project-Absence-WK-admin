<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login Area') - NamaAplikasi</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        
        <div class="mb-6">
            <a href="/" class="text-3xl font-bold text-indigo-600 tracking-tighter">
                Nama<span class="text-gray-800">Aplikasi</span>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg border border-gray-100">
            @yield('content')
        </div>

        <div class="mt-4 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Nama Perusahaan.
        </div>
    </div>

</body>
</html>