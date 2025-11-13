<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use App\Models\FoundItem;
use Carbon\Carbon;

class DashboardStats extends Component
{
    public function render()
    {
        $today = Carbon::today();

        return view('livewire.dashboard-stats', [
            // Patrol Stats
            'patrolsToday' => Activity::whereDate('started_at', $today)->count(),
            'activeNow'    => Activity::whereNull('ended_at')->count(),
            
            // Found Item Stats
            'itemsToday'   => FoundItem::whereDate('found_date', $today)->count(),
            
            // Recent Activity Feed (Last 5 logs from anyone)
            'recentLogs'   => Activity::with(['user', 'room'])
                                      ->whereNotNull('ended_at')
                                      ->latest('ended_at')
                                      ->take(5)
                                      ->get(),
        ]);
    }
}