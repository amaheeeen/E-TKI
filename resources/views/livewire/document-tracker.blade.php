<div class="max-w-7xl mx-auto p-6 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h2 class="text-2xl font-bold text-mono-800">Pelacak Dokumen TKI</h2>
        <div class="w-full md:w-2/3 flex flex-col md:flex-row gap-4 items-center justify-end">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari Nama / Paspor TKI..." class="w-full md:w-2/3 bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-lg placeholder:text-mono-400 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900">
            <div class="flex items-center space-x-2 shrink-0 bg-white/60 backdrop-blur-md rounded-xl border border-mono-300 px-3 py-1.5">
                <label class="text-sm font-semibold text-gray-700">Per Page:</label>
                <select wire:model.live="perPage" class="bg-transparent border-none text-sm focus:ring-0 cursor-pointer">
                    <option value="1">1</option>
                    <option value="10">10</option>
                    <option value="100">100</option>
                    <option value="1000">1000</option>
                </select>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div x-data="{ expandedTki: null }" class="space-y-4">
        @forelse($tkis as $tki)
            <div class="bg-white/70 backdrop-blur-xl border border-white/50 shadow-lg rounded-xl overflow-hidden transition-all duration-300">
                <!-- Master Row -->
                <div class="p-4 flex flex-col md:flex-row md:items-center justify-between gap-4 cursor-pointer hover:bg-white/40" @click="expandedTki = expandedTki === {{ $tki->id }} ? null : {{ $tki->id }}">
                    <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                        <div wire:click.stop="sortByField('nama')" class="cursor-pointer hover:text-indigo-600 transition-colors">
                            <span class="text-xs font-semibold text-mono-500 block uppercase tracking-wider">Nama TKI @if($sortBy === 'nama') {!! $sortDirection === 'asc' ? '&uarr;' : '&darr;' !!} @endif</span>
                            <span class="text-lg font-bold text-mono-900">{{ $tki->full_name }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-mono-500 block uppercase tracking-wider">Paspor</span>
                            <span class="text-base font-semibold text-mono-700">{{ $tki->passport_number ?: 'N/A' }}</span>
                        </div>
                        <div wire:click.stop="sortByField('negara_tujuan')" class="cursor-pointer hover:text-indigo-600 transition-colors">
                            <span class="text-xs font-semibold text-mono-500 block uppercase tracking-wider">Tujuan @if($sortBy === 'negara_tujuan') {!! $sortDirection === 'asc' ? '&uarr;' : '&darr;' !!} @endif</span>
                            <span class="text-base font-semibold text-mono-700">{{ $tki->destination_country ?: 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $tki->physicalDocuments->count() }} Dokumen
                            </span>
                        </div>
                    </div>
                    <div>
                        <button class="px-4 py-2 bg-mono-100 hover:bg-mono-200 text-mono-800 font-semibold rounded-lg text-sm transition-colors border border-mono-200">
                            <span x-show="expandedTki !== {{ $tki->id }}">Kelola Dokumen</span>
                            <span x-show="expandedTki === {{ $tki->id }}" x-cloak>Tutup</span>
                        </button>
                    </div>
                </div>

                <!-- Detail Area (Documents) -->
                <div x-show="expandedTki === {{ $tki->id }}" x-collapse x-cloak>
                    <div class="p-4 bg-white/40 border-t border-white/50">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-bold text-mono-800">Daftar Dokumen Fisik</h4>
                            <button wire:click.stop="openAddModal({{ $tki->id }})" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow-md transition-colors">
                                + Tambah Dokumen
                            </button>
                        </div>
                        
                        @if($tki->physicalDocuments->count() > 0)
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                @foreach($tki->physicalDocuments as $doc)
                                    <div class="bg-white/80 border border-mono-200 rounded-xl p-4 shadow-sm">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h5 class="font-bold text-mono-900 text-lg">{{ $doc->document_type }}</h5>
                                                <span class="inline-flex items-center gap-1 mt-1 text-sm font-medium text-mono-600">
                                                    <svg class="w-4 h-4 text-mono-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    Lokasi: <span class="font-bold">{{ $doc->current_location }}</span>
                                                </span>
                                            </div>
                                            <div class="flex flex-col sm:flex-row gap-2 mt-2 sm:mt-0">
                                                @if($doc->file_path)
                                                    <button x-data 
                                                            @click="$dispatch('open-viewer', { 
                                                                url: '{{ asset('storage/' . $doc->file_path) }}', 
                                                                ext: '{{ strtolower(pathinfo($doc->file_path, PATHINFO_EXTENSION)) }}' 
                                                            })" 
                                                            class="px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-800 font-semibold rounded-lg text-xs border border-blue-200 transition-colors text-center">
                                                        Lihat Bukti
                                                    </button>
                                                @endif
                                                <button wire:click.stop="editDocument({{ $doc->id }})" class="px-3 py-1.5 bg-mono-100 hover:bg-mono-200 text-mono-800 font-semibold rounded-lg text-xs border border-mono-200 transition-colors">
                                                    Edit
                                                </button>
                                                <button wire:click.stop="openTransferModal({{ $doc->id }})" class="px-3 py-1.5 bg-warning-100 hover:bg-warning-200 text-warning-800 font-semibold rounded-lg text-xs border border-warning-200 transition-colors">
                                                    Pindah Tangan
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Audit Trail -->
                                        <div class="mt-4 pt-3 border-t border-mono-200">
                                            <h6 class="text-xs font-semibold text-mono-500 uppercase tracking-wider mb-2">Riwayat Perjalanan (Chain of Custody)</h6>
                                            <div class="space-y-3">
                                                @forelse($doc->auditLogs as $log)
                                                    <div class="flex gap-3">
                                                        <div class="mt-1">
                                                            <div class="w-2 h-2 rounded-full bg-indigo-500 mt-1.5"></div>
                                                        </div>
                                                        <div class="flex-1">
                                                            <p class="text-sm font-medium text-mono-800">{{ $log->action_taken }}</p>
                                                            <div class="text-xs text-mono-500 flex items-center gap-2 mt-0.5">
                                                                <span>Oleh: {{ $log->user?->name ?? 'System' }}</span>
                                                                <span>&bull;</span>
                                                                <span>{{ $log->created_at->format('d M Y, H:i') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-xs text-mono-500 italic">Belum ada riwayat dokumen.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 bg-white/50 rounded-xl border border-dashed border-mono-300">
                                <p class="text-mono-500 font-medium">Belum ada dokumen yang terdaftar untuk TKI ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white/70 backdrop-blur-xl border border-white/50 shadow-lg rounded-2xl">
                <p class="text-mono-500 font-medium text-lg">Tidak ada data TKI ditemukan.</p>
            </div>
        @endforelse

        <div class="pt-4">
            {{ $tkis->links() }}
        </div>
    </div>

    <!-- Modals (Outside Alpine Loop) -->

    <!-- Add Document Modal -->
    <div x-data="{ show: @entangle('showAddModal') }" x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm">
        <div @click.away="show = false" class="bg-white rounded-2xl shadow-xl w-full max-w-[95%] md:max-w-md p-6 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Tambah Dokumen</h3>
            <form wire:submit.prevent="saveDocument">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Dokumen</label>
                        <select wire:model.live="document_type" class="w-full bg-white/60 border border-gray-300 rounded-xl px-4 py-3.5 text-lg">
                            <option value="">-- Pilih Jenis Dokumen --</option>
                            <option value="Paspor">Paspor</option>
                            <option value="KTP">KTP</option>
                            <option value="Kartu Keluarga">Kartu Keluarga</option>
                            <option value="Akte Kelahiran">Akte Kelahiran</option>
                            <option value="Buku Nikah">Buku Nikah</option>
                            <option value="Ijazah">Ijazah</option>
                            <option value="Lainnya">Lainnya...</option>
                        </select>
                        @error('document_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    @if($document_type === 'Lainnya')
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sebutkan Dokumen</label>
                        <input type="text" wire:model="custom_document_type" class="w-full bg-white/60 border border-gray-300 rounded-xl px-4 py-3.5 text-lg" placeholder="Misal: Surat Izin Suami">
                        @error('custom_document_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bukti Fisik (Opsional)</label>
                        <input type="file" wire:model="proof_file" class="w-full bg-white/60 border border-gray-300 rounded-xl px-4 py-3.5 text-lg">
                        <div wire:loading wire:target="proof_file" class="text-sm text-indigo-600 mt-1">Mengunggah...</div>
                        @error('proof_file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button type="button" @click="show = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md">Simpan Dokumen</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Document Modal -->
    <div x-data="{ show: @entangle('showEditModal') }" x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm">
        <div @click.away="show = false" class="bg-white rounded-2xl shadow-xl w-full max-w-[95%] md:max-w-md p-6 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Edit Dokumen</h3>
            <form wire:submit.prevent="updateDocument">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Dokumen</label>
                        <select wire:model.live="document_type" class="w-full bg-white/60 border border-gray-300 rounded-xl px-4 py-3.5 text-lg">
                            <option value="">-- Pilih Jenis Dokumen --</option>
                            <option value="Paspor">Paspor</option>
                            <option value="KTP">KTP</option>
                            <option value="Kartu Keluarga">Kartu Keluarga</option>
                            <option value="Akte Kelahiran">Akte Kelahiran</option>
                            <option value="Buku Nikah">Buku Nikah</option>
                            <option value="Ijazah">Ijazah</option>
                            <option value="Lainnya">Lainnya...</option>
                        </select>
                        @error('document_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    @if($document_type === 'Lainnya')
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sebutkan Dokumen</label>
                        <input type="text" wire:model="custom_document_type" class="w-full bg-white/60 border border-gray-300 rounded-xl px-4 py-3.5 text-lg" placeholder="Misal: Surat Izin Suami">
                        @error('custom_document_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Bukti Fisik Baru (Opsional)</label>
                        <input type="file" wire:model="proof_file" class="w-full bg-white/60 border border-gray-300 rounded-xl px-4 py-3.5 text-lg">
                        <div wire:loading wire:target="proof_file" class="text-sm text-indigo-600 mt-1">Mengunggah...</div>
                        @error('proof_file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button type="button" @click="show = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md">Update Dokumen</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Transfer Modal -->
    <div x-data="{ show: @entangle('showTransferModal') }" x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm">
        <div @click.away="show = false" class="bg-white rounded-2xl shadow-xl w-full max-w-[95%] md:max-w-md p-6 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Pindah Tangan Dokumen</h3>
            <form wire:submit.prevent="submitTransfer">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lokasi/Status Baru</label>
                        <select wire:model="newLocation" class="w-full bg-white/60 border border-gray-300 rounded-xl px-4 py-3.5 text-lg">
                            <option value="">-- Pilih Lokasi --</option>
                            <option value="Ada di Kantor">Ada di Kantor</option>
                            <option value="Ada di Mamah (Dirut)">Ada di Mamah (Dirut)</option>
                            <option value="Ada di Imigrasi">Ada di Imigrasi</option>
                            <option value="Ada di Sponsor/TKI yang bersangkutan">Ada di Sponsor/TKI yang bersangkutan</option>
                        </select>
                        @error('newLocation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button type="button" @click="show = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-warning-500 hover:bg-warning-600 text-white font-semibold rounded-xl shadow-md">Pindahkan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alpine.js Global Viewer Modal -->
    <div x-data="{ open: false, url: '', ext: '' }" 
         @open-viewer.window="url = $event.detail.url; ext = $event.detail.ext; open = true" 
         x-show="open" 
         x-cloak 
         class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/60 backdrop-blur-md"
         style="display: none;">

        <div @click.away="open = false" class="relative bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl w-full max-w-[95%] md:max-w-2xl lg:max-w-5xl border border-white/50 overflow-hidden flex flex-col">
            <div class="flex justify-between items-center p-4 border-b border-gray-200/50">
                <h3 class="text-lg font-bold text-gray-800">Pratinjau Dokumen</h3>
                <button @click="open = false" class="text-gray-500 hover:text-red-600 bg-gray-100 hover:bg-red-50 p-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-4 bg-gray-50/50 h-[60vh] md:h-[75vh] w-full flex items-center justify-center overflow-auto">
                <template x-if="['jpg', 'jpeg', 'png'].includes(ext.toLowerCase())">
                    <img :src="url" class="max-h-full max-w-full object-contain rounded shadow-sm border border-gray-200">
                </template>
                <template x-if="ext.toLowerCase() === 'pdf'">
                    <iframe :src="url" class="w-full h-full rounded shadow-sm border border-gray-200"></iframe>
                </template>
                <template x-if="!['jpg', 'jpeg', 'png', 'pdf'].includes(ext.toLowerCase()) && url !== ''">
                    <div class="text-center text-gray-500">
                        <p>Format dokumen ini tidak mendukung pratinjau langsung.</p>
                        <a :href="url" target="_blank" class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Unduh Dokumen</a>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
