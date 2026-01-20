<div>

<main class="lg:min-h-screen flex items-center justify-center bg-[#c1e6e6] relative overflow-hidden">
    <img src="{{ asset('img/baner2.jpeg') }}" alt="Background Illustration"
         class="absolute inset-0 w-full h-full object-cover pointer-events-none opacity-80 z-0">

    <div class="absolute inset-0 bg-black/50 z-[1]"></div>

    <div class="container px-8 mx-auto text-center relative z-10 py-10 -mt-12">
        <p class="text-white font-semibold text-sm uppercase mb-2"></p>

        <h1 class="text-4xl sm:text-6xl font-black leading-tight mb-6 text-gray"
            style="
                -webkit-text-stroke: 1px white;
                text-shadow: 0 0 2px white;
            ">
            Graha Indah
        </h1>

        <!-- Deskripsi: tetap putih -->
        <p class="text-white text-base sm:text-lg max-w-2xl mx-auto mb-8">
            Enjoy your stay with us, where comfort meets luxury.
        </p>
    </div>
</main>





    <section class="my-20">
        <div class="container px-8 mx-auto space-y-10">
            <div class="space-y-2">
                <h1 class="sm:text-5xl text-gray-800 text-3xl font-['poppins'] font-black capitalize after:content-[''] after:block after:w-10 after:h-1 after:bg-gray-800 after:rounded-full">Our Best Facilities</h1>
                <p class="tracking-wide text-gray-600 sm:text-base text-sm">We offer the best facilities to accompany your rest</p>
            </div>
            <div class="grid lg:grid-cols-3 sm:grid-cols-2 gap-10">
                @foreach ($facilities as $facility)
                    <div class="space-y-4">
                        <div class="aspect-[5/4] rounded-tr-2xl rounded-bl-2xl overflow-hidden">
                            <img class="w-full h-full object-cover hover:scale-110 transition-all duration-300" src="{{ asset("storage/$facility->image") }}" alt="{{ $facility->name }}">
                        </div>
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold font-['poppins'] text-lg text-gray-800">{{ $facility->name }}</h3>
                            <a href="{{ route('facilities.index', $facility->code) }}" class="flex items-center gap-1 group">
                                <span class="text-sm text-gray-600 group-hover:underline">Learn more</span>
                                <i class='bx bx-right-arrow-alt'></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

<section class="lg:my-40 mt-10 mb-20">
    <div class="container px-8 mx-auto lg:space-y-20 space-y-10">
        <div class="space-y-2 text-left">
            <h1 class="sm:text-5xl text-3xl text-gray-800 font-['poppins'] font-black capitalize after:content-[''] after:block after:w-10 after:h-1 after:bg-gray-800 after:rounded-full">
                Favorite Room
            </h1>
            <p class="tracking-wide text-gray-600 sm:text-base text-sm max-w-xl">
                Discover our most loved rooms. Designed for comfort, perfect for your next stay. 
                <a href="{{ route('rooms.index') }}" class="underline">View all options</a>
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12">
            @foreach ($favouriteRooms as $room)
                <div class="relative bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden hover:shadow-2xl transition duration-300 group">
                    <div class="grid md:grid-cols-2 grid-cols-1">
                        <div class="aspect-[4/3] overflow-hidden">
                            <img class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" src="{{ asset("storage/$room->image") }}" alt="{{ $room->name }}">
                        </div>
                        <div class="p-6 flex flex-col justify-between space-y-4">
                            <div class="space-y-1">
                                <h3 class="text-2xl font-bold text-gray-800 font-['poppins']">{{ $room->name }}</h3>
                                <p class="text-sm text-gray-600 line-clamp-3">{{ $room->description }}</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">
                                    {{ max(0, $room->total_rooms - $room->reservations->count()) }} rooms available
                                </span>
                                <a href="{{ route('rooms.show', $room->code) }}" class="inline-flex items-center text-indigo-600 hover:underline font-semibold">
                                    Order <i class='bx bx-right-arrow-alt text-xl ml-1'></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <span class="absolute top-4 left-4 bg-indigo-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">#{{ $loop->iteration }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>



    <section class="lg:my-40 mt-10 mb-20">
        <div class="container px-8 mx-auto space-y-6">
            <div class="space-y-2">
                <h1 class="sm:text-5xl text-gray-800 text-3xl font-['poppins'] font-black capitalize after:content-[''] after:block after:w-10 after:h-1 after:bg-gray-800 after:rounded-full">Gallery</h1>
                <p class="tracking-wide text-gray-600 sm:text-base text-sm">Want to see our rooms and facilities?</p>
            </div>
            <div class="md:columns-3 sm:columns-2 gap-4 space-y-4">
                @foreach ($gallery as $image)
                    <div class="overflow-hidden rounded-tr-2xl rounded-bl-2xl">
                        <img class="w-full hover:scale-110 transition-all duration-300" src="{{ asset("storage/$image->image") }}" alt="{{ $image->title }}" title="{{ $image->title }}">
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="lg:my-40 mt-10 mb-20">
        <div class="container px-8 mx-auto space-y-6 text-center">
            <div class="space-y-2">
                <h1 class="sm:text-5xl text-gray-800 text-3xl font-['poppins'] font-black capitalize after:content-[''] after:block after:w-10 after:h-1 after:bg-gray-800 after:rounded-full max-w-max mx-auto">Fascinated?</h1>
                <p class="tracking-wide text-gray-600 sm:text-base text-sm">Choose your desired hotel room now</p>
            </div>
            <a href="{{ route('rooms.index') }}" class="btn">View All Rooms</a>
        </div>
    </section>
</div>
