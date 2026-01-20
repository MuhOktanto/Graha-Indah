<?php

namespace App\Http\Livewire\Dashboard\Admin\Room;

use App\Models\Facility;
use App\Models\Room;
use App\Models\RoomHasFacility;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $name;
    public $type;
    public $total_rooms;
    public $description;
    public $price;
    public $explanation;
    public $image;
    public array $selectedFacilities;
    public $facilities;

    public function mount()
    {
        $this->facilities = Facility::where('type', 'room')->get();
        $this->selectedFacilities = array_fill_keys($this->facilities->pluck('code')->toArray(), false);
    }

    public function render()
    {
        return view('livewire.dashboard.admin.room.create')->layoutData(['title' => 'New Room | Graha Indah']);
    }

    public function store()
    {
        $typeOptions = ['kos', 'villa'];

        $validatedData = $this->validate([
            'name' => ['required'],
            'type' => ['required', 'in:' . implode(',', $typeOptions)],
            'description' => ['required'],
            'total_rooms' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'explanation' => ['required'],
            'image' => ['required', 'image', 'max:2084'],
        ]);

        // Upload image
        $validatedData['image'] = $this->image->store('img/rooms', 'public');

        // Tambahkan data pelengkap
        $validatedData['code'] = bin2hex(random_bytes(20));
        $validatedData['available'] = $this->total_rooms;

        // Simpan room
        $room = Room::create($validatedData);

        // Simpan fasilitas
        if (count(array_filter($this->selectedFacilities))) {
            foreach (array_filter($this->selectedFacilities) as $facility) {
                RoomHasFacility::create([
                    'room_id' => $room->id,
                    'facility_code' => $facility
                ]);
            }
        }

        // Reset dan notifikasi
        $this->dispatchBrowserEvent('room:created');
        $this->resetAll();
    }

    public function resetAll()
    {
        $this->reset([
            'name', 'type', 'total_rooms', 'description', 'price',
            'explanation', 'image'
        ]);
        $this->selectedFacilities = array_fill_keys($this->facilities->pluck('code')->toArray(), false);
    }
}
