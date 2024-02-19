@extends('layouts.front_inner')
@section('content')
<!-- Hero -->
<section class="hero hero-alt relative">
    <img src="{{ asset('assets/front/img/team.jpg') }}" alt="" style="border-radius: 0px;height: 500px;">
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
    <div class="container">

        <div class="tour-details-section team-member">

            <div class="row">
                <div class="col-sm-4 col-md-3 col-lg-2">
                    <div>
                        <img class="img-fluid" src="{{ $team->imageUrl }}" alt="{{ $team->name }}" style="height: 349px; padding-top: 29px;">
                    </div>
                    <h2 class="fs-xl text-primary">{{ $team->name }}</h2>
                    <p class="fs-lg">{{ $team->position }}</p>
                    <div class="lim">
                        <?= $team->description; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
