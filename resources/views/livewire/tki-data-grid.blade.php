<div class="p-6 bg-white shadow rounded-lg border border-transparent transition-colors">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800 transition-colors">TKI Data Grid</h2>
        <div class="flex space-x-2">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search TKIs..." class="bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-colors">
            <button wire:click="exportXlsx" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">Export to XLSX</button>
        </div>
    </div>

    <div class="mb-4 p-4 border border-gray-200 rounded bg-gray-50 flex items-center space-x-4 transition-colors">
        <input type="file" wire:model="importFile" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
        <button wire:click="importData" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition" wire:loading.attr="disabled">Import CSV/XLSX</button>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="relative overflow-x-auto rounded-lg">
        <div wire:loading class="absolute inset-0 z-10 bg-mono-200/50 backdrop-blur-sm flex flex-col justify-center items-center rounded-lg space-y-4">
            <div class="w-full h-12 bg-mono-300/50 animate-pulse rounded"></div>
            <div class="w-full h-12 bg-mono-300/50 animate-pulse rounded"></div>
            <div class="w-full h-12 bg-mono-300/50 animate-pulse rounded"></div>
        </div>
        
        <div x-show="$wire.selectedRows.length > 0" x-cloak class="mb-4 p-3 bg-white/80 border border-mono-200 rounded-xl flex items-center gap-3">
            <span class="text-sm font-semibold text-mono-700">Tindakan Massal:</span>
            <button wire:click="bulkVerify('Active')" class="px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-800 font-bold rounded-lg text-sm border border-green-200 transition-colors">
                Verifikasi Terpilih (Active)
            </button>
            <button wire:click="bulkVerify('Rejected')" class="px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-800 font-bold rounded-lg text-sm border border-red-200 transition-colors">
                Tolak Terpilih (Rejected)
            </button>
        </div>

        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 transition-colors">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider transition-colors w-10">
                        <input type="checkbox" wire:model.live="selectAll" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer">
                    </th>
                    <th wire:click="sortBy('full_name')" class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider cursor-pointer transition-colors">Name</th>
                    <th wire:click="sortBy('gender')" class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider cursor-pointer transition-colors">Gender</th>
                    <th wire:click="sortBy('sponsor_id')" class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider cursor-pointer transition-colors">Sponsor</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider transition-colors">Passport</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider transition-colors">Destination</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider transition-colors">Status</th>
                    <th class="px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider transition-colors">Verifikasi</th>
                    @hasanyrole('Super Admin|Operational Admin')
                    <th class="px-6 py-3 text-right font-medium text-gray-500 uppercase tracking-wider transition-colors">Actions</th>
                    @endhasanyrole
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 transition-colors">
                @foreach ($tkis as $tki)
                    <tr class="{{ $tki->verification_status === 'Pending' ? 'bg-warning-50 hover:bg-warning-100' : 'hover:bg-gray-50' }} transition-colors text-gray-900">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($tki->verification_status === 'Pending')
                                <input type="checkbox" wire:model.live="selectedRows" value="{{ $tki->id }}" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer">
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $tki->full_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $tki->gender }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $tki->sponsor?->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $tki->passport_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $tki->destination_country }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $tki->visa_status ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $tki->visa_status ?? 'No Visa' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($tki->verification_status === 'Pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-bold">Pending</span>
                            @elseif($tki->verification_status === 'Active')
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-bold">Active</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full font-bold">{{ $tki->verification_status }}</span>
                            @endif
                        </td>
                        @hasanyrole('Super Admin|Operational Admin')
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            @if($tki->verification_status === 'Pending')
                                <button wire:click="verifyTki({{ $tki->id }}, 'Active')" class="text-green-600 hover:text-green-900 bg-green-50 px-2 py-1 rounded">Verify</button>
                                <button wire:click="verifyTki({{ $tki->id }}, 'Rejected')" class="text-red-600 hover:text-red-900 bg-red-50 px-2 py-1 rounded">Reject</button>
                            @endif
                            
                            @if($tki->verification_status === 'Active')
                                <a href="{{ route('tki.cv', $tki->id) }}" target="_blank" class="inline-flex items-center px-2 py-1 text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded text-sm font-semibold transition-colors">Cetak CV</a>
                            @endif

                            <button wire:click="editTki({{ $tki->id }})" class="text-blue-600 hover:text-blue-900 bg-blue-50 px-2 py-1 rounded">Edit</button>
                            @hasrole('Super Admin')
                            <button wire:click="deleteTki({{ $tki->id }})" wire:confirm="Are you sure you want to delete this record permanently?" class="text-red-600 hover:text-red-900 bg-red-50 px-2 py-1 rounded">Delete</button>
                            @endhasrole
                        </td>
                        @endhasanyrole
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $tkis->links() }}
    </div>

    <!-- Edit Modal -->
    <div x-data="{ show: @entangle('showEditModal') }" x-show="show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm" style="display: none;">
        <div @click.away="show = false" class="bg-white rounded-2xl shadow-xl w-full max-w-4xl p-6 border border-gray-200 overflow-y-auto max-h-[90vh]">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Edit TKI Data</h3>
            <form wire:submit.prevent="updateTki">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Full Name</label>
                        <input type="text" wire:model="editData.full_name" class="w-full bg-white/60 border border-gray-300 rounded-xl px-4 py-3.5 text-lg">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Passport Number</label>
                        <input type="text" wire:model="editData.passport_number" class="w-full bg-white/60 border border-gray-300 rounded-xl px-4 py-3.5 text-lg">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Destination</label>
                        <input type="text" wire:model="editData.destination_country" class="w-full bg-white/60 border border-gray-300 rounded-xl px-4 py-3.5 text-lg">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-gray-700">Gender</label>
                        <select wire:model="editData.gender" class="w-full bg-white/60 border border-gray-300 rounded-xl px-4 py-3.5 text-lg">
                            <option value="L">L</option><option value="P">P</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold text-gray-700">Notes</label>
                        <textarea wire:model="editData.notes" class="w-full bg-white/60 border border-gray-300 rounded-xl px-4 py-3.5 text-lg"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t pt-4 mt-6">
                    <button type="button" @click="show = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold rounded-xl">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
