@extends('layouts.front')
@section('content')
    <!-- Hero -->
    <section class="relative hero hero-alt">
        <img src="{{ asset('assets/front/img/hero.jpg') }}" alt="">
        <div class="absolute overlay">
            <div class="container ">
                <h1>Blog</h1>
                <div class="text-white breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb fs-sm wrap">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="https://www.havenholidaysnepal.com/blogs">Blog </a> | <a href="https://www.havenholidaysnepal.com"> News </a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
    </section>

    <section class="py-20 news">
        <div class="container">

            <div class="grid gap-4 lg:grid-cols-3 lg:gap-10">
                @forelse ($blogs as $blog)
                    <a href="{{ route('front.blogs.show', ['slug' => $blog->slug]) }}">
                        <div class="article">
                            <div class="mb-2 image">
                                <img src="{{ $blog->imageUrl }}" alt="">
                            </div>
                            <div class="content">
                                <h2 class="text-lg">{{ $blog->name }}</h2>
                                <p class="fs-sm">{{ truncate(strip_tags($blog->description)) }}</p>

                            </div>
                        </div>
                    </a>
                @empty
                @endforelse
            </div>
        </div>
    </section>
@endsection
