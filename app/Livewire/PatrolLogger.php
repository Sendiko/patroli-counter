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

    // Modal & Edit Properties
    public $isDetailOpen = false;
    public $isEditing = false;
    public $selectedActivity = null;

    // Edit Buffers
    public $edit_room_id;
    public $edit_type;
    public $edit_note;
    public $edit_started_at;
    public $edit_ended_at;
    public $edit_image; // For new uploads

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

    public function openDetail($id)
    {
        $this->selectedActivity = Activity::with(['room', 'user'])->find($id);
        $this->isDetailOpen = true;
        $this->isEditing = false;
        $this->reset(['edit_image']);
    }

    public function closeDetail()
    {
        $this->isDetailOpen = false;
        $this->selectedActivity = null;
        $this->isEditing = false;
    }

    public function toggleEdit()
    {
        $this->edit_room_id = $this->selectedActivity->room_id;
        $this->edit_type = $this->selectedActivity->type->value;
        $this->edit_note = $this->selectedActivity->note;
        // Format for datetime-local input (Y-m-d\TH:i)
        $this->edit_started_at = $this->selectedActivity->started_at->format('Y-m-d\TH:i');
        $this->edit_ended_at = $this->selectedActivity->ended_at ? $this->selectedActivity->ended_at->format('Y-m-d\TH:i') : null;
        
        $this->isEditing = true;
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->reset(['edit_image']);
    }

    public function updateActivity()
    {
        $this->validate([
            'edit_room_id' => 'required|exists:rooms,id',
            'edit_type' => 'required',
            'edit_note' => 'required|string|min:5',
            'edit_started_at' => 'required|date',
            'edit_ended_at' => 'required|date|after:edit_started_at',
            'edit_image' => 'nullable|image|max:10240',
        ]);

        $data = [
            'room_id' => $this->edit_room_id,
            'type' => $this->edit_type,
            'note' => $this->edit_note,
            'started_at' => $this->edit_started_at,
            'ended_at' => $this->edit_ended_at,
        ];

        if ($this->edit_image) {
            // Delete old image if exists
            if ($this->selectedActivity->proof_image_path) {
                Storage::disk('public')->delete($this->selectedActivity->proof_image_path);
            }
            $data['proof_image_path'] = $this->edit_image->store('activity-proofs', 'public');
        }

        $this->selectedActivity->update($data);

        $this->isEditing = false;
        $this->reset(['edit_image']);
        session()->flash('message', 'Log updated successfully.');
    }

    public function deleteActivity()
    {
        if ($this->selectedActivity->proof_image_path) {
            Storage::disk('public')->delete($this->selectedActivity->proof_image_path);
        }

        $this->selectedActivity->delete();
        $this->closeDetail();
        session()->flash('message', 'Log deleted successfully.');
    }

    public function render()
    {
        // Get the list of IDs this user is allowed to see
        $viewableIds = Auth::user()->getViewableUserIds();

        return view('livewire.patrol-logger', [
            'rooms' => Room::all(),
            'types' => ActivityType::cases(),
            
            // UPDATE THIS QUERY:
            'history' => Activity::whereIn('user_id', $viewableIds) // <--- Changed from where()
                                ->with('user') // Load user name to see WHO did it
                                ->whereNotNull('ended_at')
                                ->latest('started_at')
                                ->paginate(5) 
        ]);
    }
}
