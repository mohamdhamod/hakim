@if ($paginator->hasPages())
    @php
        $direction = config('languages.' . app()->getLocale() . '.direction') ?? 'ltr';
        $isRtl = $direction === 'rtl';
    @endphp
    <nav aria-label="Pagination Navigation">
        <ul class="pagination pagination-sm pagination-boxed mb-0 justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span class="page-link" aria-hidden="true">
                        <i class="bi {{ $isRtl ? 'bi-chevron-right' : 'bi-chevron-left' }}"></i>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}">
                        <i class="bi {{ $isRtl ? 'bi-chevron-right' : 'bi-chevron-left' }}"></i>
                    </a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}">
                        <i class="bi {{ $isRtl ? 'bi-chevron-left' : 'bi-chevron-right' }}"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span class="page-link" aria-hidden="true">
                        <i class="bi {{ $isRtl ? 'bi-chevron-left' : 'bi-chevron-right' }}"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
