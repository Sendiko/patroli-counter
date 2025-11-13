<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Room Management</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Manage laboratory rooms and codes.</p>
        </div>
        <button wire:click="create"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition">
            + Add Room
        </button>
    </div>

    @if (session()->has('message'))
        <div
            class="mb-6 rounded-lg bg-green-50 dark:bg-green-900/20 p-4 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    <div
        class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
            <thead class="bg-zinc-50 dark:bg-zinc-900/50">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        Code</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        Name</th>
                    <th scope="col"
                        class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                        Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-800">
                @forelse($rooms as $room)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300">
                                {{ $room->code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-300">
                            {{ $room->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="edit({{ $room->id }})"
                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</button>
                            <button wire:click="delete({{ $room->id }})"
                                wire:confirm="Are you sure you want to delete {{ $room->code }}?"
                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            No rooms found. Click "Add Room" to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0">

            <div class="absolute inset-0 bg-zinc-900/75 dark:bg-zinc-900/80 transition-opacity" wire:click="closeModal">
            </div>

            <div
                class="relative bg-white dark:bg-zinc-900 rounded-xl shadow-xl transform transition-all sm:w-full sm:max-w-lg border border-zinc-200 dark:border-zinc-800 flex flex-col max-h-[90vh]">

                <div class="px-4 py-5 sm:px-6 border-b border-zinc-100 dark:border-zinc-800">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-white">
                        {{ $room_id ? 'Edit Room' : 'Create New Room' }}
                    </h3>
                </div>

                <div class="p-6 overflow-y-auto">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Room
                                Code</label>
                            <input type="text" wire:model="code" placeholder="e.g. D1"
                                class="px-2 py-1 uppercase block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('code')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Room
                                Name</label>
                            <input type="text" wire:model="name" placeholder="e.g. Chemical Lab"
                                class="px-2 py-1 block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="px-4 py-4 sm:px-6 bg-zinc-50 dark:bg-zinc-800/50 rounded-b-xl flex justify-end gap-2">
                    <button type="button" wire:click="closeModal"
                        class="inline-flex justify-center rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 focus:outline-none">
                        Cancel
                    </button>
                    <button type="button" wire:click="store"
                        class="inline-flex justify-center rounded-lg border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none">
                        Save Room
                    </button>
                </div>

            </div>
        </div>
    @endif
</div>
