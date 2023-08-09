<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $blog->seo->meta_title ?? '' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- meta tags --}}
    <meta name="description" content="{{ $blog->seo->meta_description ?? '' }}" />
    <meta name="keywords" content="{{ $blog->seo->meta_keywords ?? '' }}" />
    <link rel="canonical" href="{{ $blog->seo->canonical_url ?? '' }}" />
    <meta property="og:title" content="{{ $blog->seo->meta_title ?? '' }}" />
    <meta property="og:url" content="{{ $blog->seo->canonical_url ?? '' }}" />
    <meta property="og:site_name" content="@yield('meta_og_site_name', Setting::get('site_name') ?? '')" />
    <meta property="og:image" content="{{ $blog->image_url }}" />
    <meta property="og:description" content="{{ $blog->seo->meta_description ?? '' }}" />
    <meta property="fb:app_id" content="" />
    <meta name="IndexType" content="trekking in Nepal" />
    <meta name="language" content="EN-US" />
    <meta name="type" content="Trekking" />
    <meta name="classification" content="Trekking, Tour operator in Nepal" />
    <meta name="company" content="" />
    <meta name="author" content="" />
    <meta name="contact person" content="" />
    <meta name="copyright" content="" />
    <meta name="security" content="public" />
    <meta content="all" name="robots" />
    <meta name="document-type" content="Public" />
    <meta name="category" content="Trekking in Nepal" />
    <meta name="robots" content="all,index" />
    <meta name="googlebot" content="INDEX, FOLLOW" />
    <meta name="YahooSeeker" content="INDEX, FOLLOW" />
    <meta name="msnbot" content="INDEX, FOLLOW" />
    <meta name="allow-search" content="Yes" />
    <meta name="doc-rights" content="" />
    <meta name="doc-publisher" content="" />
    <meta name="p:domain_verify" content="" />

    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
    <link rel="canonical" href="" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:label1" content="Est. reading time" />
    <meta name="twitter:data1" content="4 minutes" />
    {{-- end of meta tags --}}

    {{-- fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Source+Sans+Pro:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    {{-- Smartmenus --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/smartmenus@1.1.1/dist/css/sm-core-css.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.0/css/perfect-scrollbar.css">

    <link rel="stylesheet" href="{{ asset('assets/front/css/tw.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/front/css/app.css') }}">
    <link href="{{ asset('assets/vendors/general/toastr/build/toastr.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/front/css/front-style.css') }}">




    {{-- <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script> --}}
    @stack('styles')
    <style>
        [x-cloak] {
            display: none;
        }
    </style>


</head>

<body class="font-body">
    <!-- scrollspy for tour-details page -->

    <!-- Header -- Topbar & Navbar-->
    @include('front.elements.header')
    {{-- end of header --}}

    <div id="topIO"></div>

    @yield('content')

    <!-- Footer -->
    @include('front.elements.footer')
    {{-- end of footer --}}

    <!-- App.js -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/smartmenus@1.1.1/dist/jquery.smartmenus.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.0/dist/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets/vendors/general/toastr/build/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/toastr-option.js') }}" type="text/javascript"></script>
    <script>
        // Initialize jQuery Smartmenus
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    var status = jqXHR.status;
                    if (status == 404) {
                        toastr.warning("Element not found.");
                    } else if (status == 422) {
                        toastr.info(jqXHR.responseJSON.message);
                    }
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
