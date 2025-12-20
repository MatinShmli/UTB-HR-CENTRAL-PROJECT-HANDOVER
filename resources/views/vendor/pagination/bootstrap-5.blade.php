@if ($paginator->hasPages())
    <nav class="d-flex justify-content-center align-items-center" style="gap: 20px;">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
            <span class="page-link disabled" aria-disabled="true" style="pointer-events: none; color: #6c757d;">
                @lang('pagination.previous')
            </span>
                @else
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="text-decoration: none;">@lang('pagination.previous')</a>
                @endif

        {{-- Page Info --}}
        <span class="small text-muted">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </span>

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" style="text-decoration: none;">@lang('pagination.next')</a>
                    @else
            <span class="page-link disabled" aria-disabled="true" style="pointer-events: none; color: #6c757d;">
                @lang('pagination.next')
            </span>
                    @endif
    </nav>
@endif
