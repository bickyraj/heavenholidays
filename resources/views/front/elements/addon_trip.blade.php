<a href="{{ route('front.trips.show', ['slug' => $trip->slug])}}" class="block mb-2">
    <div class="px-2 pt-8 pb-4 text-white" style="background: linear-gradient(rgba(0,0,0,.3), rgba(0,0,0,.5)), center / cover url('{{ $trip->thumb_imageUrl }}')">
        <h1 class="font-display font-bold mb-2">{{ $trip->name }}</h1>
        <div class="days text-sm"><?= $trip->duration; ?> days</div>
        {{--
        <div class="price"><span class="text-xs">from</span> <br><b>USD {{ number_format($trip->cost, 2) }}</b></div>
        --}}
    </div>
</a>
