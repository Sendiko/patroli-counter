<div class="max-w-2xl mx-auto space-y-8">
    
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm p-6">
        
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Found Item Log</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Record items left behind in the laboratory.</p>
        </div>

        @if (session()->has('message'))
            <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-900/20 p-4 border border-green-200 dark:border-green-800 flex items-center">
                <svg class="h-5 w-5 text-green-400 mr-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-5">
            
            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Item Name / Description</label>
                <input type="text" wire:model="item_name" placeholder="e.g. Blue Water Bottle, Calculator..." 
                       class="px-2 py-1 block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                @error('item_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Where was it found?</label>
                    <select wire:model="room_id" class="px-2 py-1 block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                        <option value="">-- Select Room --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->code }} - {{ $room->name }}</option>
                        @endforeach
                    </select>
                    @error('room_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Date Found</label>
                    <input type="date" wire:model="found_date" 
                           class="px-2 py-1 block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                    @error('found_date') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 focus:outline-none transition-colors">
                    Record Item
                </button>
            </div>
        </form>
    </div>

    <div class="space-y-4">
        <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider ml-1">Recently Recorded</h3>
        
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm overflow-hidden">
            @forelse($recentItems as $item)
                <div class="p-4 border-b border-zinc-100 dark:border-zinc-800 last:border-0 flex items-center justify-between hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                    <div class="flex items-start gap-3">
                        <div class="p-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">{{ $item->item_name }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Found in {{ $item->room->name }}</p>
                        </div>
                    </div>
                    <div class="text-xs font-medium text-zinc-400 dark:text-zinc-500">
                        {{ $item->found_date->format('M d, Y') }}
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-zinc-500 dark:text-zinc-400 text-sm">
                    No items recorded yet.
                </div>
            @endforelse
        </div>
    </div>
</div>