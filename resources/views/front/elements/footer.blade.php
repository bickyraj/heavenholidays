{{-- Newsletter --}}
<div class="py-10 bg-light">
    <div class="container">
        <div class="grid gap-8 lg:grid-cols-2">
            <div>
                <h2 class="mb-2 text-4xl font-bold font-display text-primary">Join our Newsletter</h2>
                <div>Sign up today and get special offer</div>
            </div>
            <div>
                <form class="flex" id="email-subscribe-form">
                    <label for="emailsub" class="sr-only">Email</label>
                    <div class="py-2">
                        <input type="email" id="emailsub" name="email" class="px-8 py-4 border-2 border-r-0 rounded-l-full lg:w-96 lg:text-lg border-accent" placeholder="Enter your email"
                            required>
                    </div>
                    <button type="submit" class="relative px-4 py-2 text-gray-600 rounded-full -left-10 bg-accent hover:bg-accent-d">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-6" viewBox="0 0 512 512" fill="currentColor">
                            <path
                                d="M16.1 260.2c-22.6 12.9-20.5 47.3 3.6 57.3L160 376V479.3c0 18.1 14.6 32.7 32.7 32.7c9.7 0 18.9-4.3 25.1-11.8l62-74.3 123.9 51.6c18.9 7.9 40.8-4.5 43.9-24.7l64-416c1.9-12.1-3.4-24.3-13.5-31.2s-23.3-7.5-34-1.4l-448 256zm52.1 25.5L409.7 90.6 190.1 336l1.2 1L68.2 285.7zM403.3 425.4L236.7 355.9 450.8 116.6 403.3 425.4z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>{{-- Newsletter --}}

{{-- Footer --}}
<footer class="text-white bg-primary">
    <div class="container fs-sm">
        <div class="grid grid-cols-2 gap-4 mb-8 lg:gap-8 lg:grid-cols-4">
            <div>
               

                <h2 class="text-xl font-display">Nepal Tour</h2>
                <ul class="mt-8">
                    @if ($footer1)
                        @foreach ($footer1 as $menu)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-light" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"></path>
                                </svg>
                                <a href="{!! $menu->link ? $menu->link : 'javascript:;' !!}">{{ $menu->name }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>

            </div>
            <div>
                <h2 class="text-xl font-display">Things to do in Nepal</h2>
                <ul class="mt-8">
                    @if ($footer2)
                        @foreach ($footer2 as $menu)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-light" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"></path>
                                </svg>
                                <a href="{!! $menu->link ? $menu->link : 'javascript:;' !!}">{{ $menu->name }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div>
                <h2 class="text-xl font-display">Quick Links</h2>
                <ul class="mt-8">
                    @if ($footer3)
                        @foreach ($footer3 as $menu)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-light" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"></path>
                                </svg>
                                <a href="{!! $menu->link ? $menu->link : 'javascript:;' !!}">{{ $menu->name }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
            <div class="col-span-2 lg:col-span-1">
                <h2 class="text-2xl font-display">{{ Setting::get('site_name') }}</h2>
                <ul class="mt-4 icon-list">
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
            <ul class="flex-wrap mb-4 social-links">
                <li class="mb-1">
                    <a href="{{ Setting::get('facebook') }}" target="_blank" class="p-2 hover:text-gray-600 bg-primary-d hover:bg-accent">
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#facebook" />
                        </svg>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ Setting::get('twitter') }}" target="_blank" class="p-2 hover:text-gray-600 bg-primary-d hover:bg-accent">
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#twitter" />
                        </svg>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ Setting::get('instagram') }}" target="_blank" class="p-2 hover:text-gray-600 bg-primary-d hover:bg-accent">
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#instagram" />
                        </svg>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ Setting::get('whatsapp') }}" target="_blank" class="p-2 hover:text-gray-600 bg-primary-d hover:bg-accent">
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#whatsapp" />
                        </svg>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ Setting::get('viber') }}" target="_blank" class="p-2 hover:text-gray-600 bg-primary-d hover:bg-accent">
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#viber" />
                        </svg>
                    </a>
                </li>
            </ul>
            {{--   <div class=""
            <a class="btn btn-accent" href="{{ route('front.payment') }}">Online Payment</a>
        </div> --}}
            <div class="mb-2 affiliations">
                <div class="mb-2 text-xs">We are affiliated to</div>
                <ul>
                    <li class="p-2 mr-1"><a href="#"><img class="lazy" src="{{ asset('assets/front/img/ng.png') }}"
                                alt="Nepal Government Ministry of Culture, Tourism & Civil Aviation"></a>
                    </li>
                    <li class="p-2 mr-1"><a href="#"><img class="lazy" src="{{ asset('assets/front/img/ntb.svg') }}" alt="Nepal Tourism Board"></a></li>
                    <li class="p-2 mr-1"><a href="https://www.taan.org.np/"><img class="lazy" src="{{ asset('assets/front/img/taan.svg') }}" alt="Trekking Agencies' Association of Nepal"></a>
                    </li>
                    <li class="p-2"><a href="#"><img class="lazy" src="{{ asset('assets/front/img/nma.svg') }}" alt="Nepal Mountaineering Association"></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="justify-between px-4 py-10 mx-auto text-xs border-t border-dashed max-w-7xl md:flex border-t-teal-500">
        <div class="mb-2">
            &copy; {{ date('Y') }}. All right Reserved.
        </div>
        <div class="mb-4">
            Powered by
            <a href="https://thirdeyesystem.com">Third Eye Systems</a>
        </div>
        <div class="payments">
            <img src="{{ asset('assets/front/img/payment.svg') }}" alt="">
        </div>
    </div>
</footer>{{-- Footer --}}
{{--
<div class="footer-tab">
    <ul class="flex-wrap mb-4 social-links">
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
                 <li class="mb-1">
                    <a href="tel:{{ Setting::get('mobile1') }}">
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#phone" />
                        </svg>
                    </a>
                </li>
                <li class="mb-1">
                    <a href="#">
                        Book a Trip
                    </a>
                </li>
                 
            </ul>
</div>
--}}
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
