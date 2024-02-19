@extends('layouts.front')
@section('content')
    <!-- Hero -->
    <section class="relative hero hero-alt">
        {{-- <img src="{{ asset('assets/front/img/hero.jpg') }}" alt="Haven Holidays Nepal" style="border-radius: 0px;height: 300px;"> --}}
        <img src="{{ $page->imageUrl }}" alt="Haven Holidays Nepal">
        <div class="absolute overlay">
            <div class="container ">
                <h1>{{ $page->name ?? '' }}</h1>
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb fs-sm wrap">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $page->name ?? '' }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
    </section>

    <section class="py-3 about-page">
        <div class="container">
            <div class="grid gap-1 lg:grid-cols-3 xl:grid-cols-1">
                <div class="lg:col-2 xl:col-3">
                    <div class="prose mx-auto">
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



    <!--<section class="hero-second">-->
    <!--  <div class="slide" style="background-image: url({{ $page->imageUrl ?? '' }})">-->
    <!--  </div>-->
    <!--  <div class="hero-bottom">-->
    <!--    <div class="container">-->
    <!--      <h1>{{ $page->name ?? '' }}</h1>-->
    <!--      <nav aria-label="breadcrumb">-->
    <!--        <ol class="breadcrumb">-->
    <!--          <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>-->
    <!--          <li class="breadcrumb-item active" aria-current="page">{{ $page->name }}</li>-->
    <!--        </ol>-->
    <!--      </nav>-->
    <!--    </div>-->
    <!--</section>-->

    <!--<section class="tour-details">-->
    <!--  <div class="container mt-2">-->
    <!--    <div class="row">-->
    <!--      <div class="col-md-8 col-lg-9">-->
    <!--        <div class="tour-details-section">-->
    <!--        	<div>-->
    <!--        		<?= $page->description ?? '' ?>-->
    <!--        	</div>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--      <div class="col-md-4 col-lg-3">-->
    <!--        <aside>-->
    <!-- enquiry block -->
    <!--          @include('front.elements.enquiry')-->
    <!-- end of enquiry block -->
    <!--        </aside>-->
    <!--      </div>-->
    <!--    </div>-->
    <!--</section>-->
@endsection
