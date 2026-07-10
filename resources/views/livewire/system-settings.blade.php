<div class="max-w-5xl mx-auto space-y-8">
    <h2 class="text-3xl font-bold text-gray-900 mb-6 transition-colors">Pengaturan Sistem</h2>

    <!-- User Approval Section -->
    <div class="glass-panel p-8 rounded-2xl">
        <h3 class="text-xl font-bold text-gray-800 mb-4 transition-colors">Verifikasi Pengguna Baru</h3>
        
        @if (session()->has('user_message'))
            <div class="mb-4 p-3 rounded-lg bg-success-500/10 text-success-700 text-sm border border-success-500/20">
                {{ session('user_message') }}
            </div>
        @endif
        @if (session()->has('user_error'))
            <div class="mb-4 p-3 rounded-lg bg-danger-500/10 text-danger-700 text-sm border border-danger-500/20">
                {{ session('user_error') }}
            </div>
        @endif

        <div class="w-full overflow-x-auto rounded-xl shadow-sm hide-scrollbar">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50 transition-colors">
                    <tr>
                        <th class="px-2 md:px-4 py-3 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider transition-colors">Nama</th>
                        <th class="px-2 md:px-4 py-3 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider transition-colors">Email</th>
                        <th class="px-2 md:px-4 py-3 text-left text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider transition-colors">Peran</th>
                        <th class="px-2 md:px-4 py-3 text-right text-xs md:text-sm font-medium text-gray-500 uppercase tracking-wider transition-colors">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pendingUsers as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 transition-colors">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 transition-colors">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 transition-colors">{{ $user->roles->pluck('name')->join(', ') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-liquid-button variant="success" wire:click="approveUser({{ $user->id }})" class="mr-2">
                                    Approve
                                </x-liquid-button>
                                <x-liquid-button variant="destructive" wire:click="rejectUser({{ $user->id }})" wire:confirm="Yakin ingin menolak dan menghapus user ini?">
                                    Reject
                                </x-liquid-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center italic transition-colors">
                                Tidak ada pendaftaran pengguna baru yang menunggu verifikasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- General Settings -->
    <div class="glass-panel p-8 rounded-2xl">
        <h3 class="text-xl font-bold text-gray-800 mb-4 transition-colors">Pengaturan Umum</h3>
        
        @if (session()->has('settings_message'))
            <div class="mb-4 p-3 rounded-lg bg-success-500/10 text-success-700 text-sm border border-success-500/20">
                {{ session('settings_message') }}
            </div>
        @endif

        <form wire:submit.prevent="saveSettings" class="space-y-6 max-w-2xl">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 transition-colors">Nama Perusahaan (Company Name)</label>
                <input type="text" placeholder="Masukkan nama perusahaan" wire:model="company_name" class="w-full bg-white/50 border border-gray-300 rounded-xl px-4 py-3 text-sm md:text-base text-gray-900 placeholder:text-xs md:placeholder:text-sm placeholder:text-gray-400 :text-gray-500 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 transition-colors">Bahasa Default OCR</label>
                <select wire:model="ocr_language" class="w-full bg-white/50 border border-gray-300 rounded-xl px-4 py-3 text-sm md:text-base text-gray-900 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                    <option value="id">Indonesia</option>
                    <option value="en">English</option>
                </select>
            </div>

            <div class="pt-4 border-t border-gray-200 ">
                <x-liquid-button type="submit" variant="default">
                    Simpan Pengaturan
                </x-liquid-button>
            </div>
        </form>
    </div>

    <!-- Data Security Management (Backup) -->
    <div class="glass-panel p-8 rounded-2xl">
        <h3 class="text-xl font-bold text-gray-800 mb-4 transition-colors">Manajemen Keamanan Data</h3>
        


        <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
            <button wire:click="runManualBackup" 
                    wire:loading.attr="disabled"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                <span wire:loading.remove wire:target="runManualBackup">Eksekusi Backup Sekarang</span>
                <span wire:loading wire:target="runManualBackup">Memproses Arsip...</span>
            </button>
        </div>
        <p class="mt-4 text-sm text-gray-500">
            Sistem secara otomatis melakukan pencadangan database dan dokumen fisik setiap hari pada tengah malam.
        </p>
    </div>
</div>
