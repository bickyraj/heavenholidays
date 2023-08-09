@extends('layouts.front')
@section('content')

<!-- Hero -->
<section class="hero hero-alt relative">
    <img src="{{ asset('assets/front/img/hero.jpg') }}" alt="">
    <div class="overlay absolute">
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

<section class="tour-details">
  <div class="container mt-2">
    <div class="tour-details-section">
      <div class="row">
       <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
             @forelse($documents as $document)
          <div class="thumb" style="background: center / cover url('{{ $document->fileUrl }}')">
            <a href="{{ $document->fileUrl }}" class="stretched-link" data-fancybox="gallery" data-caption="{{ $document->name }}">
            </a>
          </div>
           @empty
        </div>
       
        <div class="col-12 col-md-12 col-lg-12 col-xl-12">
          <p>No Documents to show.</p>
        </div>
        @endforelse
      </div>
    </div>
  </div>
</section>

@endsection
@push('scripts')
<script>
$(function() {
  $('[data-fancybox="gallery"]').fancybox({
    buttons: ['close']
  });
});
</script>
@endpush
