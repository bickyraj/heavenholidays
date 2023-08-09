<!-- Nav -->
<nav :class="{ show: mobilenavOpen, flex: true }">
    <ul id="main-nav" class="absolute left-0 right-0 sm sm-simple top-full lg:static">
        @if ($menus)
            @foreach ($menus as $menu)
                <li>
                    <a href="{!! $menu->link ? $menu->link : 'javascript:;' !!}" class="text-primary">{{ $menu->name }}</a>
                    @if (iterator_count($menu->children))
                        @if ($menu->mega_menu)
                            @include('front.elements.mega_menu', ['menu' => $menu])
                        @else
                            <ul>
                                @foreach ($menu->children()->orderBy('display_order', 'asc')->get() as $child)
                                    <li>
                                        <a href="{!! $child->link ? $child->link : 'javascript:;' !!}">{{ $child->name }}</a>
                                        @if (iterator_count($child->children))
                                            @include('front.elements.child-menu', [
                                                'children' => $child->children()->orderBy('display_order', 'asc')->get(),
                                                'n_count' => 2,
                                            ])
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @endif
                </li>
            @endforeach
        @endif
    </ul>
</nav><!-- Nav -->
@push('scripts')
    <script>
        //
        // Initialize jQuery Smartmenus
        $(function() {
            $('#main-nav').smartmenus();
        });
    </script>
@endpush
