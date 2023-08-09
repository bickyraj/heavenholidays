@extends('layouts.front_blog')
@section('content')
    <!-- Hero -->
    <section class="relative hero hero-alt">
        {{-- <img src="{{ asset('assets/front/img/hero.jpg') }}" alt=""> --}}
        <img src="{{ $blog->imageUrl }}" alt="">
        <div class="absolute overlay">
            <div class="container">
                <h1 class="drop-shadow-md font-display">{{ $blog->name }}</h1>
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb fs-sm wrap">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('front.blogs.index') }}">Blog</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $blog->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
    </section>

    <section class="py-20 mb-4 tour-details">
        <div class="max-w-5xl px-4 mx-auto mt-2">
            <div class="prose tour-details-section prose-headings:text-primary">
                {!! $blog->description !!}
            </div>
        </div>
    </section>

    <!-- Latest News -->
    <section class="mb-20 news">
        <div class="container">

            <div class="grid gap-2 lg:grid-cols-3 xl:gap-3">
                @forelse ($blogs as $blog)
                    <a href="{{ route('front.blogs.show', $blog->slug) }}">
                        <div class="article">
                            <div class="image">
                                <img src="{{ $blog->imageUrl }}" alt="{{ $blog->name }}" class="lazyload" title="{{ $blog->name }}" width="300" height="200">
                            </div>
                            <div class="content">
                                <h2 class="text-xl font-bold text-gray-700 hover:text-primary">{{ $blog->name }}</h2>
                                <div class="flex items-center mt-4 text-xs text-gray-400"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ formatDate($blog->blog_date) }}
                                </div>
                                <p class="mt-1">
                                    {{ truncate(strip_tags($blog->description)) }}
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                @endforelse
            </div>
        </div>
    </section>
@endsection
