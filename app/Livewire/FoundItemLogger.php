<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;
use App\Models\FoundItem;
use Illuminate\Support\Facades\Auth;

class FoundItemLogger extends Component
{
    public $room_id;
    public $item_name;
    public $found_date;

    public function mount()
    {
        // Default to today's date
        $this->found_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate([
            'room_id' => 'required|exists:rooms,id',
            'item_name' => 'required|string|max:255',
            'found_date' => 'required|date',
        ]);

        FoundItem::create([
            'user_id' => Auth::id(),
            'room_id' => $this->room_id,
            'item_name' => $this->item_name,
            'found_date' => $this->found_date,
        ]);

        // Reset inputs except date (usually stays today)
        $this->reset(['room_id', 'item_name']);
        
        session()->flash('message', 'Item recorded successfully.');
    }

    public function render()
    {
        return view('livewire.found-item-logger', [
            'rooms' => Room::all(),
            // Show last 5 items found by this user
            'recentItems' => FoundItem::where('user_id', Auth::id())
                                      ->latest()
                                      ->take(5)
                                      ->get()
        ]);
    }
}