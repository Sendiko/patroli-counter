<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use App\Models\FoundItem;
use App\Models\User; // Import User
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardStats extends Component
{
    // Filter Property (For Laboran only)
    public $filter_user_id = '';

    public function render()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // 1. Determine WHOSE data we are looking at
        if ($user->isLaboran()) {
            // If Laboran selected a specific user in the dropdown, use that ID
            if ($this->filter_user_id) {
                $targetUserIds = [$this->filter_user_id];
            } else {
                // Otherwise, show data for the WHOLE TEAM (Laboran + Assistants)
                $targetUserIds = $user->getViewableUserIds();
            }
        } else {
            // Assistants can ONLY see themselves
            $targetUserIds = [$user->id];
        }

        // 2. Fetch Assistants List (For the Dropdown - Laboran Only)
        $myAssistants = $user->isLaboran() 
            ? User::where('laboran_id', $user->id)->orderBy('name')->get() 
            : collect();

        return view('livewire.dashboard-stats', [
            
            // Pass the list for the dropdown
            'myAssistants' => $myAssistants,

            // STATS (Filtered by $targetUserIds)
            'patrolsToday' => Activity::whereIn('user_id', $targetUserIds)
                                      ->whereDate('started_at', $today)
                                      ->count(),

            'activeNow'    => Activity::whereIn('user_id', $targetUserIds)
                                      ->whereNull('ended_at')
                                      ->count(),
            
            'itemsToday'   => FoundItem::whereIn('user_id', $targetUserIds)
                                       ->whereDate('found_date', $today)
                                       ->count(),
            
            // RECENT LOGS (Filtered)
            'recentLogs'   => Activity::with(['user', 'room'])
                                      ->whereIn('user_id', $targetUserIds)
                                      ->whereNotNull('ended_at')
                                      ->latest('ended_at')
                                      ->take(5)
                                      ->get(),
        ]);
    }
}