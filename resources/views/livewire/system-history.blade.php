<div class="max-w-7xl mx-auto p-6 space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-mono-800">System History / Audit Trail</h2>
    </div>

    <!-- Filter Section (Glassmorphism Card) -->
    <div class="bg-white/70 backdrop-blur-xl border border-white/50 shadow-lg rounded-2xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-mono-700 mb-1.5">Search User / Subject</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search..." class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-lg placeholder:text-mono-400 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900">
            </div>
            <div>
                <label class="block text-sm font-semibold text-mono-700 mb-1.5">Start Date</label>
                <input type="date" wire:model.live="dateFrom" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900">
            </div>
            <div>
                <label class="block text-sm font-semibold text-mono-700 mb-1.5">End Date</label>
                <input type="date" wire:model.live="dateTo" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900">
            </div>
            <div>
                <label class="block text-sm font-semibold text-mono-700 mb-1.5">Action Type</label>
                <select wire:model.live="actionFilter" class="w-full bg-white/60 border border-mono-300 rounded-xl px-4 py-3.5 text-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all duration-300 backdrop-blur-sm text-mono-900">
                    <option value="">All Actions</option>
                    <option value="created">Created</option>
                    <option value="updated">Updated</option>
                    <option value="deleted">Deleted</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white/70 backdrop-blur-xl border border-white/50 shadow-lg rounded-2xl overflow-hidden">
        <table class="min-w-full divide-y divide-mono-200">
            <thead class="bg-mono-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-bold text-mono-600 tracking-wider">Waktu</th>
                    <th class="px-6 py-4 text-left text-sm font-bold text-mono-600 tracking-wider">Pengguna</th>
                    <th class="px-6 py-4 text-left text-sm font-bold text-mono-600 tracking-wider">Aktivitas</th>
                    <th class="px-6 py-4 text-left text-sm font-bold text-mono-600 tracking-wider">Target</th>
                    <th class="px-6 py-4 text-left text-sm font-bold text-mono-600 tracking-wider">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-mono-100 bg-transparent">
                @forelse ($activities as $activity)
                    <tr x-data="{ showChanges: false }" class="hover:bg-white/40 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-mono-700">
                            {{ $activity->created_at->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($activity->causer)
                                <div class="font-semibold text-mono-900">{{ $activity->causer->name }}</div>
                                <div class="text-xs text-mono-500">{{ $activity->causer->roles->pluck('name')->join(', ') }}</div>
                            @else
                                <span class="text-mono-400 italic">System</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                                $actionName = strtolower($activity->event ?? $activity->description);
                                $colorClass = 'bg-gray-100 text-gray-800';
                                if (str_contains($actionName, 'created')) $colorClass = 'bg-green-100 text-green-800';
                                elseif (str_contains($actionName, 'updated')) $colorClass = 'bg-blue-100 text-blue-800';
                                elseif (str_contains($actionName, 'deleted')) $colorClass = 'bg-red-100 text-red-800';
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                {{ ucfirst($actionName) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-mono-700">
                            {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                        </td>
                        <td class="px-6 py-4 text-sm text-mono-700 w-1/3">
                            <div class="flex flex-col gap-2">
                                <span class="text-xs">{{ $activity->description }}</span>
                                
                                @if($activity->properties && count($activity->properties) > 0)
                                    <button @click="showChanges = !showChanges" class="text-indigo-600 hover:text-indigo-900 text-xs font-semibold flex items-center gap-1 self-start">
                                        <svg class="w-4 h-4 transition-transform duration-200" :class="showChanges ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        Lihat Perubahan
                                    </button>
                                    
                                    <div x-show="showChanges" x-collapse class="mt-2 text-xs bg-white/50 p-3 rounded-lg border border-mono-200 w-full overflow-x-auto">
                                        @if(isset($activity->properties['old']) || isset($activity->properties['attributes']))
                                            <div class="grid grid-cols-2 gap-4">
                                                @if(isset($activity->properties['old']))
                                                <div>
                                                    <span class="font-bold text-red-600 block mb-1">Old:</span>
                                                    <pre class="whitespace-pre-wrap">{{ json_encode($activity->properties['old'], JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                                @endif
                                                @if(isset($activity->properties['attributes']))
                                                <div>
                                                    <span class="font-bold text-green-600 block mb-1">New:</span>
                                                    <pre class="whitespace-pre-wrap">{{ json_encode($activity->properties['attributes'], JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                                @endif
                                            </div>
                                        @else
                                            <pre class="whitespace-pre-wrap">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-mono-500 font-medium">
                            No history records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-4 border-t border-mono-200">
            {{ $activities->links() }}
        </div>
    </div>
</div>
