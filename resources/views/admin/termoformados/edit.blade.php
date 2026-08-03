@extends('admin.layouts.master')

@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif

<form method="post" action="{{route('admin.termoformados.update',$termoformado->id)}}" enctype="multipart/form-data">
	@csrf
	@method('put')

  <div class="form-group">
    <label for="descripcion">Descripcion</label>
    <textarea class="form-control ckeditor" name="descripcion" id="descripcion" cols="30" rows="10" value="" >{{$termoformado->descripcion}}</textarea>  
  </div>

  {{-- galeria --}}
<div class="form-group col-md-6 my-3 ">
        <label for="galeria">Galería 288x288px (imágenes y videos)</label> <br>
        <input type="file" class="form-control-file" id="galeria" name="galeria[]" multiple accept="image/*,video/mp4,video/avi,video/mov,video/webm">
        @php
            // Normalizar galería a array seguro para evitar errores en foreach
            $galeriaItems = [];
            if (!empty($termoformado->galeria)) {
                if (is_array($termoformado->galeria)) {
                    $galeriaItems = $termoformado->galeria;
                } elseif (is_string($termoformado->galeria)) {
                    $str = trim($termoformado->galeria);
                    // Si parece JSON, intentar decodificar
                    if ((str_starts_with($str, '[') && str_ends_with($str, ']')) || (str_starts_with($str, '"') && str_ends_with($str, '"'))) {
                        $decoded = json_decode($str, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $galeriaItems = $decoded;
                        }
                    }
                    // Si sigue vacío, intentar CSV separado por comas
                    if (empty($galeriaItems)) {
                        $galeriaItems = array_filter(array_map('trim', explode(',', $str)));
                    }
                }
            }
        @endphp

        @if (!empty($galeriaItems))
            <div class="image-gallery d-flex flex-wrap my-5">
                @foreach ($galeriaItems as $key => $galerias)
                    <div class="image-container position-relative mr-2 mb-2" id="image-{{ $key }}">
                        @if(Str::endsWith(strtolower($galerias), ['.mp4', '.avi', '.mov', '.webm']))
                            <video src="{{ media_url($galerias) }}" class="gallery-image" muted loop style="max-width:150px;max-height:150px;object-fit:cover;"></video>
                        @else
                            <img src="{{ media_url($galerias) }}" alt="" class="gallery-image">
                        @endif
                        <button class="btn btn-danger btn-sm delete-image position-absolute" data-id="{{ $termoformado->id }}" data-key="{{ $key }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted">No hay imágenes en la galería.</p>
        @endif
    </div> 


  <hr>
  
  
  <div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-success ">Editar</button>

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
                    url: "{{ route('admin.termoformados.eliminarImagen', ['id' => ':id', 'key' => ':key']) }}".replace(':id', id).replace(':key', key),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#image-' + key).fadeOut('slow', function() {
                                $(this).remove();
                            });
                            toastr.success(response.message || 'Imagen eliminada correctamente');
                        } else {
                            toastr.error(response.message || 'Error al eliminar la imagen');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        toastr.error('Error al eliminar la imagen');
                    }
                });
            }
        });
    });
});

</script>
@endpush
