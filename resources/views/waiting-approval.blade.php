<x-layouts.guest>
    <div class="glass-panel p-8 rounded-2xl w-full text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-warning-500/20 mb-6">
            <svg class="h-8 w-8 text-warning-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Menunggu Persetujuan</h2>
        <p class="text-sm text-gray-600 mb-8">
            Akun Anda sedang ditinjau. Silakan hubungi Super Admin untuk verifikasi agar dapat mengakses sistem.
        </p>

        <a href="{{ route('logout') }}" class="inline-block px-6 py-2 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white/50 hover:bg-white/80 transition">
            Keluar (Logout)
        </a>
    </div>
</x-layouts.guest>
