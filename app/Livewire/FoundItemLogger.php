<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;
use App\Models\FoundItem;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads; // 1. Import this
use Illuminate\Support\Facades\Storage; // <--- ADD THIS LINE

class FoundItemLogger extends Component
{
    use WithFileUploads; // 2. Use the trait
    public $room_id;
    public $item_name;
    public $found_date;
    public $item_image; // 3. Add the property

    // Modal & Edit Properties
    public $isDetailOpen = false;
    public $isEditing = false; // New: Toggles Edit Mode
    public $selectedItem = null;

    // Temporary Edit Variables
    public $edit_name;
    public $edit_room_id;
    public $edit_date;
    public $edit_image;

    public function mount()
    {
        // Default to today's date
        $this->found_date = now()->format('Y-m-d');
    }

    public function openDetail($id)
    {
        $this->selectedItem = FoundItem::with(['room', 'user'])->find($id);
        $this->isDetailOpen = true;
        $this->isEditing = false; // Always start in Read-Only mode
        $this->reset(['edit_image']); // Clear any previous uploads
    }

    public function closeDetail()
    {
        $this->isDetailOpen = false;
        $this->selectedItem = null;
    }

    public function toggleEdit()
    {
        // Load current data into edit variables
        $this->edit_name = $this->selectedItem->item_name;
        $this->edit_room_id = $this->selectedItem->room_id;
        $this->edit_date = $this->selectedItem->found_date->format('Y-m-d');
        $this->isEditing = true;
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->reset(['edit_image']);
    }

    public function updateItem()
    {
        $this->validate([
            'edit_name' => 'required|string|max:255',
            'edit_room_id' => 'required|exists:rooms,id',
            'edit_date' => 'required|date',
            'edit_image' => 'nullable|image|max:10240',
        ]);

        $data = [
            'item_name' => $this->edit_name,
            'room_id' => $this->edit_room_id,
            'found_date' => $this->edit_date,
        ];

        // Handle Image Update
        if ($this->edit_image) {
            // Delete old image if exists
            if ($this->selectedItem->item_image_path) {
                Storage::disk('public')->delete($this->selectedItem->item_image_path);
            }
            $data['item_image_path'] = $this->edit_image->store('found-items', 'public');
        }

        $this->selectedItem->update($data);
        
        $this->isEditing = false;
        $this->reset(['edit_image']);
        session()->flash('message', 'Item updated successfully.');
    }

    public function deleteItem()
    {
        if ($this->selectedItem->item_image_path) {
            Storage::disk('public')->delete($this->selectedItem->item_image_path);
        }
        
        $this->selectedItem->delete();
        $this->closeDetail();
        session()->flash('message', 'Item deleted successfully.');
    }

    public function save()
    {
        $this->validate([
            'room_id' => 'required|exists:rooms,id',
            'item_name' => 'required|string|max:255',
            'found_date' => 'required|date',
            'item_image'    => 'nullable|image|max:2048', // 4. Validate image
        ]);

        $imagePath = null;
        if ($this->item_image) {
            $imagePath = $this->item_image->store('found-items', 'public');
        }

        FoundItem::create([
            'user_id' => Auth::id(),
            'room_id' => $this->room_id,
            'item_name' => $this->item_name,
            'found_date' => $this->found_date,
            'item_image_path' => $imagePath, // 6. Save path
        ]);

        // Reset inputs
        $this->reset(['room_id', 'item_name', 'item_image']);
        
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