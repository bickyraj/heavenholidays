<ul>
    @foreach ($contents as $key => $header)
        <li><a href="#header-{{ $header['id'] }}">{!! $header['header'] !!}</a></li>
        @if (!empty($header['childs']))
            @include('bickyraj.toc.table', ['contents' => $header['childs']])
        @endif
    @endforeach
</ul>
