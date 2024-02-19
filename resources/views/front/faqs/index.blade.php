@extends('layouts.front_inner')
@section ('content')
    <!-- Hero -->
    <section class="hero hero-alt relative">
        <img src="{{ asset('assets/front/img/hero.jpg') }}" alt="" style="max-height: 400px;">
        <div class="overlay absolute">
            <div class="container ">
                <h1>Frequently Asked Questions</h1>
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb fs-sm wrap">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">FAQs</li>
                        </ol>
                    </nav>
                </div>
            </div>
    </section>

    <section class="py-10">
        <div class="container">
            <div class="grid lg:grid-cols-3 xl:grid-cols-4 gap-10">
                <div class="lg:col-span-2 xl:col-span-3">
                    @foreach($faq_categories as $category)
                        @if(iterator_count($category->faqs))
                            <div class="mb-8" x-data="{active: 'none'}">
                                <h2 class="mb-2 text-2xl font-display text-primary uppercase" style="color: #f90;">{{ $category->name }}</h2>
                                @foreach($category->faqs as $key => $faq)
                                    <div class="mb-1 border-light">
                                        <button class="flex justify-between items-center w-full p-2 text-left" @click="active = (active === {{ $key }} ? 'none' : {{ $key }})">
                                            <h3 class="font-display text-xl text-primary">{{ $faq->title }}</h3>

                                            <svg class="w-6 h-6 flex-shrink-0 text-primary" x-show="active!=={{ $key }}">
                                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#plus" />
                                            </svg>
                                            <svg class="w-6 h-6 flex-shrink-0 text-primary" x-show="active==={{ $key }}">
                                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#minus" />
                                            </svg>
                                        </button>
                                        <div class="p-4" x-cloak x-show.transition="active==={{ $key }}">
                                            <p class="mb-0">
                                                <?= $faq->content; ?>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
                {{-- <aside>
                    @include('partials.enquiry')
                </aside> --}}
            </div>
        </div>

    </section>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script>
@endpush