@extends('admin.layouts.master')

@section('content')
<h3>Editar Novedad</h3>
<form method="post" action="{{ route('admin.novedades.update', ['id' => $novedad->id]) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT') {{-- Para indicar que es un método PUT (actualización) --}}
    <div class="row">
        <div class="form-group col-md-6">
            <label for="orden">Orden</label>
            <input type="text" class="form-control" id="orden" name="orden" value="{{ $novedad->orden }}">
        </div>
        <div class="form-group col-md-6">
            <label for="titulo">Titulo</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $novedad->titulo }}">
        </div>

        <div class="form-group col-md-6">
            <label for="categoria">categoria</label>
            <input type="text" class="form-control" id="categoria" name="categoria" value="{{ $novedad->categoria }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-12">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control ckeditor" name="descripcion" id="descripcion" cols="30" rows="10">{{ $novedad->descripcion }}</textarea>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6 my-4">
            <label for="imagen">Imagen 900x675px</label> <br>
            <input type="file" class="form-control-file" id="imagen" name="imagen">
            @if($novedad->imagen)
                <p>Imagen actual:</p>
                <img src="{{media_url($novedad->imagen)}}" class="img-thumbnail mt-4">
            @endif
        </div>
        
        {{-- <div class="form-group col-md-6 my-4">
            <label for="galeria">Galería de imágenes</label> <br>
            <input type="file" class="form-control-file" multiple id="galeria" name="galeria[]">
            <small class="form-text text-muted">Puede seleccionar múltiples imágenes para la galería.</small>
            
            @if($novedad->galeria)
                <p>Imágenes actuales de la galería:</p>
                <div class="row mt-3">
                    @foreach(json_decode($novedad->galeria, true) as $key => $imagen)
                        <div class="col-md-4 mb-3 position-relative" id="image-{{ $key }}">
                            <img src="{{media_url($imagen)}}" class="img-fluid img-thumbnail">
                            <button class="btn btn-danger btn-sm position-absolute delete-image" 
                                style="top: 5px; right: 20px;" 
                                data-id="{{ $novedad->id }}" 
                                data-key="{{ $key }}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div> --}}
    </div>

    <div class="d-flex justify-content-start">
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </div>
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.delete-image').click(function(e) {
        e.preventDefault();
        
        var id = $(this).data('id');
        var key = $(this).data('key');

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/novedades') }}/eliminar-imagen/" + id + "/" + key,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#image-' + key).remove();
                            toastr.success('Imagen eliminada correctamente');
                        } else {
                            toastr.error('Error al eliminar la imagen');
                        }
                    },
                    error: function(response) {
                        toastr.error('Error al eliminar la imagen');
                    }
                });
            }
        });
    });
});
</script>
@endpush
