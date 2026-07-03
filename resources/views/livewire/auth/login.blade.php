<div class="glass-panel p-8 rounded-2xl w-full">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Masuk ke E-TKI</h2>
        <p class="text-sm text-gray-600 mt-2">Sistem Manajemen Operasional Terpadu</p>
    </div>

    <form wire:submit.prevent="login" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" wire:model="email" class="w-full bg-white/50 border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" wire:model="password" class="w-full bg-white/50 border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md transition transform hover:-translate-y-0.5">
            Masuk
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600">
        Belum punya akun? <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-semibold">Daftar sebagai Sponsor</a>
    </div>
</div>
