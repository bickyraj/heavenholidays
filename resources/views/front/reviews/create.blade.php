@extends('layouts.front_inner')
<?php
  if (session()->has('message')) {
    $session_success_message = session('message');
    session()->forget('message');
  }
?>
@push('styles')
<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>
@endpush
@section('content')

<!-- Hero -->
<section class="hero hero-alt relative">
    <img src="{{ asset('assets/front/img/hero.jpg') }}" alt="">
    <div class="overlay absolute">
        <div class="container ">
            <h1 class="font-display upper">Write Review</h1>
            <div class="breadcrumb-wrapper">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb fs-sm wrap">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('front.reviews.index') }}">Reviews</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Write Review</li>
                    </ol>
                </nav>
            </div>
        </div>
</section>

<section class="py-10">
    <div class="container">
        <div class="grid lg:grid-cols-3 xl:grid-cols-4 gap-10">
            <div class="lg:col-span-2 xl:col-span-3">
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nemo culpa necessitatibus commodi nisi ipsa quia velit, fuga rem accusantium odio quo et sit maxime magnam repellendus, eligendi laudantium dolore non?</p>
                <div class="mb-8">
                    <form id="review-form" action="{{ route('front.reviews.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="photo" class="text-sm">Your Photo *</label>
                            <input type="file" class="form-control" name="image" id="photo" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="name" class="text-sm">Your Name *</label>
                            <input type="text" class="form-control" name="review_name" id="name" placeholder="Name" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="email" class="text-sm">Your E-mail *</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="country" class="text-sm">Country You Are From *</label>
                            <input name="country" id="country" class="form-control" list="countries" placeholder="Country" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="tour" class="text-sm">Tour Package *</label>
                            <select name="trip_id" id="tour" class="form-control" required>
                                <option selected disabled>Tour Package</option>
                                @foreach($trips as $trip)
                                <option value="{{ $trip->id }}">{{ $trip->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label for="title" class="text-sm">Short Title To Your Review *</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="Title" required>
                        </div>
                        <div class="form-group mb-4">
                            <label for="" class="text-sm">Message *</label>
                            <textarea class="form-control block" name="review" id="message" rows="5" placeholder="Message"></textarea>
                        </div>
                        <div class="form-group mb-4" x-data="{value:5}">
                            <label for="" class="text-sm">Rate us</label>
                            <input type="hidden" name="rating" :value="value">
                            <div>
                                <template x-for="i in value">
                                    <svg class="w-6 h-6 text-accent" @click="value=i"><use xlink:href="{{ asset('assets/front/img/sprite.svg' )}}#star" /></svg>
                                </template>
                                <template x-if="value < 5">
                                    <span>
                                        <template x-for="i in (5 - value)">
                                            <svg class="w-6 h-6 text-accent" @click="value=value+i"><use xlink:href="{{ asset('assets/front/img/sprite.svg' )}}#staro" /></svg>
                                        </template>
                                    </span>
                                </template>
                                <span class="text-xs text-gray" x-text="`${value} stars`"></span>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <div id="review-g-recaptcha"
                                  data-sitekey="{{ config('constants.google_recaptcha') }}"
                                  data-callback="onSubmitReview"
                                  data-size="invisible">
                            </div>
                            <input type="hidden" id="review-recaptcha" name="review-recaptcha">
                            <button type="submit" class="btn btn-primary">Send</button>
                        </div>
                    </form>
                </div>

                <datalist id="countries">
                    <option value="Afghanistan">
                    <option value="Albania">
                    <option value="Algeria">
                    <option value="American Samoa">
                    <option value="Andorra">
                    <option value="Angola">
                    <option value="Anguilla">
                    <option value="Antarctica">
                    <option value="Antigua and Barbuda">
                    <option value="Argentina">
                    <option value="Armenia">
                    <option value="Aruba">
                    <option value="Australia">
                    <option value="Austria">
                    <option value="Azerbaijan">
                    <option value="Bahamas">
                    <option value="Bahrain">
                    <option value="Bangladesh">
                    <option value="Barbados">
                    <option value="Belarus">
                    <option value="Belgium">
                    <option value="Belize">
                    <option value="Benin">
                    <option value="Bermuda">
                    <option value="Bhutan">
                    <option value="Bolivia">
                    <option value="Bosnia and Herzegovina">
                    <option value="Botswana">
                    <option value="Bouvet Island">
                    <option value="Brazil">
                    <option value="British Indian Ocean Territory">
                    <option value="Brunei Darussalam">
                    <option value="Bulgaria">
                    <option value="Burkina Faso">
                    <option value="Burundi">
                    <option value="Cambodia">
                    <option value="Cameroon">
                    <option value="Canada">
                    <option value="Cape Verde">
                    <option value="Cayman Islands">
                    <option value="Central African Republic">
                    <option value="Chad">
                    <option value="Chile">
                    <option value="China">
                    <option value="Christmas Island">
                    <option value="Cocos (Keeling) Islands">
                    <option value="Colombia">
                    <option value="Comoros">
                    <option value="Congo">
                    <option value="Congo, The Democratic Republic of The">
                    <option value="Cook Islands">
                    <option value="Costa Rica">
                    <option value="Cote D'ivoire">
                    <option value="Croatia">
                    <option value="Cuba">
                    <option value="Cyprus">
                    <option value="Czech Republic">
                    <option value="Denmark">
                    <option value="Djibouti">
                    <option value="Dominica">
                    <option value="Dominican Republic">
                    <option value="Ecuador">
                    <option value="Egypt">
                    <option value="El Salvador">
                    <option value="Equatorial Guinea">
                    <option value="Eritrea">
                    <option value="Estonia">
                    <option value="Ethiopia">
                    <option value="Falkland Islands (Malvinas)">
                    <option value="Faroe Islands">
                    <option value="Fiji">
                    <option value="Finland">
                    <option value="France">
                    <option value="French Guiana">
                    <option value="French Polynesia">
                    <option value="French Southern Territories">
                    <option value="Gabon">
                    <option value="Gambia">
                    <option value="Georgia">
                    <option value="Germany">
                    <option value="Ghana">
                    <option value="Gibraltar">
                    <option value="Greece">
                    <option value="Greenland">
                    <option value="Grenada">
                    <option value="Guadeloupe">
                    <option value="Guam">
                    <option value="Guatemala">
                    <option value="Guinea">
                    <option value="Guinea-bissau">
                    <option value="Guyana">
                    <option value="Haiti">
                    <option value="Heard Island and Mcdonald Islands">
                    <option value="Holy See (Vatican City State)">
                    <option value="Honduras">
                    <option value="Hong Kong">
                    <option value="Hungary">
                    <option value="Iceland">
                    <option value="India">
                    <option value="Indonesia">
                    <option value="Iran, Islamic Republic of">
                    <option value="Iraq">
                    <option value="Ireland">
                    <option value="Israel">
                    <option value="Italy">
                    <option value="Jamaica">
                    <option value="Japan">
                    <option value="Jordan">
                    <option value="Kazakhstan">
                    <option value="Kenya">
                    <option value="Kiribati">
                    <option value="Korea, Democratic People's Republic of">
                    <option value="Korea, Republic of">
                    <option value="Kuwait">
                    <option value="Kyrgyzstan">
                    <option value="Lao People's Democratic Republic">
                    <option value="Latvia">
                    <option value="Lebanon">
                    <option value="Lesotho">
                    <option value="Liberia">
                    <option value="Libyan Arab Jamahiriya">
                    <option value="Liechtenstein">
                    <option value="Lithuania">
                    <option value="Luxembourg">
                    <option value="Macao">
                    <option value="Macedonia, The Former Yugoslav Republic of">
                    <option value="Madagascar">
                    <option value="Malawi">
                    <option value="Malaysia">
                    <option value="Maldives">
                    <option value="Mali">
                    <option value="Malta">
                    <option value="Marshall Islands">
                    <option value="Martinique">
                    <option value="Mauritania">
                    <option value="Mauritius">
                    <option value="Mayotte">
                    <option value="Mexico">
                    <option value="Micronesia, Federated States of">
                    <option value="Moldova, Republic of">
                    <option value="Monaco">
                    <option value="Mongolia">
                    <option value="Montserrat">
                    <option value="Morocco">
                    <option value="Mozambique">
                    <option value="Myanmar">
                    <option value="Namibia">
                    <option value="Nauru">
                    <option value="Nepal">
                    <option value="Netherlands">
                    <option value="Netherlands Antilles">
                    <option value="New Caledonia">
                    <option value="New Zealand">
                    <option value="Nicaragua">
                    <option value="Niger">
                    <option value="Nigeria">
                    <option value="Niue">
                    <option value="Norfolk Island">
                    <option value="Northern Mariana Islands">
                    <option value="Norway">
                    <option value="Oman">
                    <option value="Pakistan">
                    <option value="Palau">
                    <option value="Palestinian Territory, Occupied">
                    <option value="Panama">
                    <option value="Papua New Guinea">
                    <option value="Paraguay">
                    <option value="Peru">
                    <option value="Philippines">
                    <option value="Pitcairn">
                    <option value="Poland">
                    <option value="Portugal">
                    <option value="Puerto Rico">
                    <option value="Qatar">
                    <option value="Reunion">
                    <option value="Romania">
                    <option value="Russian Federation">
                    <option value="Rwanda">
                    <option value="Saint Helena">
                    <option value="Saint Kitts and Nevis">
                    <option value="Saint Lucia">
                    <option value="Saint Pierre and Miquelon">
                    <option value="Saint Vincent and The Grenadines">
                    <option value="Samoa">
                    <option value="San Marino">
                    <option value="Sao Tome and Principe">
                    <option value="Saudi Arabia">
                    <option value="Senegal">
                    <option value="Serbia and Montenegro">
                    <option value="Seychelles">
                    <option value="Sierra Leone">
                    <option value="Singapore">
                    <option value="Slovakia">
                    <option value="Slovenia">
                    <option value="Solomon Islands">
                    <option value="Somalia">
                    <option value="South Africa">
                    <option value="South Georgia and The South Sandwich Islands">
                    <option value="Spain">
                    <option value="Sri Lanka">
                    <option value="Sudan">
                    <option value="Suriname">
                    <option value="Svalbard and Jan Mayen">
                    <option value="Swaziland">
                    <option value="Sweden">
                    <option value="Switzerland">
                    <option value="Syrian Arab Republic">
                    <option value="Taiwan, Province of China">
                    <option value="Tajikistan">
                    <option value="Tanzania, United Republic of">
                    <option value="Thailand">
                    <option value="Timor-leste">
                    <option value="Togo">
                    <option value="Tokelau">
                    <option value="Tonga">
                    <option value="Trinidad and Tobago">
                    <option value="Tunisia">
                    <option value="Turkey">
                    <option value="Turkmenistan">
                    <option value="Turks and Caicos Islands">
                    <option value="Tuvalu">
                    <option value="Uganda">
                    <option value="Ukraine">
                    <option value="United Arab Emirates">
                    <option value="United Kingdom">
                    <option value="United States">
                    <option value="United States Minor Outlying Islands">
                    <option value="Uruguay">
                    <option value="Uzbekistan">
                    <option value="Vanuatu">
                    <option value="Venezuela">
                    <option value="Viet Nam">
                    <option value="Virgin Islands, British">
                    <option value="Virgin Islands, U.S">
                    <option value="Wallis and Futuna">
                    <option value="Western Sahara">
                    <option value="Yemen">
                    <option value="Zambia">
                    <option value="Zimbabwe">
                </datalist>
            </div>
            <aside>
                @include('front.elements.enquiry')
            </aside>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script>
    var session_success_message = '{{ $session_success_message ?? '' }}';
    if (session_success_message) {
      toastr.success(session_success_message);
    }

    function onSubmitReview(token) {
        $("#review-form").submit();
        return true;
    }

    function onSubmitEnquiry(token) {
        $("#enquiry-form").submit();
        return true;
    }

    var validator = $("#review-form").validate({
        ignore: "",
        rules: {
            'name': 'required',
            'country': 'required',
            'title': 'required',
            'review': 'required',
        },
        submitHandler: function(form, event) {
            event.preventDefault();
            if (grecaptcha.getResponse(1)) {
                var btn = $(form).find('button[type=submit]').attr('disabled', true).html('Submitting...');
                setTimeout(() => {
                    form.submit();
                }, 500);
            }else{
                grecaptcha.reset(review_captcha);
                grecaptcha.execute(review_captcha);
            }
        },
    });

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
            $(form).find('#redirect-url').val('{!! route("front.reviews.index") !!}');
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

    let enquiry_captcha;
    let review_captcha;
    var CaptchaCallback = function() {
        enquiry_captcha = grecaptcha.render('inquiry-g-recaptcha', {'sitekey' : '{!! config("constants.recaptcha.sitekey") !!}'});
        console.log(enquiry_captcha);
        review_captcha = grecaptcha.render('review-g-recaptcha', {'sitekey' : '{!! config("constants.recaptcha.sitekey") !!}'});
    };

</script>
@endpush
