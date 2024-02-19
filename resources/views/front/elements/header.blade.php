@push('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush

{{-- Header --}}
<header class="fixed flex items-center w-full transition header" x-data="{ mobilenavOpen: false, searchboxOpen: false }">
    <div class="container relative flex items-end justify-between w-full rounded-lg right" x-data="searchDropdown()">
        <!-- Logo -->
        <a class="flex-shrink-0" href="{{ route('home') }}">
            <img src="{{ asset('assets/front/img/logo.png') }}" class="block h-16 brand" alt="{{ config('app.name') }}" width="318" height="197">
        </a><!-- Logo -->
        <div class="flex items-end gap-2 ">
            <!-- Nav -->
            @include('front.elements.navbar')

            {{-- Search --}}
            {{-- Search --}}
            <button class="p-3 ml-auto lg:ml-0" x-on:click="searchboxOpen=true;$nextTick(() => { $refs.headerSearchInput.focus(); });" aria-label="Search">
                <svg class="w-6 h-6 header-color">
                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#search"></use>
                </svg>
            </button>
            <div x-show="searchboxOpen" x-cloak class="absolute w-full max-w-3xl left-1/2 top-[8rem] z-10" @click.away="searchboxOpen=false" style="transform: translateX(-50%)">
                @include('front.elements.trip-search-header')
            </div>
            {{-- Search --}}

            {{-- Talk to expert --}}
            <div class="hidden p-3 lg:block">
                <div class="flex items-center justify-end gap-1 header-color">
                    <span class="text-xs">Talk to an expert</span>
                    <a href="{{ Setting::get('viber') ?? '' }}" style="color:#d766ff">
                        <svg class="w-5 h-5">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#viber" />
                        </svg>
                    </a>
                    <a href="{{ Setting::get('whatsapp') ?? '' }}" style="color:#28d146">
                        <svg class="w-5 h-5">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#whatsapp" />
                        </svg>
                    </a>
                </div>
                <div>
                    <a href="tel:{{ Setting::get('mobile1') ?? '' }}" class="flex items-center header-color">
                        <svg class="w-4 h-4">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#phone" />
                        </svg>
                        <div>{{ Setting::get('mobile1') ?? '' }}</div>
                    </a>
                </div>
            </div>{{-- Talk to expert --}}

            {{-- Mobile Nav Button --}}
            <div class="lg:hidden">
                <button class="p-2" @click="mobilenavOpen=!mobilenavOpen">
                    <svg class="w-6 h-6 header-color" x-show="!mobilenavOpen">
                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#menu" />
                    </svg>
                    <svg class="w-6 h-6" x-cloak x-show="mobilenavOpen">
                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#x" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>{{-- Header --}}
@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('tripStore', {
                init() {
                    this.trips = JSON.parse(document.querySelector('#tripsJson').innerText);
                },
                trips: []
            })
        })

        function searchDropdown() {
            return {
                keyword: '',
                selectedIndex: '',
                trips: [],
                get filteredTrips() {
                    if (this.keyword === '') {
                        return []
                    }
                    return Alpine.store('tripStore').trips.filter(trip => trip.name.toLowerCase().includes(this.keyword.toLowerCase()))
                },
                reset() {
                    this.keyword = ''
                },
                selectNext() {
                    if (this.selectedIndex === '') {
                        this.selectedIndex = 0;
                    } else {
                        this.selectedIndex++;
                    }
                    if (this.selectedIndex === this.filteredTrips.length) {
                        this.selectedIndex = 0;
                    }
                    this.focusSelected();
                },
                selectPrev() {
                    if (this.selectedIndex === '') {
                        this.selectedIndex = this.filteredTrips.length - 1;
                    } else {
                        this.selectedIndex--;
                    }
                    if (this.selectedIndex === -1) {
                        this.selectedIndex = this.filteredTrips.length - 1;
                    }
                    this.focusSelected();
                },
                focusSelected() {
                    this.$refs.results.children[this.selectedIndex + 1].scrollIntoView({
                        block: 'nearest'
                    })
                },
                handleSubmit(form) {
                    if (this.selectedIndex !== '') {
                        window.location.href = this.filteredTrips[this.selectedIndex].url;
                    } else {
                        form.submit();
                    }
                }
            }
        }
    </script>
@endpush
