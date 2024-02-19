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
        <img src="{{ asset('assets/front/img/hero.jpg') }}" alt="Haven Holidays Nepals" style="border-radius: 0px;height: 500px;">
        <div class="overlay absolute">
            <div class="container ">
                <h1 class="font-display upper">Our Team</h1>
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb fs-sm wrap">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Our Team</li>
                        </ol>
                    </nav>
                </div>
            </div>
    </section>

    <section class="py-10">
        <div class="container" x-data="{ active: 'administration' }">
            <div class="grid lg:grid-cols-3 xl:grid-cols-4 gap-10">
                <div class="lg:col-span-2 xl:col-span-3">
                    <button :class="{ 'btn': true, 'btn-accent': active === 'administration', 'btn-primary': active !== 'administration' }" @click="active='administration'">Administration</button>
                    <button :class="{ 'btn': true, 'btn-accent': active === 'representatives', 'btn-primary': active !== 'representatives' }" @click="active='representatives'">Representatives</button>
                    <button :class="{ 'btn': true, 'btn-accent': active === 'tourguides', 'btn-primary': active !== 'tourguides' }" @click="active='tourguides'">Tour Guides</button>

                    <div x-show="active==='administration'">
                        <div class="grid gap-2 lg:gap-3 pt-8">
                            @if ($administrations)
                                @foreach ($administrations as $item)
                                    @include('front.elements.team_card')
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div x-show="active==='representatives'">
                        <div class="grid gap-2 lg:gap-3 pt-8">
                            @if ($representatives)
                                @foreach ($representatives as $item)
                                    @include('front.elements.team_card')
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div x-show="active==='tourguides'">
                        <div class="grid gap-2 lg:gap-3 pt-8">
                            @if ($tour_guides)
                                @foreach ($tour_guides as $item)
                                    @include('front.elements.team_card')
                                @endforeach
                            @endif
                        </div>
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
                    } else {
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
            enquiry_captcha = grecaptcha.render('inquiry-g-recaptcha', {
                'sitekey': '{!! config('constants.recaptcha.sitekey') !!}'
            });
            // review_captcha = grecaptcha.render('review-g-recaptcha', {'sitekey' : '{!! config('constants.recaptcha.sitekey') !!}'});
        };
    </script>
@endpush
