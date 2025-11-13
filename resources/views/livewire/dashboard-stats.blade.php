<div wire:poll.10s class="space-y-8">
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6 shadow-sm flex items-center">
            <div class="p-3 rounded-lg bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Active Now</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $activeNow }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6 shadow-sm flex items-center">
            <div class="p-3 rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Patrols Today</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $patrolsToday }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-6 shadow-sm flex items-center">
            <div class="p-3 rounded-lg bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Items Found Today</p>
                <p class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $itemsToday }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-800">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Laboratory Activity Feed</h3>
        </div>
        <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
            @forelse($recentLogs as $log)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-xs font-bold text-zinc-600 dark:text-zinc-300">
                            {{ substr($log->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white">
                                {{ $log->user->name }} <span class="text-zinc-500 font-normal">completed</span> {{ $log->type->label() }}
                            </p>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                Room {{ $log->room->code }}
                            </p>
                        </div>
                    </div>
                    <span class="text-xs text-zinc-400">{{ $log->ended_at->diffForHumans() }}</span>
                </div>
            @empty
                <div class="p-6 text-center text-zinc-500 dark:text-zinc-400">No recent activity recorded.</div>
            @endforelse
        </div>
    </div>

</div>