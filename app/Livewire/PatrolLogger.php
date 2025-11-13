<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads; // Required for image upload
use App\Models\Activity;
use App\Models\Room;
use App\Enums\ActivityType;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination; // 1. Import Pagination

class PatrolLogger extends Component
{
    use WithFileUploads;
    use WithPagination; // 2. Use the trait

    public $room_id;
    public $type;
    public $note;
    public $proof_image;
    public $currentActivity = null;

    public function mount()
    {
        $this->checkCurrentActivity();
    }

    public function checkCurrentActivity()
    {
        $this->currentActivity = Activity::where('user_id', Auth::id())
                                         ->whereNull('ended_at')
                                         ->first();
    }

    public function startActivity()
    {
        $this->validate([
            'room_id' => 'required|exists:rooms,id',
            'type' => 'required',
        ]);

        Activity::create([
            'user_id' => Auth::id(),
            'room_id' => $this->room_id,
            'type' => $this->type,
            'started_at' => now(),
        ]);

        $this->reset(['room_id', 'type']);
        $this->checkCurrentActivity(); // Refresh state
    }

    public function finishActivity()
    {
        $this->validate([
            'note' => 'required|string|min:5',
            'proof_image' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($this->proof_image) {
            $path = $this->proof_image->store('activity-proofs', 'public');
        }

        $this->currentActivity->update([
            'note' => $this->note,
            'proof_image_path' => $path,
            'ended_at' => now(),
        ]);

        $this->reset(['note', 'proof_image']);
        $this->checkCurrentActivity();
        
        session()->flash('message', 'Activity logged successfully!');
    }

public function render()
    {
        return view('livewire.patrol-logger', [
            'rooms' => Room::all(),
            'types' => ActivityType::cases(),
            
            // This is the missing part causing the error!
            'history' => Activity::where('user_id', Auth::id())
                                 ->whereNotNull('ended_at')
                                 ->latest('started_at')
                                 ->paginate(5) 
        ]);
    }
}
