@extends('layouts.front_inner')
@section('meta_og_title'){!! $blog->seo->meta_title ?? '' !!}@stop
@section('meta_description'){!! $blog->seo->meta_description ?? '' !!}@stop
@section('meta_keywords'){!! $blog->seo->meta_keywords ?? '' !!}@stop
@section('meta_og_url'){!! $blog->seo->canonical_url ?? '' !!}@stop
@section('meta_og_description'){!! $blog->seo->meta_description ?? '' !!}@stop
@section('meta_og_image'){!! $blog->seo->ogImageUrl ?? '' !!}@stop
@section('content')
    <!-- Hero -->
    <section class="relative hero hero-alt">
        <img src="{{ $blog->imageUrl }}" alt="">
        <div class="absolute overlay">
            <div class="container ">
                <h1 style="font-size: calc(2vw + 1rem);">{{ $blog->name }}</h1>
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

    @if ($contents)
        <section class="container grid gap-10 py-20 lg:grid-cols-3">
            <div>
                <div class="sticky top-32 bg-gray p-4">
                    <h2 class="mb-4 text-primary">Table of Contents</h2>
                    <div class="prose prose-a:no-underline prose-li:list-none">
                        {!! $contents !!}
                    </div>
                </div>
            </div>
            <div class="lg:col-span-2">
                <div class="prose prose-headings:text-primary" style="max-width: 90ch;">
                    {!! $body !!}
                </div>
            </div>
        </section>
    @else
        <div class="container py-20">
            <div class="mx-auto prose">
                {!! $blog->toc !!}
            </div>
        </div>
    @endif

    <!-- similar blogs -->
    @if (isset($blog->similar_blogs) && !empty($blog->similar_blogs))
        <section class="mt-20 mb-5 bg-gray-100 news">
            <div class="container">
                <h2 class="relative pt-10 pb-10 pr-10 text-3xl font-bold text-gray-600 uppercase lg:text-5xl font-display">Latest Travel Blogs</h2>
                <div class="absolute right-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                <div class="grid gap-2 lg:grid-cols-3 xl:gap-3">
                    @foreach ($blog->similar_blogs as $s_blog)
                        <a href="{{ route('front.blogs.show', $s_blog->slug) }}">
                            <div>
                                <div class="relative">
                                    <img src="{{ $s_blog->mediumImageUrl }}" alt="{{ $s_blog->image_alt }}" class="w-full h-auto rounded-lg" width="615" height="462" loading="lazy">
                                    <div class="absolute bottom-0 px-2 text-center text-white bg-accent left-4" style="bottom:0; left:1rem;">
                                        <div class="text-sm">{{ date('M', strtotime($s_blog->blog_date)) }}</div>
                                        <div class="text-lg font-bold">{{ date('j', strtotime($s_blog->blog_date)) }}</div>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <h3 class="text-xl font-bold text-gray-600">{{ $s_blog->name }}</h3>
                                    <p class="mt-2 text-gray-600">
                                        {{ truncate(strip_tags($s_blog->description)) }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
