@php
    $adminTitle = trim($__env->yieldContent('title', 'Panel de administración'));
    $adminUser = Auth::user();
    $adminName = $adminUser?->name ?: 'Administrador';
    $adminInitials = collect(explode(' ', trim($adminName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');
@endphp

<nav class="navbar navbar-expand-lg topbar">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-3">
            <button class="button-toggle-menu" id="sidebarToggle" type="button" aria-label="Abrir o cerrar menú">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div>
                <div class="admin-topbar-eyebrow">Administrador</div>
                <h1 class="admin-topbar-title">{{ $adminTitle }}</h1>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <a class="admin-topbar-action" href="{{ route('index') }}" target="_blank" rel="noopener">
                <i class="bi bi-box-arrow-up-right me-2"></i>
                Ver sitio
            </a>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="admin-user-button nav-link dropdown-toggle"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <span class="d-none d-md-block text-end">
                            <span class="admin-user-name d-block">{{ $adminName }}</span>
                            <span class="admin-user-role d-block">{{ $adminUser?->role }}</span>
                        </span>
                        <span class="admin-user-avatar">{{ $adminInitials ?: 'A' }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('index') }}" target="_blank" rel="noopener">
                                <i class="bi bi-window me-2"></i>
                                Abrir web pública
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Cerrar sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
