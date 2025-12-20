@if ($paginator->hasPages())
    <nav style="display: flex; align-items: center; justify-content: center; gap: 20px;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
            <span class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')" style="padding: 8px 16px; color: #6c757d; text-decoration: none; pointer-events: none;">
                @lang('pagination.previous')
            </span>
            @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')" style="padding: 8px 16px; color: #3498db; text-decoration: none;">@lang('pagination.previous')</a>
            @endif

        {{-- Page Info --}}
        <span style="color: #2c3e50; font-size: 14px;">
            Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}
        </span>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')" style="padding: 8px 16px; color: #3498db; text-decoration: none;">@lang('pagination.next')</a>
            @else
            <span class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')" style="padding: 8px 16px; color: #6c757d; text-decoration: none; pointer-events: none;">
                @lang('pagination.next')
            </span>
            @endif
    </nav>
@endif
