<div class="max-w-4xl mx-auto">
    <h2 class="text-3xl font-bold text-gray-900 mb-6 transition-colors">Profil Saya</h2>

    <div class="glass-panel p-8 rounded-2xl">
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="mb-6 p-4 rounded-xl bg-success-500/10 border border-success-500/20 text-success-700 flex items-center gap-3">
                <svg class="w-5 h-5 text-success-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="updateProfile" class="space-y-6" enctype="multipart/form-data">
            <div class="flex items-center gap-6 mb-8">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full overflow-hidden border border-white/50 shadow-inner flex items-center justify-center bg-gray-100/50 shrink-0">
                    @if ($avatar)
                        <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover rounded-full">
                    @elseif (auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}" class="w-full h-full object-cover rounded-full">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-indigo-500 font-bold text-3xl">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                    <input type="file" wire:model="avatar" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all cursor-pointer">
                    <p class="mt-1 text-xs text-gray-500 italic">*masukkan file jpg, png untuk foto</p>
                    @error('avatar') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 transition-colors">Nama Lengkap</label>
                    <input type="text" placeholder="Masukkan nama lengkap" wire:model="name" class="w-full bg-white/50 border border-gray-300 rounded-xl px-4 py-3 text-base text-gray-900 placeholder:text-gray-400 :text-gray-500 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 transition-colors">Email</label>
                    <input type="email" placeholder="contoh@email.com" wire:model="email" class="w-full bg-white/50 border border-gray-300 rounded-xl px-4 py-3 text-base text-gray-900 placeholder:text-gray-400 :text-gray-500 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 transition-colors">Nomor Telepon</label>
                    <input type="text" placeholder="08xxxxxxxxxx" wire:model="phone_number" class="w-full bg-white/50 border border-gray-300 rounded-xl px-4 py-3 text-base text-gray-900 placeholder:text-gray-400 :text-gray-500 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                    @error('phone_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1 transition-colors">Bio</label>
                    <textarea wire:model="bio" rows="3" placeholder="Tuliskan bio singkat..." class="w-full bg-white/50 border border-gray-300 rounded-xl px-4 py-3 text-base text-gray-900 placeholder:text-gray-400 :text-gray-500 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors"></textarea>
                    @error('bio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <hr class="border-gray-200 transition-colors">
            
            <h3 class="text-lg font-semibold text-gray-800 transition-colors">Ubah Password</h3>
            <p class="text-sm text-gray-500 mb-4 transition-colors">Kosongkan jika tidak ingin mengubah password.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 transition-colors">Password Baru</label>
                    <input type="password" placeholder="••••••••" wire:model="new_password" class="w-full bg-white/50 border border-gray-300 rounded-xl px-4 py-3 text-base text-gray-900 placeholder:text-gray-400 :text-gray-500 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                    @error('new_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 transition-colors">Konfirmasi Password Baru</label>
                    <input type="password" placeholder="••••••••" wire:model="new_password_confirmation" class="w-full bg-white/50 border border-gray-300 rounded-xl px-4 py-3 text-base text-gray-900 placeholder:text-gray-400 :text-gray-500 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200 mt-6 transition-colors">
                <x-liquid-button type="submit" variant="default">
                    Simpan Perubahan
                </x-liquid-button>
            </div>
        </form>
    </div>
</div>
