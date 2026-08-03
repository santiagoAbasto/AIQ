@if($paginator->total() > 0)
    <div class="admin-pagination-bar">
        <p class="admin-pagination-summary">
            Mostrando
            <strong>{{ number_format($paginator->firstItem(), 0, ',', '.') }}</strong>
            a
            <strong>{{ number_format($paginator->lastItem(), 0, ',', '.') }}</strong>
            de
            <strong>{{ number_format($paginator->total(), 0, ',', '.') }}</strong>
            resultados
        </p>

        @if($paginator->hasPages())
            <nav aria-label="Navegación de páginas">
                <ul class="pagination admin-pagination mb-0">
                    <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                        @if($paginator->onFirstPage())
                            <span class="page-link" aria-hidden="true">
                                <i class="bi bi-chevron-left"></i>
                                <span class="admin-pagination-label">Anterior</span>
                            </span>
                        @else
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Ir a la página anterior">
                                <i class="bi bi-chevron-left"></i>
                                <span class="admin-pagination-label">Anterior</span>
                            </a>
                        @endif
                    </li>

                    @foreach($elements as $element)
                        @if(is_string($element))
                            <li class="page-item disabled" aria-hidden="true">
                                <span class="page-link">{{ $element }}</span>
                            </li>
                        @endif

                        @if(is_array($element))
                            @foreach($element as $page => $url)
                                <li class="page-item {{ $page === $paginator->currentPage() ? 'active' : '' }}">
                                    @if($page === $paginator->currentPage())
                                        <span class="page-link" aria-current="page">{{ $page }}</span>
                                    @else
                                        <a class="page-link" href="{{ $url }}" aria-label="Ir a la página {{ $page }}">{{ $page }}</a>
                                    @endif
                                </li>
                            @endforeach
                        @endif
                    @endforeach

                    <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                        @if($paginator->hasMorePages())
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Ir a la página siguiente">
                                <span class="admin-pagination-label">Siguiente</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        @else
                            <span class="page-link" aria-hidden="true">
                                <span class="admin-pagination-label">Siguiente</span>
                                <i class="bi bi-chevron-right"></i>
                            </span>
                        @endif
                    </li>
                </ul>
            </nav>
        @endif
    </div>
@endif
