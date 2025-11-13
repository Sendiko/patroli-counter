<div class="max-w-2xl mx-auto">

    <div
        class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm p-6 space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">Patrol Logger</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Log your daily laboratory activities.</p>
            </div>

            @if ($currentActivity)
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200 border border-amber-200 dark:border-amber-800">
                    <svg class="mr-1.5 h-2 w-2 text-amber-500" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3" />
                    </svg>
                    In Progress
                </span>
            @else
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700">
                    <svg class="mr-1.5 h-2 w-2 text-zinc-400" fill="currentColor" viewBox="0 0 8 8">
                        <circle cx="4" cy="4" r="3" />
                    </svg>
                    Ready
                </span>
            @endif
        </div>

        <hr class="border-zinc-200 dark:border-zinc-800">

        @if (session()->has('message'))
            <div class="rounded-lg bg-green-50 dark:bg-green-900/20 p-4 border border-green-200 dark:border-green-800">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (!$currentActivity)
            <form wire:submit.prevent="startActivity" class="space-y-5">

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Select Room</label>
                    <select wire:model="room_id"
                        class="px-2 py-1 block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                        <option value="">-- Choose Room --</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->code }} - {{ $room->name }}</option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Activity Type</label>
                    <select wire:model="type"
                        class="px-2 py-1 block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                        <option value="">-- Choose Activity --</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->value }}">{{ $type->label() }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Start Activity
                    </button>
                </div>
            </form>
        @else
            <div
                class="rounded-lg bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800/50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-amber-800 dark:text-amber-200">
                            Current Task: {{ ucfirst($currentActivity->type->value) }}
                        </h3>
                        <div class="mt-1 text-sm text-amber-700 dark:text-amber-300">
                            <p>Started at {{ $currentActivity->started_at->format('H:i') }} in
                                {{ $currentActivity->room->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <form wire:submit.prevent="finishActivity" class="space-y-5">

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Activity
                        Notes</label>
                    <textarea wire:model="note" rows="4"
                        class="px-2 py-1 block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm placeholder-zinc-400"
                        placeholder="Describe what you did..."></textarea>
                    @error('note')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Proof of Activity
                        (Optional)</label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-zinc-300 dark:border-zinc-700 border-dashed rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors relative">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-zinc-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48" aria-hidden="true">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-zinc-600 dark:text-zinc-400 justify-center">
                                <label for="file-upload"
                                    class="relative cursor-pointer bg-transparent rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none">
                                    <span>Upload a file</span>
                                    <input class="px-2 py-1" id="file-upload" wire:model="proof_image" type="file"
                                        class="sr-only">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-zinc-500 dark:text-zinc-500">PNG, JPG up to 2MB</p>
                        </div>
                    </div>
                    @error('proof_image')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    @if ($proof_image)
                        <div class="mt-4">
                            <p class="text-xs font-medium text-zinc-500 mb-2">Preview:</p>
                            <img src="{{ $proof_image->temporaryUrl() }}"
                                class="h-24 w-24 object-cover rounded-lg border border-zinc-200 dark:border-zinc-700">
                        </div>
                    @endif
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        Check Out & Save
                    </button>
                </div>
            </form>
        @endif

    </div>

    <div class="space-y-4 mt-6">
        <h3 class="text-sm font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider ml-1">My Recent History
        </h3>

        <div
            class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm overflow-hidden">
            <ul role="list" class="divide-y divide-zinc-200 dark:divide-zinc-800">
                @forelse($history as $log)
                    <li wire:click="openDetail({{ $log->id }})"
                        class="p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition cursor-pointer group">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <span
                                    class="inline-flex items-center justify-center h-10 w-10 rounded-full 
                                    {{ $log->type->value === 'maintenance'
                                        ? 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400'
                                        : ($log->type->value === 'inspection'
                                            ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400'
                                            : 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400') }}">

                                    @if ($log->type->value === 'maintenance')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                        </svg>
                                    @elseif($log->type->value === 'inspection')
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    @endif
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p
                                    class="text-sm font-medium text-zinc-900 dark:text-white truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                                    {{ $log->type->label() }}
                                </p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 truncate">
                                    {{ $log->room->code }} - {{ $log->room->name }}
                                </p>
                                <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-1">
                                    {{ Str::limit($log->note, 60) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-zinc-900 dark:text-white">
                                    {{ $log->started_at->format('M d') }}
                                </div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ $log->started_at->format('H:i') }} - {{ $log->ended_at->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="p-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
                        No completed activities found yet.
                    </li>
                @endforelse
            </ul>

            @if ($history->hasPages())
                <div class="bg-zinc-50 dark:bg-zinc-800/50 px-4 py-3 border-t border-zinc-200 dark:border-zinc-800">
                    {{ $history->links(data: ['scrollTo' => false]) }}
                </div>
            @endif
        </div>
    </div>
    @if ($isDetailOpen && $selectedActivity)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0">
            <div class="absolute inset-0 bg-zinc-900/75 dark:bg-zinc-900/80 transition-opacity"
                wire:click="closeDetail"></div>

            <div
                class="relative bg-white dark:bg-zinc-900 rounded-xl shadow-xl transform transition-all sm:w-full sm:max-w-lg border border-zinc-200 dark:border-zinc-800 flex flex-col max-h-[90vh]">

                <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-800 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">
                        {{ $isEditing ? 'Edit Patrol Log' : 'Patrol Details' }}
                    </h3>
                    <button wire:click="closeDetail"
                        class="text-zinc-400 hover:text-zinc-500 dark:hover:text-zinc-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto space-y-6">

                    @if (!$isEditing)
                        @if ($selectedActivity->proof_image_path)
                            <div class="rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700">
                                <img src="{{ Storage::url($selectedActivity->proof_image_path) }}"
                                    class="w-full h-64 object-cover bg-zinc-100 dark:bg-zinc-800">
                            </div>
                        @endif

                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Activity
                                        Type</label>
                                    <p class="text-base font-semibold text-zinc-900 dark:text-white mt-1">
                                        {{ $selectedActivity->type->label() }}</p>
                                </div>
                                <div>
                                    <label
                                        class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Location</label>
                                    <p class="text-base font-medium text-zinc-900 dark:text-white mt-1">Room
                                        {{ $selectedActivity->room->code }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Time
                                    Log</label>
                                <div class="mt-1 flex items-center gap-2 text-zinc-900 dark:text-white">
                                    <span>{{ $selectedActivity->started_at->format('M d, H:i') }}</span>
                                    <span class="text-zinc-400">&rarr;</span>
                                    <span>{{ $selectedActivity->ended_at->format('H:i') }}</span>
                                    <span
                                        class="text-xs text-zinc-500 ml-2">({{ $selectedActivity->ended_at->diffInMinutes($selectedActivity->started_at) }}
                                        mins)</span>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase">Note</label>
                                <div
                                    class="mt-1 p-3 bg-zinc-50 dark:bg-zinc-800/50 rounded-lg border border-zinc-100 dark:border-zinc-800 text-sm text-zinc-700 dark:text-zinc-300">
                                    {{ $selectedActivity->note }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Location</label>
                                <select wire:model="edit_room_id"
                                    class="block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                    @foreach ($rooms as $room)
                                        <option value="{{ $room->id }}">{{ $room->code }} -
                                            {{ $room->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Activity
                                    Type</label>
                                <select wire:model="edit_type"
                                    class="block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                    @foreach ($types as $type)
                                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Started
                                        At</label>
                                    <input type="datetime-local" wire:model="edit_started_at"
                                        class="block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Ended
                                        At</label>
                                    <input type="datetime-local" wire:model="edit_ended_at"
                                        class="block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Note</label>
                                <textarea wire:model="edit_note" rows="3"
                                    class="block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Update
                                    Photo</label>
                                <input type="file" wire:model="edit_image"
                                    class="block w-full text-sm text-zinc-500 dark:text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900 dark:file:text-indigo-300">
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
                        <button wire:click="deleteActivity"
                            wire:confirm="Are you sure? This log will be permanently deleted."
                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium px-2">
                            Delete Log
                        </button>
                        <div class="flex gap-2">
                            <button wire:click="closeDetail"
                                class="px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                Close
                            </button>
                            <button wire:click="toggleEdit"
                                class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                                Edit Log
                            </button>
                        </div>
                    @else
                        <div></div>
                        <div class="flex gap-2">
                            <button wire:click="cancelEdit"
                                class="px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700">
                                Cancel
                            </button>
                            <button wire:click="updateActivity"
                                class="px-4 py-2 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700">
                                Save Changes
                            </button>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    @endif
</div>
