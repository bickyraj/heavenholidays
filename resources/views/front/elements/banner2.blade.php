@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tiny-slider@2.9.3/dist/tiny-slider.css">
@endpush

<section>
    <div class="relative z-10 hero">
        <!-- Slider -->
        <div id="banner-slider" class="hero-slider">
            @forelse ($banners as $banner)
                <div class="relative slide banner">
                    <img src="{{ $banner->imageUrl }}" data-img="{{ $banner->largeImageUrl }}" class="w-full h-96 lg:h-[48rem] object-cover object-top lazyload" alt="{{ $banner->name }}"
                        width="1920" height="1280">
                    <div class="absolute w-full py-4 text lg:py-6">
                        <div class="container mb-8">
                            <h2 class="text-3xl font-bold text-white lg:text-6xl font-display">
                                <span>{{ $banner->caption }}</span>
                            </h2>

                            @if ($banner->btn_link)
                                <div class="buttons">
                                    {{-- <a href="{{ route('front.trips.show', ['slug' => $banner->slug]) }}" class="btn btn-primary"> --}}
                                    <a href="{{ $banner->btn_link }}" class="btn btn-primary">
                                        View more
                                        <svg class="w-6 h-4">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#arrownarrowright') }}" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div><!-- Slider -->

        <div class="absolute bottom-0 -translate-x-1/2 left-1/2">
            <svg viewbox="0 0 40 6" class="h-12">
                <path d="M0 6 10 2 14 4 20 0 26 4 30 2 40 6 0 6" fill="white">
            </svg>
        </div>

        @include('front.elements.trip-search')

    </div><!-- Hero -->
</section>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tiny-slider@2.9.3/dist/tiny-slider.min.js"></script>

    <script>
        const heroSlider = tns({
            mode: 'gallery',
            container: '.hero-slider',
            nav: false,
            // controlsContainer: '.hero-slider-controls .container',
            controls: false,
            autoplay: true,
            autoplayButtonOutput: false,
            mouseDrag: true
        })
    </script>
@endpush
