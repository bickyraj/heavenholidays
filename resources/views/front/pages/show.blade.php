@extends('layouts.front')
@section('content')
    <!-- Hero -->
    <section class="relative hero hero-alt">
        {{-- <img src="{{ asset('assets/front/img/hero.jpg') }}" alt=""> --}}
        <img src="{{ $page->imageUrl }}">
        <div class="absolute overlay">
            <div class="container ">
                <h1 class="font-display">{{ $page->name ?? '' }}</h1>
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb fs-sm wrap">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $page->name ?? '' }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10">
        <div class="max-w-5xl px-4 mx-auto">
            <div class="grid gap-1 lg:grid-cols-3 xl:grid-cols-1">
                <div class="lg:col-2 xl:col-3">
                    <div class="prose prose-headings:text-primary tour-details-section">
                        <p>
                            <?= $page->description ?? '' ?>
                        </p>
                    </div>
                </div>
                <aside>
                    <!-- enquiry block -->
                    {{-- @include('front.elements.enquiry') --}}
                    <!-- end of enquiry block -->
                </aside>
            </div>
        </div>

    </section>
@endsection
