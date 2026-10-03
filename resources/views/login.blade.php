<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino System</title>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Geist:wght@300..800&display=swap');
        * {
            font-family: "Geist", sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50">

    <!-- Navigation Bar -->
    <nav class="bg-white px-6 md:px-12 lg:px-24 py-5 flex items-center justify-center relative mt-4 rounded-xl ">
        
        <!-- Desktop Nav Items -->
        <!-- bg-purple-50 දීලා තියෙන්නේ ලස්සන light theme එකකට -->
        <div class="hidden md:flex items-center bg-purple-50/50 border border-purple-100 rounded-full px-2 py-1.5 gap-3">
            
            <!-- Active Link (Dashboard) -->
            <a href="{{ url('/') }}" class="nav-link px-6 py-2.5 rounded-full text-base transition-colors bg-white border border-purple-200 font-semibold text-purple-700 shadow-sm">
                Dashboard
            </a>
            
            <!-- Inactive Links -->
            <a href="{{ url('/directory') }}" class="nav-link px-6 py-2.5 rounded-full text-base transition-colors font-medium text-slate-600 hover:text-purple-700 hover:bg-purple-100/50">
                Directory
            </a>
            <a href="{{ url('/history') }}" class="nav-link px-6 py-2.5 rounded-full text-base transition-colors font-medium text-slate-600 hover:text-purple-700 hover:bg-purple-100/50">
                History
            </a>
            <a href="{{ url('/profile') }}" class="nav-link px-6 py-2.5 rounded-full text-base transition-colors font-medium text-slate-600 hover:text-purple-700 hover:bg-purple-100/50">
                Profile
            </a>

        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="p-6 md:p-12">
        <h1 class="text-2xl text-slate-800 font-semibold">Welcome to Dashboard</h1>
    </main>

</body>

</html>