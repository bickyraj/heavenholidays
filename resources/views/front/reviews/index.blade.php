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
<style>
    .pagination {
        display: flex;
    }
    .pagination li {
        text-align: center;
        display: block;
        height: 20px;
        width: 20px;
    }
</style>
<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>
@endpush
@section('content')
<!-- Hero -->
<section class="hero hero-alt relative">
    <img src="{{ asset('assets/front/img/hero.jpg') }}" alt="">
    <div class="overlay absolute">
        <div class="container ">
            <h1 class="font-display upper">Reviews</h1>
            <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb fs-sm wrap">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reviews</li>
                    </ol>
                </nav>
            </div>
        </div>
</section>

<section class="py-10">
    <div class="container">
        <div class="grid lg:grid-cols-3 xl:grid-cols-4 gap-10">
            <div class="lg:col-span-2 xl:col-span-3">
                <div class="grid lg:grid-cols-1 gap-2 lg:gap-3">
                    @forelse ($reviews as $review)
                        <div class="review p-4" style="border: 2px solid #fbf1f1;">
                            <div class="review__content">
                                <h2 class="mb-2 font-display text-2xl text-primary">{{ $review->title }}</h2>
                                <p>{{ $review->review }}</p>
                            </div>
                            <div class="flex items-center">
                                <div class="mr-2">
                                    <img src="{{ $review->thumbImageUrl }}" alt="">
                                </div>
                                <div>
                                    <div class="font-bold">{{ ucfirst($review->review_name) }}</div>
                                    <div class="text-sm text-gray">{{ $review->review_country }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
                <?php echo $reviews->render(); ?>
            </div>
            <aside>
                <a href="{{ route('front.reviews.create') }}" class="mb-4 btn btn-accent">Write a review</a>
                 <div class="mb-8">


                </div>
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
          toastr.error(session_error_message);
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
            errorPlacement: function(error, element) {
                error.insertAfter(element.closest('.flex'));
                // error.append(element.closest('.form-group'));
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
