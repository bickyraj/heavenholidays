<a href="{{ route('front.activities.show', $activity->slug) }}" class="activity">
    <div class="relative">
        <img src="{{ $activity->mediumImageUrl }}" alt="{{ $activity->name }}" class="block w-full rounded-full">
        <div class="px-2 py-4 text-center">
            <h2 class="font-display uppercase">{{ $activity->name }}</h2>
            <div class="tours">
                <span class="fs-xl bold">{{ $activity->trips->count() }}</span>
                <span class="fs-sm">tours</span>
            </div>
        </div>
    </div>
</a>
