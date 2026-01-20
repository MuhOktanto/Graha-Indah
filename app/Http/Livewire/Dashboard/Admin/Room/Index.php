<?php

namespace App\Http\Livewire\Dashboard\Admin\Room;

use App\Models\Room;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $selectedRoom;
    public $search = '';
    public $filterType = ''; // new: filter berdasarkan jenis

    protected $queryString = [
        'search' => ['except' => ''],
        'filterType' => ['except' => ''], // untuk menjaga query URL tetap sync
    ];

    public function mount()
    {
        $this->selectedRoom = Room::first();
    }

    public function render()
    {
        $query = Room::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        return view('livewire.dashboard.admin.room.index', [
            'rooms' => $query->latest()->paginate(10),
        ])->layoutData(['title' => 'Room Dashboard | Graha Indah']);
    }

    public function show(Room $room)
    {
        $this->selectedRoom = $room;
        $this->dispatchBrowserEvent('room:show');
    }

    public function delete(Room $room)
    {
        $this->selectedRoom = $room;
        $this->dispatchBrowserEvent('room:delete');
    }

    public function destroy()
    {
        if ($this->selectedRoom && $this->selectedRoom->image && Storage::disk('public')->exists($this->selectedRoom->image)) {
            Storage::disk('public')->delete($this->selectedRoom->image);
        }

        $this->selectedRoom->delete();
        $this->dispatchBrowserEvent('room:deleted');

        $this->selectedRoom = Room::first(); // reset selected room
    }

    public function cancel()
    {
        $this->selectedRoom = Room::first();
    }
}
