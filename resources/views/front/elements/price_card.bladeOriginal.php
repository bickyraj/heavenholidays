<div x-data="{isGroupDiscountsShown: {{ $isGroupDiscountsShown ? 'true' : 'false' }}}" class="pt-4 mb-6 rounded-lg price-card bg-primary">
    <div class="relative flex mb-2 ribbon">
        <div class="relative px-4 py-1 text-lg bg-white font-display text-primary">
            Best Price
        </div>
    </div>
    <div class="p-4 text-white">
        @if($trip->cost)
        <div class="">
            <span class="mb-2 mr-2 text-sm">Price starting from</span>
            <s class="text-xl font-bold">${{ number_format($trip->cost) }}</s>
        </div>
        <div class="mb-2 font-display">
            <span class="text-xl font-bold">US $</span>
            <span class="text-3xl font-bold text-accent">{{ number_format($trip->offer_price) }}</span>
            <span class="text-xl">per person</span>
        </div>
        <button x-on:click="isGroupDiscountsShown=!isGroupDiscountsShown" class="mb-6 text-sm text-accent">Show Group Discounts</button>
        @endif
        {{-- Group Discounts --}}
        <table x-show="isGroupDiscountsShown" x-cloak class="w-full mb-4">
            <thead>
                <th class="px-1 py-2 text-left border-b border-white font-display">No of people</th>
                <th class="px-1 py-2 text-right border-b border-white font-display">Price per person</th>
            </thead>
            <tbody>
                @forelse ($trip->people_price_range as $item)
                    <tr class="border-b border-white/30">
                        <td class="px-1 py-2 text-sm">{{ $item['from'] }} {{ ($item['to'] != "") ? " - ". $item['to']: "" }}</td>
                        <td class="px-1 py-2 text-sm text-right">${{ number_format($item['price']) }}</td>
                    </tr>
                @empty

                @endforelse
            </tbody>
        </table>
        <div class="mb-2 text-center">
            <a href="{{ route('front.trips.booking', $trip->slug) }}" class="w-full mb-2 btn btn-accent">Book Now</a>
            <a href="{{ route('front.plantrip.createfortrip', $trip->slug) }}" class="btn btn-accent">

                <svg class="flex-shrink-0 w-6 h-6 mr-2">
                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#adjustments" />
                </svg>
                Plan My Trip
            </a>
        </div>
        <div class="p-1 actions">
            <a href="{{ route('front.trips.print', ['slug' => $trip->slug]) }}" class="flex items-center p-1 text-accent" title="Print tour details">
                <svg class="flex-shrink-0 w-4 h-4 mr-2">
                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#printer" />
                </svg>
                <span class="text-sm">Print Tour Details</span>
            </a>
            <a href="#" class="flex items-center p-1 text-accent" title="">
                <svg class="flex-shrink-0 w-4 h-4 mr-2">
                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#download" />
                </svg>
                <span class="text-sm">Download Tour Brochure</span>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('front.trips.show', ['slug' => $trip->slug]) }}" class="flex items-center p-1 text-accent" title="Share tour">
                <svg class="flex-shrink-0 w-4 h-4 mr-2">
                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#share" />
                </svg>
                <span class="text-sm">Share Tour</span>
            </a>
        </div>
    </div>
</div>
