@php
    $totalTki = \App\Models\Tki::count();
    $approvedVisa = \App\Models\Tki::where('visa_status', 'Approved')->count();
    $pendingDocs = \App\Models\Tki::whereNull('visa_status')->orWhere('visa_status', 'Pending')->count();
    
    // As there is no passport expiry column, we use medical_date as a placeholder for expiring documents
    $expiringDocs = \App\Models\Tki::whereNotNull('medical_date')
        ->where('medical_date', '<=', now()->addDays(30))
        ->where('medical_date', '>=', now())
        ->get();
@endphp
<x-layouts.app>
    <h2 class="text-3xl font-bold text-mono-900 mb-6 transition-colors">Dashboard</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-panel p-6 rounded-2xl border-t-4 border-indigo-500">
            <h3 class="text-sm font-semibold text-mono-500 uppercase tracking-wider transition-colors">Total TKI</h3>
            <p class="mt-2 text-3xl font-bold text-mono-900 transition-colors">{{ $totalTki }}</p>
        </div>
        
        <div class="glass-panel p-6 rounded-2xl border-t-4 border-success-500">
            <h3 class="text-sm font-semibold text-mono-500 uppercase tracking-wider transition-colors">Approved Visa</h3>
            <p class="mt-2 text-3xl font-bold text-mono-900 transition-colors">{{ $approvedVisa }}</p>
        </div>
        
        <div class="glass-panel p-6 rounded-2xl border-t-4 border-warning-500">
            <h3 class="text-sm font-semibold text-mono-500 uppercase tracking-wider transition-colors">Pending Dokumen</h3>
            <p class="mt-2 text-3xl font-bold text-mono-900 transition-colors">{{ $pendingDocs }}</p>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 glass-panel p-6 rounded-2xl">
            <h3 class="text-xl font-bold text-mono-800 mb-4 transition-colors">Registrasi vs Keberangkatan (6 Bulan Terakhir)</h3>
            <div class="relative h-64 w-full">
                <canvas id="tkiChart"></canvas>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-2xl border-t-4 border-danger-500">
            <h3 class="text-xl font-bold text-mono-800 mb-4 flex items-center gap-2 transition-colors">
                <svg class="w-6 h-6 text-danger-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Peringatan Dokumen
            </h3>
            <ul class="space-y-4">
                @forelse($expiringDocs as $doc)
                <li class="flex flex-col border-b border-mono-200 pb-2">
                    <span class="font-semibold text-mono-800">{{ $doc->full_name }}</span>
                    <span class="text-sm text-danger-600 font-medium">Medical Check-up kedaluwarsa pada {{ \Carbon\Carbon::parse($doc->medical_date)->format('d M Y') }}</span>
                </li>
                @empty
                <li class="flex flex-col">
                    <span class="text-sm text-success-600 font-medium">Semua dokumen aman.</span>
                </li>
                @endforelse
            </ul>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('tkiChart');
            if(ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                        datasets: [
                            {
                                label: 'Pendaftar Baru',
                                data: [12, 19, 15, 25, 22, 30],
                                backgroundColor: 'rgba(79, 70, 229, 0.7)',
                                borderRadius: 6
                            },
                            {
                                label: 'Telah Berangkat',
                                data: [5, 10, 12, 18, 15, 20],
                                backgroundColor: 'rgba(34, 197, 94, 0.7)',
                                borderRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.05)' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-layouts.app>
