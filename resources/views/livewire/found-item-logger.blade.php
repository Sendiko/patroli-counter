<div class="max-w-2xl mx-auto space-y-8">

    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm p-6">

        <div class="mb-6">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Found Item Log</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Record items left behind in the laboratory.</p>
        </div>

        @if (session()->has('message'))
            <div
                class="mb-6 rounded-lg bg-green-50 dark:bg-green-900/20 p-4 border border-green-200 dark:border-green-800 flex items-center">
                <svg class="h-5 w-5 text-green-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-5">

            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Item Name /
                    Description</label>
                <input type="text" wire:model="item_name" placeholder="e.g. Blue Water Bottle, Calculator..."
                    class="px-2 py-1 block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                @error('item_name')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Where was it
                        found?</label>
                    <select wire:model="room_id"
                        class="px-2 py-1 block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                        <option value="">-- Select Room --</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->code }} - {{ $room->name }}</option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Date Found</label>
                    <input type="date" wire:model="found_date"
                        class="px-2 py-1 block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                    @error('found_date')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Photo (Optional)</label>
                <input type="file" wire:model="item_image"
                    class="block w-full text-sm text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300 transition">
                @error('item_image')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror

                @if ($item_image)
                    <div class="mt-3">
                        <p class="text-xs text-zinc-500 mb-1">Preview:</p>
                        <img src="{{ $item_image->temporaryUrl() }}"
                            class="w-24 h-24 object-cover rounded-lg border border-zinc-200 dark:border-zinc-700">
                    </div>
                @endif
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 focus:outline-none transition-colors">
                    Record Item
                </button>
            </div>
        </form>
    </div>

    @if ($isDetailOpen && $selectedItem)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0">

            <div class="absolute inset-0 bg-zinc-900/75 dark:bg-zinc-900/80 transition-opacity"
                wire:click="closeDetail"></div>

            <div
                class="relative bg-white dark:bg-zinc-900 rounded-xl shadow-xl transform transition-all sm:w-full sm:max-w-lg border border-zinc-200 dark:border-zinc-800 flex flex-col max-h-[90vh]">

                <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">
                        {{ $isEditing ? 'Edit Item' : 'Item Details' }}
                    </h3>
                    <button wire:click="closeDetail" class="text-zinc-400 hover:text-zinc-500 dark:hover:text-zinc-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto space-y-6">

                    @if (!$isEditing)
                        @if ($selectedItem->item_image_path)
                            <div class="rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700">
                                <img src="{{ Storage::url($selectedItem->item_image_path) }}"
                                    class="w-full h-64 object-cover bg-zinc-100 dark:bg-zinc-800">
                            </div>
                        @else
                            <div
                                class="w-full h-32 rounded-lg bg-zinc-50 dark:bg-zinc-800 flex flex-col items-center justify-center text-zinc-400 dark:text-zinc-500 border border-dashed border-zinc-300 dark:border-zinc-700">
                                <span class="text-sm">No photo available</span>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Item
                                    Name</label>
                                <p class="text-base font-semibold text-zinc-900 dark:text-white mt-1">
                                    {{ $selectedItem->item_name }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Date
                                    Found</label>
                                <p class="text-base font-medium text-zinc-900 dark:text-white mt-1">
                                    {{ $selectedItem->found_date->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Found
                                    By</label>
                                <p class="text-sm font-medium text-zinc-900 dark:text-white mt-1">
                                    {{ $selectedItem->user->name }}</p>
                            </div>
                            <div>
                                <label
                                    class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Location</label>
                                <div class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-300">
                                        Room {{ $selectedItem->room->code }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Item
                                    Name</label>
                                <input type="text" wire:model="edit_name"
                                    class="px-2 py-1 block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                @error('edit_name')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Location</label>
                                <select wire:model="edit_room_id"
                                    class="block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}">{{ $room->code }} - {{ $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('edit_room_id')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Date</label>
                                <input type="date" wire:model="edit_date"
                                    class="block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                @error('edit_date')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Change
                                    Photo</label>
                                <input type="file" wire:model="edit_image"
                                    class="block w-full text-sm text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300">
                                @error('edit_image')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror

                                @if ($edit_image)
                                    <div class="mt-2">
                                        <p class="text-xs text-zinc-500">New Preview:</p>
                                        <img src="{{ $edit_image->temporaryUrl() }}"
                                            class="w-20 h-20 object-cover rounded-lg border">
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 bg-zinc-50 dark:bg-zinc-800/50 rounded-b-xl flex justify-between items-center">

                    @if (!$isEditing)
                        <div>
                            <button wire:click="deleteItem"
                                wire:confirm="Are you sure you want to delete this item? This cannot be undone."
                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium px-2">
                                Delete Item
                            </button>
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="closeDetail"
                                class="px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                Close
                            </button>
                            <button wire:click="toggleEdit"
                                class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                                Edit Item
                            </button>
                        </div>
                    @else
                        <div></div>
                        <div class="flex gap-2">
                            <button wire:click="cancelEdit"
                                class="px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                Cancel
                            </button>
                            <button wire:click="updateItem"
                                class="px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700">
                                Save Changes
                            </button>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    @endif

    <div class="space-y-4">
        <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider ml-1">Recently
            Recorded
        </h3>

        <div
            class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm overflow-hidden">
            @forelse($recentItems as $item)
                <div wire:click="openDetail({{ $item->id }})"
                    class="p-4 border-b border-zinc-100 dark:border-zinc-800 last:border-0 flex items-center justify-between hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition cursor-pointer group">

                    <div class="flex items-start gap-3">
                        @if ($item->item_image_path)
                            <img src="{{ Storage::url($item->item_image_path) }}"
                                class="w-10 h-10 rounded-lg object-cover border border-zinc-200 dark:border-zinc-700 group-hover:opacity-80 transition">
                        @else
                            <div
                                class="p-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/30 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        @endif

                        <div>
                            <p
                                class="text-sm font-medium text-zinc-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                                {{ $item->item_name }}</p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">Found in {{ $item->room->name }}</p>
                        </div>
                    </div>
                    <div class="text-xs font-medium text-zinc-400 dark:text-zinc-500">
                        {{ $item->found_date->format('M d') }}
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
