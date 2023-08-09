<div>
    <a href="{{ route('front.activities.show', $activity->slug) }}" class="activity">
        <div class="relative">
            <div class="px-4">
                <img class="object-cover rounded-full lazyload aspect-square" data-src="{{ $activity->imageUrl }}" alt="{{ $activity->name }}" class="block w-full" title="{{ $activity->name }}"
                    width="240" height="240">
            </div>
            <div class="px-2 py-4 text-center">
                <h2 class="text-xl font-bold text-gray-600 hover:text-primary">{{ $activity->name }}</h2>
                <div class="text-sm text-gray-400">
                    <span class="bold">{{ $activity->trips->count() }}</span>
                    <span>tours</span>
                </div>
            </div>
        </div>
    </a>
</div>
