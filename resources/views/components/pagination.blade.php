@if ($paginator->hasPages())
    <nav class="kh-pagination-wrapper">
        <ul class="kh-pagination">
            {{-- Nút Previous (Quay lại) --}}
            @if ($paginator->onFirstPage())
                <li class="kh-page-item disabled" aria-disabled="true">
                    <span class="kh-page-link">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </span>
                </li>
            @else
                <li class="kh-page-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="kh-page-link" rel="prev">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </a>
                </li>
            @endif

            {{-- Các số trang --}}
            @foreach ($elements as $element)
                {{-- Dấu ba chấm "..." --}}
                @if (is_string($element))
                    <li class="kh-page-item disabled" aria-disabled="true"><span class="kh-page-link">{{ $element }}</span></li>
                @endif

                {{-- Mảng các số trang --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="kh-page-item active" aria-current="page"><span class="kh-page-link">{{ $page }}</span></li>
                        @else
                            <li class="kh-page-item"><a href="{{ $url }}" class="kh-page-link">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Nút Next (Tiếp theo) --}}
            @if ($paginator->hasMorePages())
                <li class="kh-page-item">
                    <a href="{{ $paginator->nextPageUrl() }}" class="kh-page-link" rel="next">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </li>
            @else
                <li class="kh-page-item disabled" aria-disabled="true">
                    <span class="kh-page-link">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif