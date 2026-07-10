<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Operasional TKI' }}</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>✈️</text></svg>">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#4f46e5">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        /* Removed inline mesh-bg and glass-panel as they are moved to app.css and replaced by utility classes */
        .sidebar-link {
            transition: all 0.3s ease;
        }
        .sidebar-link.active {
            background: rgba(255, 255, 255, 0.9);
            border-left: 4px solid #4f46e5;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            font-weight: 600;
        }
        .sidebar-link:hover:not(.active) {
            background: rgba(255, 255, 255, 0.5);
        }
        .dark .sidebar-link:hover:not(.active) {
            background: rgba(255, 255, 255, 0.05);
        }
        .dark .sidebar-link:hover:not(.active) {
            background: rgba(255, 255, 255, 0.05);
        }
        .dark .sidebar-link.active {
            background: rgba(24, 24, 27, 0.9);
            color: #e0e7ff;
            border-left-color: #818cf8;
        }
        .dark .sidebar-link {
            color: #d1d5db;
        }
    </style>
</head>
<body class="bg-mono-50 text-mono-900 transition-colors duration-500 ease-in-out antialiased min-h-screen flex">
    <div x-data="{ sidebarOpen: false }" class="flex-1 flex h-screen overflow-hidden transition-colors duration-500 relative">
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm md:hidden" 
             @click="sidebarOpen = false" 
             x-cloak>
        </div>
        
        <!-- Sidebar -->
        <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" class="fixed inset-y-0 left-0 z-50 transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0 w-64 flex-shrink-0 flex flex-col h-full bg-white/80 backdrop-blur-xl border-r border-mono-200">
            <button @click="sidebarOpen = false" class="md:hidden absolute top-4 right-4 p-2 bg-red-500/10 text-red-600 hover:bg-red-500/20 rounded-full transition-colors z-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="p-6 border-b border-white/30 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">E-TKI<span class="text-indigo-600">.</span></h1>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <a href="{{ route('registration') }}" class="flex items-center gap-2 px-4 py-3 mb-4 rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transition-all {{ request()->routeIs('registration') ? 'ring-2 ring-indigo-300 ring-offset-2' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Registrasi E-Form
                </a>
                <a href="{{ route('dashboard') }}" class="sidebar-link block px-4 py-3 rounded-lg text-sm {{ request()->routeIs('dashboard') ? 'active text-mono-900 font-semibold' : 'text-mono-600 ' }}">Dashboard</a>
                <a href="{{ route('tki-data') }}" class="sidebar-link block px-4 py-3 rounded-lg text-sm {{ request()->routeIs('tki-data') ? 'active text-mono-900 font-semibold' : 'text-mono-600 ' }}">Data TKI</a>
                <a href="{{ route('document-tracker') }}" class="sidebar-link block px-4 py-3 rounded-lg text-sm {{ request()->routeIs('document-tracker') ? 'active text-mono-900 font-semibold' : 'text-mono-600 ' }}">Pelacak Dokumen</a>
                <a href="{{ route('profile') }}" class="sidebar-link block px-4 py-3 rounded-lg text-sm {{ request()->routeIs('profile') ? 'active text-mono-900 font-semibold' : 'text-mono-600 ' }}">Profil Saya</a>
                
                @hasrole('Super Admin')
                <div class="pt-4 mt-4 border-t border-mono-200 "></div>
                <a href="{{ route('settings') }}" class="sidebar-link block px-4 py-3 rounded-lg text-sm {{ request()->routeIs('settings') ? 'active text-mono-900 font-semibold' : 'text-mono-600 ' }}">Pengaturan Sistem</a>
                <a href="{{ route('history-log') }}" class="sidebar-link block px-4 py-3 rounded-lg text-sm {{ request()->routeIs('history-log') ? 'active text-mono-900 font-semibold' : 'text-mono-600 ' }}">History Log</a>
                @endhasrole
            </nav>



            <div class="p-4 border-t border-white/30 ">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold overflow-hidden transition-colors border-2 border-white/50">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr(auth()->user()->name, 0, 1) }}
                        @endif
                    </div>
                    <div class="overflow-hidden flex-1">
                        <p class="text-sm font-semibold truncate transition-colors">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate transition-colors">{{ auth()->user()->roles->pluck('name')->join(', ') }}</p>
                    </div>
                    <div class="hidden md:flex items-center">
                        <livewire:notification-bell position="bottom-full mb-2 right-0" />
                    </div>
                </div>
                <a href="{{ route('logout') }}" class="block w-full text-center px-4 py-2 bg-white/50 hover:bg-white/80 border border-white/40 rounded-lg text-sm text-gray-700 transition-colors">Logout</a>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Mobile Header -->
            <div class="md:hidden flex items-center justify-between px-4 py-3 bg-white/60 backdrop-blur-xl border-b border-white/50 sticky top-0 z-30">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-indigo-600">
                        <path d="M12 2.25a.75.75 0 01.75.75v3.42l8.805 4.929a.75.75 0 01.372.639v2.247a.75.75 0 01-1.077.677l-8.1-3.771v4.832l2.67 2a.75.75 0 01.3.6v1.944a.75.75 0 01-1.15.632L12 19.52l-2.42 1.706a.75.75 0 01-1.15-.632v-1.944a.75.75 0 01.3-.6l2.67-2v-4.832l-8.1 3.771A.75.75 0 012.223 14.23v-2.247a.75.75 0 01.372-.639L11.4 6.42V3a.75.75 0 01.6-.75z" />
                    </svg>
                    <span class="text-lg font-bold text-gray-800 tracking-tight">E-TKI</span>
                </div>
                <div class="flex items-center gap-2">
                    <livewire:notification-bell />
                    <button @click="sidebarOpen = true" class="p-2 text-gray-600 bg-white/50 rounded-xl hover:bg-white/80 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <main class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:px-8 z-10">
                <div class="max-w-7xl mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <div x-data="{ 
            show: false, 
            message: '', 
            type: 'success',
            init() {
                window.addEventListener('notify', event => {
                    this.message = event.detail.message;
                    this.type = event.detail.type || 'success';
                    this.show = true;
                    setTimeout(() => this.show = false, 3000);
                });
            }
        }" 
        class="fixed bottom-4 right-4 z-[60] flex flex-col gap-2 pointer-events-none">
        
        <div x-show="show" 
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white/80 backdrop-blur-xl shadow-lg ring-1 ring-black ring-opacity-5"
             x-cloak>
            <div class="p-4 flex items-center">
                <div class="flex-shrink-0">
                    <svg x-show="type === 'success'" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg x-show="type === 'error'" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p x-text="message" class="text-sm font-medium text-gray-900"></p>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
