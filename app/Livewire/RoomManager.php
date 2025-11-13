<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Room;
use Illuminate\Validation\Rule;

class RoomManager extends Component
{
    public $rooms;
    public $code, $name, $room_id;
    public $isModalOpen = false;

    public function render()
    {
        $this->rooms = Room::orderBy('code')->get();
        return view('livewire.room-manager');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->code = '';
        $this->name = '';
        $this->room_id = null;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate([
            'code' => ['required', 'string', 'max:10', Rule::unique('rooms')->ignore($this->room_id)],
            'name' => 'required|string|max:255',
        ]);

        $data = [
            'code' => strtoupper($this->code),
            'name' => $this->name,
        ];

        if ($this->room_id) {
            // Update existing room
            Room::find($this->room_id)->update($data);
            session()->flash('message', 'Room updated successfully.');
        } else {
            // Create new room
            Room::create($data);
            session()->flash('message', 'Room created successfully.');
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $room = Room::findOrFail($id);
        $this->room_id = $id;
        $this->code = $room->code;
        $this->name = $room->name;
        $this->openModal();
    }

    public function delete($id)
    {
        Room::find($id)->delete();
        session()->flash('message', 'Room deleted.');
    }
}
