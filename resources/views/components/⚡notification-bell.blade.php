<?php

use Livewire\Component;

new class extends Component
{
    public $unreadCount = 0;

    public function mount()
    {
        $this->unreadCount = auth()->user() ? auth()->user()->unreadNotifications->count() : 0;
    }

    public function getNotificationsProperty()
    {
        return auth()->user() ? auth()->user()->notifications()->take(5)->get() : collect([]);
    }

    public function markAsRead()
    {
        if (auth()->user()) {
            auth()->user()->unreadNotifications->markAsRead();
            $this->unreadCount = 0;
        }
    }
};
?>

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open; if(open) { $wire.markAsRead() }" class="p-2 text-gray-600 bg-white/50 rounded-xl hover:bg-white/80 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span x-show="$wire.unreadCount > 0" class="absolute top-1 right-1 flex h-3 w-3" x-cloak>
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white"></span>
        </span>
    </button>

    <div x-show="open" @click.away="open = false" x-transition.opacity class="absolute {{ $attributes->get('position', 'right-0 mt-2') }} w-72 bg-white/80 backdrop-blur-xl border border-white/50 shadow-2xl rounded-2xl overflow-hidden z-50 text-left" x-cloak>
        <div class="p-4 border-b border-white/50 font-semibold text-gray-800 flex justify-between items-center">
            <span>Notifications</span>
            <span x-show="$wire.unreadCount > 0" class="text-xs bg-red-500 text-white px-2 py-0.5 rounded-full" x-text="$wire.unreadCount"></span>
        </div>
        <div class="max-h-64 overflow-y-auto">
            @forelse($this->notifications as $notification)
                <div class="px-4 py-3 border-b border-gray-100/50 text-sm {{ empty($notification->read_at) ? 'bg-indigo-50/50 font-semibold text-gray-900' : 'text-gray-600' }}">
                    {{ $notification->data['message'] ?? 'Notifikasi sistem' }}
                    <div class="text-xs text-gray-400 mt-1 font-normal">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            @empty
                <div class="p-4 text-sm text-gray-500 text-center italic">Belum ada notifikasi</div>
            @endforelse
        </div>
    </div>
</div>