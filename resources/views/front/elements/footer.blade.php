<!-- Newsletter -->
<div class="bg-gray pt-10">
    <div class="mx-auto max-w-5xl pb-2">
        <div class="grid lg:grid-cols-2 gap-8">
            <div>
                <h2 class="mb-2 font-display text-4xl text-balance text-primary">Subscribe for seasonal discounts.</h2>
                <div>Sign up to stay updated with latest offers, recent events and more news.</div>
            </div>
            <div>
                <form class="subscribe-form flex flex-wrap" id="email-subscribe-form">
                    <div class="p-2 bg-white rounded-full overflow-hidden">
                        <label for="emailsub" class="sr-only">Email</label>
                        <input type="email" id="emailsub" class="lg:text-xl p-4 mb-1 lg:mb-0 lg:mr-2 border-0" placeholder="Enter your email" required>
                        <button type="submit" class="btn btn-accent">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <img src="{{ asset('assets/front/img/webpage_art.png') }}" width="1920" height="275" alt="Art representing various natural and cultutal heritages of Nepal"
        class="footer-art w-full h-auto object-cover" loading="lazy">
</div><!-- Newsletter -->

<!-- Footer -->
<footer class="bg-primary text-white">

    <div class="container fs-sm">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="mb-4 col-span-2 lg:col-span-1">
                {{-- <div id="TA_excellent377" class="TA_excellent">
                    <ul id="B53fPW4H" class="TA_links VQZgkV">
                        <li id="7ovBpuTFQ8" class="2srDuwCwKx1"><a target="_blank"
                                href="https://www.tripadvisor.com/Attraction_Review-g293890-d2651131-Reviews-Nature_Trail_Travels_Tours_Trekking_Expeditions_Day_Tours-Kathmandu_Kathmandu_Val.html"><img
                                    src="https://static.tacdn.com/img2/brand_refresh/Tripadvisor_lockup_horizontal_secondary_registered.svg" alt="TripAdvisor" class="widEXCIMG"
                                    id="CDSWIDEXCLOGO" /></a></li>
                    </ul>
                </div>
                <script async src="https://www.jscache.com/wejs?wtype=excellent&amp;uniq=377&amp;locationId=2651131&amp;lang=en_US&amp;display_version=2" data-loadtrk onload="this.loadtrk=true"></script> --}}

                <h1 class="font-display text-2xl text-white">Trekking Destination</h1>
                <ul>
                    @if ($footer1)
                        @foreach ($footer1 as $menu)
                            <li class="text-sm">
                                <a href="{!! $menu->link ? $menu->link : 'javascript:;' !!}">{{ $menu->name }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>

            </div>
            <div class="mb-4 col-span-2 lg:col-span-1">
                <h1 class="font-display text-2xl text-white">Things to do in Nepal</h1>
                <ul>
                    @if ($footer2)
                        @foreach ($footer2 as $menu)
                            <li class="text-sm">
                                <a href="{!! $menu->link ? $menu->link : 'javascript:;' !!}">{{ $menu->name }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="mb-4">
                <h1 class="font-display text-2xl text-white">Quick Links</h1>
                <ul>
                    @if ($footer3)
                        @foreach ($footer3 as $menu)
                            <li class="text-sm">
                                <a href="{!! $menu->link ? $menu->link : 'javascript:;' !!}">{{ $menu->name }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <div class="col-span-2 lg:col-span-1">
                <h1 class="font-display text-2xl text-white">Head Office, Nepal</h1>
                <ul class="icon-list">
                    <li class="flex">
                        <svg class="flex-shrink-0 mr-1">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#locationmarker" />
                        </svg>
                        <span class="text-sm">{{ Setting::get('address') }}</span>
                    </li>
                    <li class="flex">
                        <svg class="flex-shrink-0 mr-1">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#phone" />
                        </svg>
                        <a class="text-sm" href="tel:{{ Setting::get('mobile1') }}">{{ Setting::get('mobile1') }}</a>
                    </li>
                     <li class="flex">
                        <svg class="flex-shrink-0 mr-1">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#phone" />
                        </svg>
                        <a class="text-sm" href="tel:{{ Setting::get('mobile1') }}">{{ Setting::get('telephone') }}</a>
                    </li>
                    <li class="flex">
                        <svg class="flex-shrink-0 mr-1">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#mail" />
                        </svg>
                        <a class="text-sm" href="mailto:{{ Setting::get('email') }}">{{ Setting::get('email') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="container">
            <ul class="social-links flex-wrap mb-4">
                <li class="mb-1">
                    <a href="{{ Setting::get('facebook') }}">
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#facebook" />
                        </svg>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ Setting::get('twitter') }}">
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#twitter" />
                        </svg>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ Setting::get('instagram') }}">
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#instagram" />
                        </svg>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ Setting::get('whatsapp') }}">
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#whatsapp" />
                        </svg>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ Setting::get('viber') }}">
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#viber" />
                        </svg>
                    </a>
                </li>
            </ul>

            <div class="mb-2 affiliations">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <a href="{{ route('front.makeapayment') }}" class="btn btn-accent">Make a Payment</a>
                </div>
            </div>

            {{-- <div class="mb-2 affiliations">
                <ul>
                    <p><a href="#"><img class="lazyload" data-src="{{ asset('assets/front/img/certified1.png') }}" alt="Travel Life"></a></p>
                </ul>
            </div> --}}

            <div class="mb-2 affiliations">
                <div class="mb-2 text-xs">We are affiliated to</div>
                <ul class="flex justify-end gap-2">
                    <li class="p-2">
                        <a href="#"><img src="{{ asset('assets/front/img/ng.svg') }}" class="w-auto h-12" alt="Nepal Government Ministry of Culture, Tourism & Civil Aviation"
                                width="179"height="150" loading="lazy"></a>
                    </li>
                    <li class="p-2">
                        <a href="#"><img src="{{ asset('assets/front/img/ntb.svg') }}" class="w-auto h-12" alt="Nepal Tourism Board" width="148" height="150" loading="lazy"></a>
                    </li>
                    <li class="p-2">
                        <a href="https://www.taan.org.np/"><img src="{{ asset('assets/front/img/taan.svg') }}" class="w-auto h-12" alt="Trekking Agencies' Association of Nepal" width="112"
                                height="150" loading="lazy"></a>
                    </li>
                    <li class="p-2">
                        <a href="#"><img src="{{ asset('assets/front/img/nma.svg') }}" class="w-auto h-12" alt="Nepal Mountaineering Association" width="180" height="150"
                                loading="lazy"></a>
                    </li>
                     <li class="p-2">
                        <a href="https://www.tripadvisor.com/Attraction_Review-g293890-d26451945-Reviews-Haven_Holidays_Pvt_Ltd-Kathmandu_Kathmandu_Valley_Bagmati_Zone_Central_Region.html" target="_blank"><img src="{{ asset('assets/front/img/ta.jpg') }}" class="w-auto h-12" alt="TripAdvisor" width="180" height="150"
                                loading="lazy"></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="bg-primary text-xs text-center pb-20">
        <div class="container md:flex justify-between">
            <div class="mb-2">
                All Contents &copy; 2004 - {{ date('Y') }}. All right Reserved.
            </div>
            {{--
            <div class="mb-4">
                Powered by
                <a href="https://thirdeyesystem.com">Third Eye Systems</a>
            </div>
            --}}
            <div class="payments">
                <img src="{{ asset('assets/front/img/payment.svg') }}" alt="">
            </div>
        </div>
    </div>
     @include('front.elements.scroll-to-top')
    {{-- @include('front.elements.mobile-bottom-navigation') --}}
</footer><!-- Footer -->
@push('scripts')
    <script type="text/javascript">
        $(function() {

            $('#email-subscribe-form').on('submit', function(event) {
                event.preventDefault();
                var form = $(this);
                var formData = form.serialize();

                $.ajax({
                    url: "{{ route('front.email-subscribers.store') }}",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    async: "false",
                    success: function(res) {
                        if (res.status == 1) {
                            toastr.success(res.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        var status = jqXHR.status;
                        if (status == 404) {
                            toastr.warning("Element not found.");
                        } else if (status == 422) {
                            toastr.info(jqXHR.responseJSON.errors.email[0]);
                        }
                    }
                });

            });
        });
    </script>
@endpush
