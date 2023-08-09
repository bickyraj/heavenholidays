<div class="pt-4 mb-6 price-card bg-primary">
    <div class="relative flex mb-4 ribbon">
        <div class="relative py-1 text-xl text-gray-600 bg-accent font-display" style="padding-left: 5px;">
            Best Price Guarantee
        </div>
    </div>

    <div class="p-4 text-white">

        <div class="">

            <span class="mb-2 mr-1 text-sm">Price starting from</span>
            <s class="text-xl">${{ number_format($trip->cost) }}</s>
        </div>
        <div class="mb-8 font-display">
            <span class="text-2xl font-bold">USD $</span>
            <span class="text-4xl font-bold text-light">{{ number_format($trip->offer_price) }}</span>
            <span class="text-lg">per person</span>
        </div>

        <div class="mb-2 text-center">
            <a href="{{ route('front.trips.booking', $trip->slug) }}" class="mb-2 btn btn-accent">Book Now</a>
            <a href="{{ route('front.trips.customize', $trip->slug) }}" class="btn btn-accent">

                <svg class="flex-shrink-0 w-6 h-6 mr-2">
                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#adjustments" />
                </svg>
                Customize
            </a>
        </div>
    </div>
    {{-- <div class="p-2 bg-light">
        <div class="mb-2 font-bold">Get group discounts</div>
        <table>
            <thead>
                <th class="px-1 py-2 font-display">Group size</th>
                <th class="px-1 py-2 font-display">Price per person</th>
            </thead>
            <tbody>
                <tr>
                    <td class="px-1 py-2 text-sm">1 person</td>
                    <td class="px-1 py-2 text-sm text-right">$1500</td>
                </tr>
                <tr>
                    <td class="px-1 py-2 text-sm">2 - 4 people</td>
                    <td class="px-1 py-2 text-sm text-right">$1450</td>
                </tr>
                <tr>
                    <td class="px-1 py-2 text-sm">5-10 people</td>
                    <td class="px-1 py-2 text-sm text-right">$1425</td>
                </tr>
                <tr>
                    <td class="px-1 py-2 text-sm">10-20 people</td>
                    <td class="px-1 py-2 text-sm text-right">$1415</td>
                </tr>
                <tr>
                    <td class="px-1 py-2 text-sm">more than 20 people</td>
                    <td class="px-1 py-2 text-sm text-right">$1415</td>
                </tr>
            </tbody>
        </table>
    </div> --}}
</div>
