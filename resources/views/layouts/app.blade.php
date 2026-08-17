<!DOCTYPE html>
<html lang="en" x-data="{ dark: false }" :class="dark ? 'dark' : ''">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} — API Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
    <script>
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body x-init="$watch('dark', value => {
        localStorage.setItem('theme', value ? 'dark' : 'light');
        document.documentElement.classList.toggle('dark', value);
    });
    dark = localStorage.getItem('theme') === 'dark';
">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
        <!-- Top Navigation -->
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-900 dark:text-white">
                                API <span class="text-blue-600 dark:text-blue-400">Playground</span>
                            </a>
                        </div>
                        <div class="hidden sm:-mb-px sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('dashboard') }}"
                               class="{{ request()->routeIs('dashboard') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}
                                          inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Dashboard
                            </a>
                            <a href="{{ route('products.index') }}"
                               class="{{ request()->routeIs('products.*') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}
                                          inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Products
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center sm:ml-6 sm:pl-4">
                        <button @click="dark = !dark" type="button"
                            class="p-2 rounded-md text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            <span x-show="!dark" x-cloak>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 3v1m0 16.5V21m8.66-12.34l-.7.7A7.5 7.5 0 0112 19.5a7.5 7.5 0 01-7.96-7.96l.7-.7M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </span>
                            <span x-show="dark" x-cloak>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354A7.65 7.65 0 004.5 12c0 4.142 3.358 8 7.5 8 2.84.002 5.253-1.58 6.55-3.996l-1.5-.7A6 6 0 0112 19.5a6 6 0 01-.165-11.99L12 7.5l.5.5z" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
