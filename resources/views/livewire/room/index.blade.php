<main class="mt-28 mb-20">
    <div class="container px-8 mx-auto space-y-10">
        <!-- Header dan Filter -->
        <div class="space-y-2">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="sm:text-5xl text-3xl text-gray-800 font-poppins font-black capitalize after:content-[''] after:block after:w-10 after:h-1 after:bg-gray-800 after:rounded-full">
                        Room Choices For You
                    </h1>
                    <p class="tracking-wide text-gray-600 sm:text-base text-sm">
                        We have a choice of {{ $rooms->count() }} different room types that you can choose according to your needs.
                    </p>
                </div>

                <!-- Filter Tombol -->
                <form method="GET" class="w-full sm:w-auto">
                    <div class="flex gap-2 flex-wrap">
                        @php $active = request('type'); @endphp

                        <button type="submit" name="type" value="" 
                            class="px-4 py-2 rounded-md text-sm font-medium border
                            {{ $active == '' ? 'bg-gray-800 text-white' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
                            All
                        </button>
                        <button type="submit" name="type" value="villa" 
                            class="px-4 py-2 rounded-md text-sm font-medium border
                            {{ $active == 'villa' ? 'bg-gray-800 text-white' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
                            Villa
                        </button>
                        <button type="submit" name="type" value="kos" 
                            class="px-4 py-2 rounded-md text-sm font-medium border
                            {{ $active == 'kos' ? 'bg-gray-800 text-white' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100' }}">
                            Kos
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Room Cards -->
        <div class="grid lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-8">
            @forelse ($rooms as $room)
                <div class="space-y-4">
                    <div class="aspect-[8/6] w-full rounded-tl-2xl rounded-br-2xl overflow-hidden">
                        <img 
                            class="w-full h-full object-cover hover:scale-110 transition-all duration-300" 
                            src="{{ $room->image ? asset('storage/' . $room->image) : asset('images/no-image.png') }}" 
                            alt="Image of {{ $room->name }}"
                        >
                    </div>

                    <div class="bg-gray-200 text-sm text-gray-600 flex flex-wrap gap-x-4 gap-y-2 justify-center rounded-tr-lg rounded-bl-lg py-2 px-4">
                        <div class="flex items-center gap-1 text-gray-800"><i class='bx bx-show'></i><span>{{ $room->views }}</span></div>
                        <div class="flex items-center gap-1 text-gray-800"><i class='bx bx-star'></i><span>{{ $room->rate }}</span></div>
                        <div class="flex items-center gap-1 text-gray-800"><i class='bx bx-chat'></i><span>{{ $room->reviews->count() }}</span></div>
                        <div class="flex items-center gap-1 text-gray-800"><i class='bx bx-money'></i><span>${{ $room->price }}/night</span></div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold font-poppins text-gray-800">{{ $room->name }}</h3>
                            <a href="{{ route('rooms.show', $room->code) }}" class="flex items-center gap-1 group">
                                <span class="text-sm text-gray-600 group-hover:underline">Learn more</span>
                                <i class='bx bx-right-arrow-alt'></i>
                            </a>
                        </div>
                        <p class="tracking-wide text-gray-600 sm:text-base text-sm break-words line-clamp-3">
                            {{ $room->description }}
                        </p>
                    </div>

                    <span class="text-sm text-gray-600 bg-gray-200 py-2 text-center rounded-tr-xl rounded-bl-xl block">
                        {{ max(0, $room->total_rooms - $room->reservations->count()) }} rooms available
                    </span>
                </div>
            @empty
                <p class="tracking-wide text-gray-600 sm:text-base text-sm">There is nothing here</p>
            @endforelse
        </div>
    </div>
</main>
