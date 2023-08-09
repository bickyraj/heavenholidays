@extends('layouts.front_inner')
@section('content')
<!-- Hero -->
<section class="hero hero-alt relative">
    <img src="{{ asset('assets/front/img/hero.jpg') }}" alt="" style="height: 100%;">
    <div class="overlay absolute">
        <div class="container ">
            <h1>{{ $team->name }}</h1>
            <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb fs-sm wrap">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('front.teams.index') }}">Our Team</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $team->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
</section>

<section class="py-5">
    <div class="container lg:grid grid-cols-3">

       
        <div class="col-span-1" style="padding-top: 25px;">
           <img class="img-fluid" src="{{ $team->imageUrl }}" alt="{{ $team->name }}" style="border: 5px solid #f3ad0f; border-radius: 50%;">  
        </div>
         <div class="col-span-2" style="padding: 25px;">
           <h2 class="fs-xl text-primary" style="font-weight: bold; font-size: 33px;">{{ $team->name }}</h2>
                    <p class="fs-lg" style="font-weight: bold;font-size: 23px;">{{ $team->position }}</p>
                    <div class="lim">
                        <?= $team->description; ?>
                    </div> 
        </div>
   
    </div>
</section>
@endsection
