@extends('layouts.front')
@section('content')
    <!-- Hero -->
    <section class="relative hero hero-alt">
        <img src="{{ asset('assets/front/img/hero.jpg') }}" alt="">
        <div class="absolute overlay">
            <div class="container ">
                <h1 class="font-display upper">Legal Documents</h1>
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb fs-sm wrap">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Legal Documents</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container py-20">
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 md:gap-8">
                @forelse($documents as $document)
                    <a href="{{ $document->fileUrl }}" class="stretched-link" data-fancybox="gallery" data-caption="{{ $document->name }}">
                        <img src="{{ $document->fileUrl }}" alt="" class="w-full">
                    </a>
                @empty
                    <div class="col-12 col-md-12 col-lg-12 col-xl-12">
                        <p>No Documents to show.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css">
@endpush
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script>
        $(function() {
            $('[data-fancybox="gallery"]').fancybox({
                buttons: ['close']
            });
        });
    </script>
@endpush
