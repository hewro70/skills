@if ($paginator->hasPages())
    <nav>
        <ul class="pagination justify-content-center mb-0">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link fs-6 py-1 px-2">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link fs-6 py-1 px-2" href="{{ $paginator->previousPageUrl() }}"
                        rel="prev">&lsaquo;</a>
                </li>
            @endif

            @foreach ($elements as $element)
            
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link fs-6 py-1 px-2">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link fs-6 py-1 px-2">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link fs-6 py-1 px-2" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link fs-6 py-1 px-2" href="{{ $paginator->nextPageUrl() }}"
                        rel="next">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link fs-6 py-1 px-2">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
