<?php

namespace App\Http\Livewire\Room;

use App\Models\Room;
use Livewire\Component;
use Illuminate\Http\Request;

class Index extends Component
{
    public $rooms;
    public $type;

    public function mount(Request $request)
    {
        $this->type = $request->query('type'); // Ambil dari URL ?type=villa

        $query = Room::query();

        if (in_array($this->type, ['villa', 'kos'])) {
            $query->where('type', $this->type);
        }

        $this->rooms = $query->orderBy('created_at', 'desc')->with(['reviews', 'reservations'])->get();
    }

    public function render()
    {
        return view('livewire.room.index')->layout('layouts.main', ['title' => 'Rooms | Graha Indah']);
    }
}

