<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Geist:wght@300..800&display=swap');
        * { font-family: "Geist", sans-serif; }
    </style>
</head>
<body class="bg-slate-50">

    <!-- Navigation Bar -->
    <!-- Navigation Bar -->
    <nav class="bg-white px-6 md:px-12 lg:px-24 py-5 flex items-center justify-center relative mt-4 mx-4 rounded-xl ">
        <div class="hidden md:flex items-center bg-violet-200 border border-purple-100 rounded-full px-4 py-3 gap-3">
            
            <!-- Dashboard Link -->
            <a href="{{ url('/') }}" 
               class="nav-link inline-block px-6 py-2 rounded-full text-base transition-colors border 
               {{ request()->is('/') ? 'bg-violet-500 border-violet-600 font-semibold text-white shadow-md' : 'border-transparent font-medium text-slate-600 hover:text-violet-700 hover:bg-violet-50/50' }}">
                Dashboard
            </a>
            
            <!-- Directory Link -->
            <a href="{{ url('/directory') }}" 
               class="nav-link inline-block px-6 py-2 rounded-full text-base transition-colors border 
               {{ request()->is('directory') ? 'bg-violet-500 border-violet-600 font-semibold text-white shadow-md' : 'border-transparent font-medium text-slate-600 hover:text-violet-700 hover:bg-violet-50/50' }}">
                Directory
            </a>

            <!-- History Link -->
            <a href="{{ url('/history') }}" 
               class="nav-link inline-block px-6 py-2 rounded-full text-base transition-colors border 
               {{ request()->is('history') ? 'bg-violet-500 border-violet-600 font-semibold text-white shadow-md' : 'border-transparent font-medium text-slate-600 hover:text-violet-700 hover:bg-violet-50/50' }}">
                History
            </a>

            <!-- Profile Link -->
            <a href="{{ url('/profile') }}" 
               class="nav-link inline-block px-6 py-2 rounded-full text-base transition-colors border 
               {{ request()->is('profile') ? 'bg-violet-500 border-violet-600 font-semibold text-white shadow-md' : 'border-transparent font-medium text-slate-600 hover:text-violet-700 hover:bg-violet-50/50' }}">
                Profile
            </a>

        </div>
    </nav>

    <main class="p-6 md:p-12">
        @yield('content')
    </main>

</body>
</html>