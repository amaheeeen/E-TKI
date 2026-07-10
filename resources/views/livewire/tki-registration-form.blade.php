<div class="max-w-3xl mx-auto p-8 bg-white shadow-xl rounded-2xl mt-6 border border-gray-200 transition-colors">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 transition-colors">Register New TKI</h2>

    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-transition.opacity x-init="setTimeout(() => show = false, 5000)" class="fixed top-5 right-5 z-50 p-4 border border-success-500/30 rounded-xl bg-success-500/10 backdrop-blur-md text-success-900 shadow-lg shadow-success-500/10">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="font-medium text-sm">{{ session('success') }}</p>
                <button @click="show = false" class="ml-4 text-success-500 hover:text-success-900 font-bold">&times;</button>
            </div>
        </div>
    @endif

    <div class="mb-8 p-8 border-2 border-dashed border-mono-300 rounded-2xl bg-white/60 text-center relative transition-all duration-300 backdrop-blur-sm min-h-[120px] flex flex-col items-center justify-center">
        <label for="documentScan" class="block text-base font-semibold text-gray-700 mb-4 transition-colors">Upload Scan Passport/ID (Auto-fill via OCR)</label>
        <div wire:loading wire:target="documentScan" class="absolute inset-0 z-10 bg-white/75 backdrop-blur-sm flex items-center justify-center rounded-lg border-2 border-warning-500/50 shadow-[0_0_15px_rgba(245,158,11,0.2)]">
            <div class="flex flex-col items-center gap-2">
                <svg class="animate-spin h-6 w-6 text-warning-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-warning-600 font-semibold text-sm animate-pulse">AI is reading document...</span>
            </div>
        </div>
        <input type="file" wire:model="documentScan" id="documentScan" class="block w-full text-lg text-mono-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-lg file:font-semibold file:bg-mono-100 file:text-mono-700 :bg-mono-800 :text-mono-300 hover:file:bg-mono-200 :file:bg-mono-700 mx-auto transition-colors cursor-pointer">
        @error('documentScan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        <div x-data="{ show: false, message: '', type: 'success' }" 
             x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 5000)"
             x-show="show" 
             x-transition.opacity 
             class="fixed top-24 right-5 z-50 p-4 border rounded-xl backdrop-blur-md shadow-lg"
             :class="{
                'bg-success-500/10 border-success-500/30 text-success-900 shadow-success-500/10': type === 'success',
                'bg-danger-500/10 border-danger-500/30 text-danger-900 shadow-danger-500/10': type === 'danger'
             }">
            <div class="flex items-center gap-3">
                <svg x-show="type === 'success'" class="w-5 h-5 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <svg x-show="type === 'danger'" class="w-5 h-5 text-danger-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="font-medium text-sm" x-text="message"></p>
                <button @click="show = false" class="ml-4 font-bold" :class="type === 'success' ? 'text-success-500 hover:text-success-900' : 'text-danger-500 hover:text-danger-900'">&times;</button>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="submit" class="space-y-6">
        <h3 class="text-xl font-bold text-mono-800 mb-6 pb-2 border-b border-mono-200">Personal Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Tanggal Daftar</label>
                <input type="date" wire:model="tanggal_daftar" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                @error('tanggal_daftar') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Full Name</label>
                <input type="text" placeholder="Masukkan nama lengkap" wire:model="full_name" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base placeholder:text-xs md:placeholder:text-sm placeholder:text-mono-400 focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                @error('full_name') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Passport Number</label>
                <input type="text" placeholder="Cth: A1234567" wire:model="passport_number" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base placeholder:text-xs md:placeholder:text-sm placeholder:text-mono-400 focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                @error('passport_number') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Gender</label>
                <select wire:model="gender" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                    <option value="L">Laki-laki (L)</option>
                    <option value="P">Perempuan (P)</option>
                </select>
                @error('gender') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Place of Birth</label>
                <input type="text" placeholder="Kota kelahiran" wire:model="place_of_birth" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base placeholder:text-xs md:placeholder:text-sm placeholder:text-mono-400 focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                @error('place_of_birth') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Date of Birth</label>
                <input type="date" wire:model="date_of_birth" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                @error('date_of_birth') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Marital Status</label>
                <select wire:model="marital_status" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                    <option value="Single">Single</option>
                    <option value="Janda/Duda">Janda/Duda</option>
                    <option value="Menikah">Menikah</option>
                </select>
                @error('marital_status') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Address</label>
                <textarea wire:model="address" placeholder="Alamat lengkap" rows="2" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base placeholder:text-xs md:placeholder:text-sm placeholder:text-mono-400 focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 "></textarea>
                @error('address') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Mother's Name</label>
                <input type="text" placeholder="Nama Ibu" wire:model="mother_name" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base placeholder:text-xs md:placeholder:text-sm placeholder:text-mono-400 focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                @error('mother_name') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Spouse's Name (If Married)</label>
                <input type="text" placeholder="Nama Suami/Istri" wire:model="spouse_name" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base placeholder:text-xs md:placeholder:text-sm placeholder:text-mono-400 focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                @error('spouse_name') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Education</label>
                <select wire:model="education" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SMA">SMA</option>
                    <option value="Diploma">Diploma</option>
                    <option value="Strata">Strata</option>
                </select>
                @error('education') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Experience Type</label>
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 mt-3">
                    <label class="flex items-center gap-2 cursor-pointer text-sm md:text-base">
                        <input type="radio" wire:model="experience_type" value="NON" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        NON (Baru)
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm md:text-base">
                        <input type="radio" wire:model="experience_type" value="EX" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                        EX (Berpengalaman)
                    </label>
                </div>
                @error('experience_type') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Height (cm)</label>
                <input type="number" wire:model="height" placeholder="Cth: 160" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base placeholder:text-xs md:placeholder:text-sm placeholder:text-mono-400 focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                @error('height') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Weight (kg)</label>
                <input type="number" wire:model="weight" placeholder="Cth: 55" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base placeholder:text-xs md:placeholder:text-sm placeholder:text-mono-400 focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                @error('weight') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Destination Country</label>
                <input type="text" placeholder="Negara tujuan" wire:model="destination_country" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base placeholder:text-xs md:placeholder:text-sm placeholder:text-mono-400 focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                @error('destination_country') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Employer Name</label>
                <input type="text" placeholder="Nama majikan" wire:model="employer_name" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base placeholder:text-xs md:placeholder:text-sm placeholder:text-mono-400 focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                @error('employer_name') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Medical Date</label>
                <input type="date" wire:model="medical_date" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                @error('medical_date') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Visa Status</label>
                <input type="text" placeholder="Status Visa" wire:model="visa_status" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base placeholder:text-xs md:placeholder:text-sm placeholder:text-mono-400 focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                @error('visa_status') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Departure Date</label>
                <input type="date" wire:model="departure_date" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                @error('departure_date') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Sponsor</label>
                <select wire:model="sponsor_id" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 ">
                    <option value="">-- Select Sponsor --</option>
                    @foreach ($sponsors as $sponsor)
                        <option value="{{ $sponsor->id }}">{{ $sponsor->name }}</option>
                    @endforeach
                </select>
                @error('sponsor_id') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="text-sm font-semibold text-mono-700 mb-1.5 block">Notes</label>
                <textarea wire:model="notes" placeholder="Catatan tambahan" rows="2" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-sm md:text-base placeholder:text-xs md:placeholder:text-sm placeholder:text-mono-400 focus:ring-2 focus:ring-mono-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900 "></textarea>
                @error('notes') <span class="text-danger-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex justify-end pt-6 border-t border-gray-200 transition-colors">
            <x-liquid-button type="submit" variant="default">
                Submit Registration
            </x-liquid-button>
        </div>
    </form>

    <!-- Overwrite Confirmation Modal -->
    <div x-data="{ show: false }" 
         x-on:confirm-overwrite.window="show = true" 
         x-show="show" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm">
        <div @click.away="show = false" class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl w-full max-w-md p-6 border border-white/50 text-center">
            <div class="w-12 h-12 rounded-full bg-warning-100 text-warning-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Peringatan Duplikasi Data</h3>
            <p class="text-gray-600 text-sm mb-6">Data dengan identitas tersebut sudah ada di sistem. Apakah Anda ingin menimpa (overwrite) data lama?</p>
            <div class="flex justify-center gap-3">
                <button type="button" @click="show = false; $wire.set('existingTkiId', null)" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-xl transition-colors">Batal</button>
                <button type="button" @click="show = false; $wire.submit()" class="px-4 py-2 bg-warning-500 hover:bg-warning-600 text-white font-semibold rounded-xl shadow-md transition-colors">Ya, Timpa Data</button>
            </div>
        </div>
    </div>
</div>
