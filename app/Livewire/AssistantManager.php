<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AssistantManager extends Component
{
    public $myAssistants;       // Users assigned to me
    public $availableUsers;     // Users looking for a supervisor
    
    public $selected_user_id;   // The user selected in the dropdown
    public $isModalOpen = false;

    public function mount()
    {
        if (Auth::user()->role !== 'laboran') {
            abort(403, 'Only Laborans can manage assistants.');
        }
    }

    public function render()
    {
        // 1. Get My Team
        $this->myAssistants = User::where('laboran_id', Auth::id())
                                  ->orderBy('name')
                                  ->get();

        // 2. Get Available Assistants (Role is assistant, but No Laboran yet)
        $this->availableUsers = User::where('role', 'assistant')
                                    ->whereNull('laboran_id')
                                    ->orderBy('name')
                                    ->get();

        return view('livewire.assistant-manager');
    }

    public function openAssignModal()
    {
        $this->isModalOpen = true;
        $this->selected_user_id = null; // Reset selection
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selected_user_id = null;
    }

    public function assignUser()
    {
        $this->validate([
            'selected_user_id' => 'required|exists:users,id'
        ]);

        // Find the user and link them to ME
        $user = User::find($this->selected_user_id);
        
        // Double check they aren't already taken
        if($user->laboran_id !== null) {
            $this->addError('selected_user_id', 'This user was just taken by someone else.');
            return;
        }

        $user->update(['laboran_id' => Auth::id()]);

        session()->flash('message', $user->name . ' has been added to your team.');
        $this->closeModal();
    }

    public function unassign($id)
    {
        // Find user in MY team
        $user = User::where('laboran_id', Auth::id())->find($id);

        if ($user) {
            // Set laboran_id to NULL (Release them)
            $user->update(['laboran_id' => null]);
            session()->flash('message', 'Assistant removed from your team.');
        }
    }
}