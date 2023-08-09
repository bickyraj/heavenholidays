@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tiny-slider@2.9.3/dist/tiny-slider.css">
@endpush

<section>
    <div class="relative hero">
        <!-- Slider -->
        <div id="banner-slider" class="hero-slider">
            @forelse ($block_1_trips as $banner)
                <div class="relative slide banner">
                    <img src="{{ $banner->thumbImageUrl }}" data-img="{{ $banner->imageUrl }}" class="block lazyload" alt="{{ $banner->name }}" width="1500" height="1000">
                    <div class="absolute w-full py-4 text lg:py-6">
                        <div class="container">
                            <div class="flex flex-col mb-4">
                                <div class="mb-2 text-2xl italic text-white lg:text-5xl">
                                    <span>{{ $banner->duration }} days</span>
                                </div>
                                <h2 class="font-bold text-white uppercase hero-slider-title">
                                    <span>{{ $banner->name }}</span>
                                </h2>
                            </div>

                            <div class="buttons">
                                <a href="{{ route('front.trips.show', ['slug' => $banner->slug]) }}" class="btn btn-primary">
                                    Explore
                                    <svg class="w-6 h-4">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#arrownarrowright') }}" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div><!-- Slider -->

        <div class="absolute hero-slider-controls none md:block">
            <div class="container flex flex-col gap-10">
                <button>
                    <svg class="w-6 h-6">
                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#arrownarrowleft') }}" />
                    </svg>
                </button>
                <button>
                    <svg class="w-6 h-6">
                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#arrownarrowright') }}" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="absolute bottom-0 -translate-x-1/2 left-1/2">
            <svg viewbox="0 0 40 6" class="h-12">
                <path d="M0 6 10 2 14 4 20 0 26 4 30 2 40 6 0 6" fill="white">
            </svg>
        </div>
    </div><!-- Hero -->
</section>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tiny-slider@2.9.3/dist/tiny-slider.min.js"></script>

    <script>
        const heroSlider = tns({
            mode: 'gallery',
            container: '.hero-slider',
            nav: false,
            controlsContainer: '.hero-slider-controls .container',
            // autoplay: true,
            autoplayButtonOutput: false
        })
    </script>
@endpush
