<div class="relative destination fade-in">
    <a href="{{ route('front.destinations.show', $destination->slug) }}">
        <div class="destination__img"><img class="lazyload" data-src="{{ $destination->imageUrl }}" class="block" alt="{{ $destination->name }}" title="{{ $destination->name }}" width="300"
                height="300"></div>
        <div class="absolute px-4 py-2 text-center rounded-lg shadow-md text">
            <h2 class="font-bold">{{ $destination->name }}</h2>
            <div class="text-sm text-gray">{{ $destination->trips->count() }} tours</div>
        </div>
    </a>
</div>
