<?php

namespace App\Http\Livewire\Dashboard\User\Reservation;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Reservation;
use App\Models\Room;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $selected_reservation;
    public $message;

    // Untuk upload bukti pembayaran
    public $payment_proof;
    public $payment_method;

    protected $listeners = [
        'reservation:canceled' => 'reservationcanceled',
    ];

    public function render()
    {
        return view('livewire.dashboard.user.reservation.index', [
            'reservations' => Reservation::where('user_id', auth()->id())
                ->latest()
                ->paginate(5),
        ])->layoutData([
            'title' => 'Reservation Dashboard | Graha Indah',
        ]);
    }

    public function reservationcanceled()
    {
        $this->dispatchBrowserEvent('reservation:canceled');
    }

    public function cancel($code)
    {
        $this->selected_reservation = $code;
    }

    public function canceled()
    {
        $this->validate([
            'message' => ['required'],
        ]);

        $reservation = Reservation::where('code', $this->selected_reservation)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $room = Room::firstWhere('code', $reservation->room->code);

        $reservation->update([
            'status' => 'canceled',
            'message' => $this->message,
        ]);

        // Hitung ulang jumlah kamar tersedia (exclude yang canceled & check out)
        $available = $room->total_rooms - $room->reservations
            ->whereNotIn('status', ['canceled', 'check out'])
            ->sum('total_rooms');

        $room->update(['available' => $available]);

        $this->emitSelf('reservation:canceled');

        // Reset input
        $this->reset('message', 'selected_reservation');
    }

    public function uploadPayment($code)
    {
        $this->validate([
            'payment_method' => 'required|in:cash,transfer',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $reservation = Reservation::where('code', $code)
            ->where('user_id', auth()->id())
            ->where('status', 'waiting')
            ->firstOrFail();

        $path = $this->payment_proof->store('payment_proofs', 'public');

        $reservation->update([
            'payment_method' => $this->payment_method,
            'payment_proof' => $path,
            // Status tetap "waiting", hanya resepsionis yang bisa ubah status.
        ]);

        session()->flash('success', 'Bukti pembayaran berhasil diupload. Mohon tunggu konfirmasi dari resepsionis.');

        $this->reset('payment_method', 'payment_proof');
    }
}
