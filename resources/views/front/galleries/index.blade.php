<?php
  if (session()->has('success_message')) {
    $session_success_message = session('success_message');
    session()->forget('success_message');
  }

  if (session()->has('error_message')) {
    $session_error_message = session('error_message');
    session()->forget('error_message');
  }
?>
@extends('layouts.front_inner')
@push('styles')
<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>
@endpush
@section('content')

<!-- Hero -->
<section class="hero hero-alt relative">
    <img src="{{ asset('assets/front/img/hero.jpg') }}" alt="" style="border-radius: 0px;height: 300px;">
    <div class="overlay absolute">
        <div class="container ">
            <h1 class="font-display upper">Gallery</h1>
            <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb fs-sm wrap">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gallery</li>
                    </ol>
                </nav>
            </div>
        </div>
</section>

<section class="py-10">
    <div class="container">
        <div class="grid lg:grid-cols-3 xl:grid-cols-4 gap-10">
            <div class="lg:col-span-2 xl:col-span-3">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <?php foreach ($trips as $trip) : ?>
                        <div class="album relative">
                            <a href="{{ $trip->galleryLink }}">
                                <div class="relative mb-2">
                                    <img class="block" src="{{ $trip->mediumImageUrl }}" alt="">
                                    <div class="flex absolute" style="left:0.5rem;bottom:-0.5rem;right:0.5rem">
                                        <div class="bg-primary shadow-md px-2 py-1 font-display text-lg text-white uppercase">
                                            {{ $trip->name }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <aside>
                @include('front.elements.enquiry')
            </aside>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script>
    $(function() {
        var session_success_message = '{{ $session_success_message ?? '' }}';
        var session_error_message = '{{ $session_error_message ?? '' }}';
        if (session_success_message) {
          toastr.success(session_success_message);
        }

        if (session_error_message) {
          toastr.danger(session_error_message);
        }

        var enquiry_validator = $("#enquiry-form").validate({
            ignore: "",
            rules: {
                'name': 'required',
                'email': 'required',
                'country': 'required',
                'phone': 'required',
                'message': 'required',
            },
            submitHandler: function(form, event) {
                event.preventDefault();
                if (grecaptcha.getResponse(0)) {
                    var btn = $(form).find('button[type=submit]').attr('disabled', true).html('Sending...');
                    setTimeout(() => {
                        form.submit();
                    }, 500);
                }else{
                    grecaptcha.reset(enquiry_captcha);
                    grecaptcha.execute(enquiry_captcha);
                }
            },
        });
    });

    function onSubmitEnquiry(token) {
        $("#enquiry-form").submit();
        return true;
    }

    let enquiry_captcha;
    var CaptchaCallback = function() {
        enquiry_captcha = grecaptcha.render('inquiry-g-recaptcha', {'sitekey' : '{!! config("constants.recaptcha.sitekey") !!}'});
        // review_captcha = grecaptcha.render('review-g-recaptcha', {'sitekey' : '{!! config("constants.recaptcha.sitekey") !!}'});
    };
</script>
@endpush

