<?php

namespace App\Http\Livewire\Dashboard\Admin\Room;

use App\Models\Facility;
use App\Models\Room;
use App\Models\RoomHasFacility;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $room;
    public $name;
    public $type;
    public $total_rooms;
    public $description;
    public $price;
    public $explanation;
    public $image;
    public $oldImage;
    public array $selectedFacilities;
    public $facilities;
    public $roomFacilities;

    public function render()
    {
        return view('livewire.dashboard.admin.room.edit')->layoutData(['title' => 'Edit Room | Graha Indah']);
    }

    public function mount(Room $room)
    {
        $this->room = $room;
        $this->roomFacilities = $room->facilities->pluck('facility_code')->toArray();
        $this->name = $room->name;
        $this->type = $room->type;
        $this->price = $room->price;
        $this->total_rooms = $room->total_rooms;
        $this->description = $room->description;
        $this->explanation = $room->explanation;
        $this->oldImage = $room->image;
        $this->facilities = Facility::where('type', 'room')->get();

        $facilities = [];
        foreach ($this->facilities->pluck('code') as $facility) {
            $facilities[$facility] = in_array($facility, $this->roomFacilities) ? $facility : false;
        }
        $this->selectedFacilities = $facilities;
    }

    public function update()
    {
        $typeOptions = ['kos', 'villa'];

        $rules = [
            'name' => ['required'],
            'type' => ['required', 'in:' . implode(',', $typeOptions)],
            'description' => ['required'],
            'total_rooms' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'explanation' => ['required'],
        ];

        if ($this->image) {
            $rules['image'] = ['required', 'image', 'max:2084'];
        }

        $validatedData = $this->validate($rules);

        // Handle image update
        if ($this->image) {
            $validatedData['image'] = $this->image->store('img/rooms', 'public');
            if ($this->room->image && Storage::disk('public')->exists($this->room->image)) {
                Storage::disk('public')->delete($this->room->image);
            }
        }

        $this->room->update($validatedData);

        // Update fasilitas
        RoomHasFacility::where('room_id', $this->room->id)->delete();
        if (count(array_filter($this->selectedFacilities))) {
            foreach (array_filter($this->selectedFacilities) as $facility) {
                RoomHasFacility::create([
                    'room_id' => $this->room->id,
                    'facility_code' => $facility
                ]);
            }
        }

        $this->dispatchBrowserEvent('room:edited');
    }
}
