<div class="space-y-8">
    <h1 class="text-gray-800 text-3xl font-black capitalize after:content-[''] after:block after:w-10 after:h-1 after:bg-gray-800 after:rounded-full">
        Reservation
    </h1>

    <a class="btn" href="{{ route('rooms.index') }}">New Reservation</a>

    <x-table>
        <thead class="thead">
            <tr>
                <th class="th">Code</th>
                <th class="th">Room</th>
                <th class="th">Check In</th>
                <th class="th">Check Out</th>
                <th class="th">Total Room(s)</th>
                <th class="th">Total Price</th>
                <th class="th">Status</th>
                <th class="th">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reservations as $reservation)
                <tr class="bg-white border-b">
                    <td class="td font-medium">{{ $reservation->code }}</td>
                    <td class="td">
                        <a href="{{ route('rooms.show', $reservation->room->code) }}" class="underline">
                            {{ $reservation->room->name }}
                        </a>
                    </td>
                    <td class="td">{{ $reservation->check_in }}</td>
                    <td class="td">{{ $reservation->check_out }}</td>
                    <td class="td">{{ $reservation->total_rooms }}</td>
                    <td class="td">Rp {{ number_format($reservation->total_price, 0, ',', '.') }}</td>
                    <td class="td capitalize">
                        {{ $reservation->status }}
                        @if ($reservation->payment_proof && $reservation->status === 'waiting')
                            <span class="text-yellow-500 text-xs block">Menunggu verifikasi</span>
                        @endif
                    </td>
                    <td class="td">
                        <div x-data="{ openCancel: false, openUpload: false }" class="relative">
                            <div class="flex justify-center space-x-2">
                                <a target="_blank" href="{{ route('dashboard.user.reservations.proof', $reservation->code) }}" class="btn btn-sm">Print</a>

                                @if ($reservation->status === 'waiting' && !$reservation->payment_proof)
                                    <button type="button" class="btn btn-sm btn-outline" @click="openCancel = true">Cancel</button>
                                    <button type="button" class="btn btn-sm btn-success" @click="openUpload = true">Bayar</button>
                                @endif
                            </div>

                            {{-- Modal Cancel --}}
                            <div x-show="openCancel" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                                <div class="absolute inset-0 bg-black bg-opacity-50" @click="openCancel = false"></div>
                                <form wire:submit.prevent="canceled"
                                    class="bg-white z-50 rounded-xl p-6 space-y-4 w-full max-w-md mx-auto">
                                    <h2 class="text-xl font-semibold text-gray-800">Alasan Pembatalan</h2>
                                    <textarea wire:model.defer="message" class="textarea w-full" rows="4" placeholder="Tulis alasan pembatalan..."></textarea>
                                    @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                    <div class="flex justify-end space-x-2">
                                        <button type="submit" wire:click="cancel('{{ $reservation->code }}')" class="btn">Kirim</button>
                                        <button type="button" @click="openCancel = false" class="btn btn-outline">Batal</button>
                                    </div>
                                </form>
                            </div>

                            {{-- Modal Upload Pembayaran --}}
                            <div x-show="openUpload" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                                <div class="absolute inset-0 bg-black bg-opacity-50" @click="openUpload = false"></div>

                                <form wire:submit.prevent="uploadPayment('{{ $reservation->code }}')" enctype="multipart/form-data"
                                    class="bg-white z-50 rounded-xl p-6 space-y-5 w-full max-w-md mx-auto shadow-lg">
                                    <h2 class="text-xl font-bold text-gray-800">Upload Bukti Pembayaran</h2>

                                    {{-- Metode Pembayaran --}}
                                    <div class="space-y-1">
                                        <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                                        <select wire:model.defer="payment_method" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-indigo-200">
                                            <option value="">Pilih Metode</option>
                                            <option value="cash">Cash</option>
                                            <option value="transfer">Transfer</option>
                                        </select>
                                        @error('payment_method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    {{-- Upload File --}}
                                    <div class="space-y-1">
                                        <label class="block text-sm font-medium text-gray-700">Upload Bukti (jpg/png/pdf)</label>
                                        <input type="file" wire:model="payment_proof"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 shadow-sm focus:ring focus:ring-indigo-200"
                                            accept=".jpg,.jpeg,.png,.pdf">
                                        @error('payment_proof') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        @if ($payment_proof)
                                            <p class="text-sm text-gray-600 mt-1">File terpilih: {{ $payment_proof->getClientOriginalName() }}</p>
                                        @endif
                                    </div>

                                    {{-- Catatan --}}
                                    <p class="text-xs text-gray-500 leading-relaxed">
                                        <strong>Catatan:</strong> Setelah mengunggah bukti, silakan tunggu verifikasi dari resepsionis. Status reservasi akan tetap <em>waiting</em> sampai dikonfirmasi.
                                    </p>

                                    {{-- Tombol --}}
                                    <div class="flex justify-end space-x-2 pt-2">
                                        <button type="submit" class="btn btn-success">Upload</button>
                                        <button type="button" @click="openUpload = false" class="btn btn-outline">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="td text-center">Belum ada reservasi</td>
                </tr>
            @endforelse
        </tbody>
    </x-table>

    {{-- Pagination --}}
    <div>
        {{ $reservations->links() }}
    </div>

    {{-- Flash Modal Cancel --}}
    <div x-data="{ open: false }">
        <div x-show="open" @reservation:canceled.window="open = true" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/50"></div>
            <div class="bg-white rounded-xl p-8 text-center space-y-4 max-w-sm z-50">
                <i class='bx bx-check-circle text-green-500 text-6xl'></i>
                <h2 class="text-xl font-bold text-gray-800">Reservasi Berhasil Dibatalkan</h2>
                <p class="text-gray-600">Terima kasih telah menggunakan layanan kami!</p>
                <button class="btn" @click="open = false">Oke</button>
            </div>
        </div>
    </div>
</div>
