<div class="relative pt-10 bg-light">
    <div class="container px-4">
        <div class="plan-trip grid lg:grid-cols-2 gap-10">
            <div class="grid grid-cols-3 gap-2 lg:gap-10" style="place-items: center">
                <img src="{{ asset('assets/front/img/ramchandra.jpg') }}" alt="" loading="lazy" class="w-full aspect-square object-cover">
                <img src="{{ asset('assets/front/img/trent.jpg') }}" alt="" loading="lazy" class="w-full aspect-square object-cover">
                <img src="{{ asset('assets/front/img/ganga.jpg') }}" alt="" loading="lazy" class="w-full aspect-square object-cover">
            </div>
            <div class="px-4 py-10 prose">
                <h2>
                    <div class="mb-4 text-left text-gray-600 text-3xl lg:text-5xl font-bold">
                        Plan your trip
                    </div>
                    <div class="text-left text-gray-600 text-2xl lg:text-4xl">
                        Tailor-made trips to suit your needs and desires.
                    </div>
                </h2>
                <p>Please feel free to ask any questions, and together we'll create the ideal journey based on your interests and aspirations.</p>
                @if (request()->routeIs('home'))
                    <a href="{{ route('front.plantrip') }}" class="btn btn-accent" style="text-decoration:none;">Start planning</a>
                @else
                    <a href="{{ route('front.contact.index') }}" class="btn btn-accent" style="text-decoration:none;">Contact Us</a>
                @endif
            </div>
        </div>
    </div>
</div>
