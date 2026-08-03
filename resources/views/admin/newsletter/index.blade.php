@extends('admin.layouts.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Suscripciones al Newsletter</h1>
    @if(!empty($newsletterEmails))
    <div class="mb-3">
        <a class="btn btn-primary" href="mailto:?bcc={{ urlencode($newsletterEmails) }}">
            <i class="fas fa-envelope me-2"></i>Enviar correo a todos
        </a>
    </div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:80px;">#</th>
                            <th>Email</th>
                            <th class="text-center" style="width:180px;">Fecha de alta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($newsletter as $index => $suscripcion)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $suscripcion->email }}</td>
                            <td class="text-center">{{ $suscripcion->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Sin registros por el momento.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection