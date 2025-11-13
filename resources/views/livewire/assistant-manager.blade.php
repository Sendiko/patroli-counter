<div class="max-w-6xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-white">My Assistants</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Manage your laboratory team.</p>
        </div>
        <button wire:click="openAssignModal" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-zinc-900 hover:bg-zinc-800 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200 transition">
            + Assign Assistant
        </button>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-900/20 p-4 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
            <thead class="bg-zinc-50 dark:bg-zinc-900/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-800">
                @forelse($myAssistants as $assistant)
                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-300 mr-3">
                                    {{ substr($assistant->name, 0, 1) }}
                                </div>
                                <div class="text-sm font-medium text-zinc-900 dark:text-white">{{ $assistant->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $assistant->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="unassign({{ $assistant->id }})" 
                                    wire:confirm="Remove {{ $assistant->name }} from your team? Their account will NOT be deleted." 
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                Remove from Team
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-sm text-zinc-500 dark:text-zinc-400">
                            No assistants assigned yet. Click "Assign Assistant" to add one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0">
            <div class="absolute inset-0 bg-zinc-900/75 dark:bg-zinc-900/80 transition-opacity" wire:click="closeModal"></div>

            <div class="relative bg-white dark:bg-zinc-900 rounded-xl shadow-xl transform transition-all sm:w-full sm:max-w-lg border border-zinc-200 dark:border-zinc-800 flex flex-col">
                
                <div class="px-4 py-5 sm:px-6 border-b border-zinc-100 dark:border-zinc-800">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-white">
                        Assign Assistant
                    </h3>
                    <p class="text-sm text-zinc-500 mt-1">Select a registered user to join your laboratory.</p>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Select User</label>
                        
                        @if($availableUsers->count() > 0)
                            <select wire:model="selected_user_id" class="block w-full rounded-lg border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5">
                                <option value="">-- Choose a User --</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('selected_user_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        @else
                            <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 text-yellow-800 dark:text-yellow-200 rounded-lg text-sm">
                                No available assistants found. Users must register first and have the role 'assistant'.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="px-4 py-4 bg-zinc-50 dark:bg-zinc-800/50 rounded-b-xl flex justify-end gap-2">
                    <button type="button" wire:click="closeModal" class="px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700">
                        Cancel
                    </button>
                    @if($availableUsers->count() > 0)
                        <button type="button" wire:click="assignUser" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                            Assign Selected
                        </button>
                    @endif
                </div>

            </div>
        </div>
    @endif
</div>