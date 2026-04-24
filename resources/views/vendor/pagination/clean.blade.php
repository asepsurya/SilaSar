@if ($paginator->hasPages())
    <div class="pagination-wrapper">
        {{-- Showing information --}}
        <div>
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} entries
        </div>

        <div class="pagination-controls">
            {{-- Page detail --}}
            <div class="pagination-page-input">
                <span>Page</span>
                <div class="page-number-box">
                    {{ $paginator->currentPage() }}
                </div>
                <span>of {{ $paginator->lastPage() }}</span>
            </div>

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-btn disabled">Previous</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn">Previous</a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn next">Next</a>
            @else
                <span class="pagination-btn next disabled">Next</span>
            @endif
        </div>
    </div>
@endif
