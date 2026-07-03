<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Operasional TKI' }}</title>

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
    <div class="flex-1 flex h-screen overflow-hidden transition-colors duration-500">
        
        <!-- Sidebar -->
        <aside class="w-64 flex-shrink-0 flex flex-col h-full z-20 bg-white/80 backdrop-blur-xl border-r border-mono-200 transition-colors duration-500 ease-in-out">
            <div class="p-6 border-b border-white/30">
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">E-TKI<span class="text-indigo-600">.</span></h1>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="sidebar-link block px-4 py-3 rounded-lg text-sm {{ request()->routeIs('dashboard') ? 'active text-mono-900 font-semibold' : 'text-mono-600 ' }}">Dashboard</a>
                <a href="{{ route('tki-data') }}" class="sidebar-link block px-4 py-3 rounded-lg text-sm {{ request()->routeIs('tki-data') ? 'active text-mono-900 font-semibold' : 'text-mono-600 ' }}">Data TKI</a>
                <a href="{{ route('document-tracker') }}" class="sidebar-link block px-4 py-3 rounded-lg text-sm {{ request()->routeIs('document-tracker') ? 'active text-mono-900 font-semibold' : 'text-mono-600 ' }}">Pelacak Dokumen</a>
                <a href="{{ route('registration') }}" class="sidebar-link block px-4 py-3 rounded-lg text-sm {{ request()->routeIs('registration') ? 'active text-mono-900 font-semibold' : 'text-mono-600 ' }}">Registrasi E-Form</a>
                <a href="{{ route('profile') }}" class="sidebar-link block px-4 py-3 rounded-lg text-sm {{ request()->routeIs('profile') ? 'active text-mono-900 font-semibold' : 'text-mono-600 ' }}">Profil Saya</a>
                
                @hasrole('Super Admin')
                <div class="pt-4 mt-4 border-t border-mono-200 "></div>
                <a href="{{ route('settings') }}" class="sidebar-link block px-4 py-3 rounded-lg text-sm {{ request()->routeIs('settings') ? 'active text-mono-900 font-semibold' : 'text-mono-600 ' }}">Pengaturan Sistem</a>
                <a href="{{ route('history-log') }}" class="sidebar-link block px-4 py-3 rounded-lg text-sm {{ request()->routeIs('history-log') ? 'active text-mono-900 font-semibold' : 'text-mono-600 ' }}">History Log</a>
                @endhasrole
            </nav>



            <div class="p-4 border-t border-white/30 ">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold transition-colors">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-semibold truncate transition-colors">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate transition-colors">{{ auth()->user()->roles->pluck('name')->join(', ') }}</p>
                    </div>
                </div>
                <a href="{{ route('logout') }}" class="block w-full text-center px-4 py-2 bg-white/50 hover:bg-white/80 border border-white/40 rounded-lg text-sm text-gray-700 transition-colors">Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-8 z-10">
            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
