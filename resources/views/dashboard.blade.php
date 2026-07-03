<x-layouts.app>
    <h2 class="text-3xl font-bold text-mono-900 mb-6 transition-colors">Dashboard</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-panel p-6 rounded-2xl border-t-4 border-indigo-500">
            <h3 class="text-sm font-semibold text-mono-500 uppercase tracking-wider transition-colors">Total TKI</h3>
            <p class="mt-2 text-3xl font-bold text-mono-900 transition-colors">{{ \App\Models\Tki::count() }}</p>
        </div>
        
        <div class="glass-panel p-6 rounded-2xl border-t-4 border-success-500">
            <h3 class="text-sm font-semibold text-mono-500 uppercase tracking-wider transition-colors">Approved Visa</h3>
            <p class="mt-2 text-3xl font-bold text-mono-900 transition-colors">{{ \App\Models\Tki::where('visa_status', 'Approved')->count() }}</p>
        </div>
        
        <div class="glass-panel p-6 rounded-2xl border-t-4 border-warning-500">
            <h3 class="text-sm font-semibold text-mono-500 uppercase tracking-wider transition-colors">Pending Dokumen</h3>
            <p class="mt-2 text-3xl font-bold text-mono-900 transition-colors">{{ \App\Models\Tki::whereNull('visa_status')->orWhere('visa_status', 'Pending')->count() }}</p>
        </div>
    </div>

    <div class="mt-8 flex justify-end">
        <a href="{{ route('registration') }}">
            <x-liquid-button variant="default">
                Buka Form Registrasi
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </x-liquid-button>
        </a>
    </div>
</x-layouts.app>
