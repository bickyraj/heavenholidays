<a href="{{ route('front.trips.show', $trip->slug) }}" class="block mb-2">
    <div class="px-2 py-4 text-white" style="background: linear-gradient(rgba(0,0,0,.5), rgba(0,0,0,.5)), center / cover url('{{ $trip->imageUrl }}')">
        <h1 class="font-display text-xl uppercase">{{ $trip->name }}</h1>
        <div class="days mb-4"><?= $trip->duration; ?> days</div>
        <div class="price"><span class="text-xs">from</span> <br><b>USD {{ number_format($trip->cost, 2) }}</b></div>
    </div>
</a>
