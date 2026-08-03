@extends('admin.layouts.master')

@section('title', 'Base de conocimiento IA')

@section('content')
<div class="admin-page-hero">
    <div>
        <p class="admin-page-kicker">Zona Clientes · IA</p>
        <h1 class="admin-page-title">Base de conocimiento</h1>
        <p class="admin-page-description">
            Subí PDFs aprobados por AIQ para alimentar el asesor privado de clientes.
        </p>
    </div>
    <a href="{{ route('admin.clientes.ai') }}" class="btn btn-outline-primary">Ver consultas</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('danger'))
    <div class="alert alert-danger">{{ session('danger') }}</div>
@endif
@if(isset($errors) && $errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="row g-4 mb-4">
    <div class="col-12 col-md-3">
        <div class="admin-dashboard-card">
            <div>
                <div class="admin-dashboard-icon"><i class="bi bi-file-earmark-pdf"></i></div>
                <h2>{{ $counts['total'] }}</h2>
                <p>PDFs cargados</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="admin-dashboard-card">
            <div>
                <div class="admin-dashboard-icon"><i class="bi bi-arrow-repeat"></i></div>
                <h2>{{ $counts['processing'] }}</h2>
                <p>En proceso</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="admin-dashboard-card">
            <div>
                <div class="admin-dashboard-icon"><i class="bi bi-check2-circle"></i></div>
                <h2>{{ $counts['ready'] }}</h2>
                <p>Indexados por N8N</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-3">
        <div class="admin-dashboard-card">
            <div>
                <div class="admin-dashboard-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                <h2>{{ $counts['error'] }}</h2>
                <p>Con error</p>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">Subir PDFs al asesor AIQ</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.clientes.knowledge.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="assistant_type" value="tecnico">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <label class="form-label" for="title">Título común opcional</label>
                    <input class="form-control" id="title" name="title" type="text" placeholder="Ej: Fichas técnicas 2026">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label" for="documents">PDFs</label>
                    <input class="form-control" id="documents" name="documents[]" type="file" accept="application/pdf,.pdf" multiple required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="notes">Instrucciones internas</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Ej: Usar como fuente prioritaria para problemas de proceso BOPP."></textarea>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-3">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-cloud-arrow-up me-2"></i>
                    Cargar a base IA
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Documentos cargados</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Uso</th>
                        <th>Estado</th>
                        <th>Subido por</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $document)
                        <tr>
                            <td>
                                <strong>{{ $document->title }}</strong><br>
                                <small>{{ $document->original_name }} · {{ number_format($document->size / 1024, 0) }} KB</small>
                            </td>
                            <td>{{ $document->assistant_label }}</td>
                            <td>
                                @php
                                    $badge = match($document->status) {
                                        'ready' => 'bg-success',
                                        'processing' => 'bg-info',
                                        'error' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">{{ $document->status }}</span>
                            </td>
                            <td>{{ $document->uploader?->name ?: '-' }}</td>
                            <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a class="btn btn-outline-primary btn-sm" href="{{ $document->public_url }}" target="_blank" rel="noopener">
                                    Ver PDF
                                </a>
                                <form action="{{ route('admin.clientes.knowledge.destroy', $document) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('¿Eliminar este PDF de la base IA?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Todavía no hay PDFs cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $documents->onEachSide(1)->links('admin.partials.pagination') }}
    </div>
</div>
@endsection
