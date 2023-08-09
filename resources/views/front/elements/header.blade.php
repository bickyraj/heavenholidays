@push('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endpush
{{-- Header --}}
<header class="relative header" x-data="{ searchboxOpen: false, mobilenavOpen: false }" @click.away="mobilenavOpen=false">

    {{-- Top row --}}
    <div class="container relative flex items-center justify-between w-full gap-10">
        {{-- Logo --}}
        <a class="flex-shrink-0 p-2 shadow-sm" href="{{ route('home') }}">
            <img src="{{ asset('assets/front/img/logo.png') }}" width="224" height="150" class="w-auto lazy" alt="{{ config('app.name') }}" title="{{ config('app.name') }}">
        </a>{{-- Logo --}}

        {{-- Header Search --}}
        <div class="hidden header__search lg:block">
            <form id="search-form" action="{{ route('front.trips.search') }}" method="GET" class="flex justify-center w-full shadow header__searchform">
                <div class="relative flex items-center">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="absolute w-6 h-6 -translate-y-1/2 left-4 top-1/2 text-primary" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z">
                        </path>
                    </svg>
                    <input type="search" name="keyword" id="header-search" value="{{ request()->get('keyword') }}" placeholder="Where do you want to go?"
                        class="w-64 h-full py-2 pl-12 pr-2 bg-white border-0 rounded-l lg:w-80 flex-grow-1 lg:pl-16 placeholder:italic">
                </div>
                <button class="flex-shrink-0 p-3 rounded-r btn-accent">
                    <svg class="w-6 h-6">
                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#arrownarrowright" />
                    </svg>
                </button>
            </form>
        </div>{{-- Header Search --}}

        {{-- <div class="flex justify-end w-full py-2 lg:justify-start"> --}}
        {{-- Email --}}
        {{-- <a href="mailto:{{ Setting::get('email') ?? '' }}">
                <div class="flex items-center gap-2 text-sm">
                    <svg class="flex-shrink-0 w-5 h-5 lg:h-4 lg:w-4 text-primary" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z"></path>
                        <path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z"></path>
                    </svg>
                    <span class="hidden text-sm lg:inline">Email Us:</span>
                    <span class="hidden lg:inline">{{ Setting::get('email') ?? '' }}</span>
                </div>
            </a> --}}
        {{-- </div> --}}

        {{-- Talk to experts --}}
        <div class="flex items-center justify-end gap-2">
            <a href="tel:{{ Setting::get('mobile2') ?? '' }}" class="flex-shrink-0 rounded-full lg:p-3 lg:border-[1.5px] border-primary">
                <svg class="flex-shrink-0 w-4 h-4 lg:w-5 lg:h-5 text-primary" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path clip-rule="evenodd" fill-rule="evenodd"
                        d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z">
                    </path>
                </svg>
            </a>
            <div>
                <div class="text-xs font-bold tracking-wider text-gray-600 uppercase lg:text-sm lg:block whitespace-nowrap">Talk to an expert:</div>
                <div class="flex items-center gap-2">
                    <a href="tel:{{ Setting::get('mobile2') ?? '' }}" class="text-xs lg:text-lg text-primary whitespace-nowrap">
                        {{ Setting::get('mobile2') ?? '' }}
                    </a>
                    <a href="{{ Setting::get('viber') ?? '' }}" class="text-[#8f5db7] flex-shrink-0">
                        <svg class="w-4 h-4 lg:w-6 lg:h-6">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#viber" />
                        </svg>
                    </a>
                    <a href="{{ Setting::get('whatsapp') ?? '' }}" class="text-[#128c7e] flex-shrink-0"><svg class="w-4 h-4 lg:w-6 lg:h-6">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#whatsapp" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>{{-- Talk to experts --}}

    </div>{{-- Top row --}}

    {{-- Bottom row --}}
    <div class="bottom-bar">
        <div class="container flex justify-between py-2 lg:py-0 lg:justify-center">

            {{-- Header Search on bottom row for mobiles --}}
            <div class="header__search lg:hidden">
                <form id="search-form" action="{{ route('front.trips.search') }}" method="GET" class="flex justify-center w-full shadow header__searchform">
                    <div class="relative flex items-center">
                        <svg fill="currentColor" viewBox="0 0 20 20" class="absolute w-4 h-4 -translate-y-1/2 lg:w-6 lg:h-6 left-2 lg:left-4 top-1/2 text-primary" xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z">
                            </path>
                        </svg>
                        <input type="search" name="keyword" id="header-search" value="{{ request()->get('keyword') }}" placeholder="Where do you want to go?"
                            class="w-56 h-full py-2 pl-8 pr-2 text-sm bg-white border-0 rounded-l lg:pl-12 lg:w-80 flex-grow-1 placeholder:italic">
                    </div>
                    <button class="flex-shrink-0 p-3 rounded-r btn-accent">
                        <svg class="w-4 h-4 lg:w-6 lg:h-6">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#arrownarrowright" />
                        </svg>
                    </button>
                </form>
            </div>{{-- Header Search --}}

            @include('front.elements.navbar')

            <div class="flex gap-4 lg:hidden">

                {{-- Mobile Nav Button --}}
                <div>
                    <button class="flex items-center justify-center p-2 rounded-lg bg-gray" @click="mobilenavOpen=!mobilenavOpen">
                        <svg class="w-6 h-6" x-show="!mobilenavOpen">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#menu" />
                        </svg>
                        <svg class="w-6 h-6" x-cloak x-show="mobilenavOpen">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#x" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>{{-- Bottom row --}}

</header>{{-- Header --}}
@push('scripts')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ asset('assets/js/search-trips.js') }}"></script>
@endpush