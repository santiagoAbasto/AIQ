

{{-- filepath: c:\laragon\www\sinkevicius\resources\views\admin\trabajos\edit.blade.php --}}
@extends('admin.layouts.master')  

@section('content')
<form method="post" action="{{route('admin.trabajos.update',$trabajo->id)}}" enctype="multipart/form-data">
    @csrf
  @method('put')
  <div class="form-group col-md-6">
    <label for="orden">Orden</label>
    <input type="text" class="form-control" id="orden" name="orden" value="{{$trabajo->orden}}">   
  </div>

<div class="form-group col-md-6 my-4">
    <label for="titulo">titulo</label>
    <input type="text" class="form-control" id="titulo" name="titulo" value="{{$trabajo->titulo}}">   
  </div>
{{-- descripcion --}}
<div class="row">
    <div class="form-group col-md-12">
        <label for="descripcion">Descripción</label>
        <textarea class="form-control ckeditor" name="descripcion" id="descripcion" cols="30" rows="10">{!! $trabajo->descripcion !!}</textarea>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12 my-3">
        <label for="relacionados">Trabajos Relacionados</label>
        <select class="form-control select2" name="relacionados[]" multiple="multiple">
            @foreach($trabajos as $item)
                <option value="{{ $item->id }}" 
                    @if(in_array($item->id, $trabajo->relacionados->pluck('id')->toArray())) selected @endif
                >
                    {{ $item->titulo }}
                </option>
            @endforeach
        </select>
    </div>
</div>

  <div class="form-group col-md-6 my-4">
    <label for="imagen">Imagen 280x180px</label> <br>
    <input type="file" class="form-control-file" id="imagen" name="imagen">
    @if($trabajo->imagen)
        <p>Imagen actual:</p>
        <img src="{{media_url($trabajo->imagen)}}" class="img-thumbnail mt-2 w-25">
    @endif
</div>

{{-- pdf --}}
  <div class="form-group col-md-6 my-4">
    <label for="pdf">Archivo PDF o DOC</label> <br>
    <input type="file" class="form-control-file" id="pdf" name="pdf">
    @if($trabajo->pdf)
        <p>Archivo actual: <a href="{{ media_url($trabajo->pdf) }}" target="_blank">Ver archivo</a></p>
    @endif
</div>

{{-- galeria --}}
  <div class="form-group col-md-6 my-3 ">
        <label for="galeria">Galería 288x288px</label> <br>
        <input type="file" class="form-control-file" id="galeria" name="galeria[]" multiple>
        @php
            // Normalizar galería a array seguro para evitar errores en foreach
            $galeriaItems = [];
            if (!empty($trabajo->galeria)) {
                if (is_array($trabajo->galeria)) {
                    $galeriaItems = $trabajo->galeria;
                } elseif (is_string($trabajo->galeria)) {
                    $str = trim($trabajo->galeria);
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
                        <img src="{{ media_url($galerias) }}" alt="" class="gallery-image">
                        <button type="button" class="btn btn-danger btn-sm delete-image position-absolute" data-route="{{ route('admin.trabajos.eliminarImagen', ['id' => $trabajo->id, 'key' => $key]) }}" data-id="{{ $trabajo->id }}" data-key="{{ $key }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted">No hay imágenes en la galería.</p>
        @endif
    </div> 

 <button type="submit" class="btn btn-primary">Editar</button>
</form>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.delete-image').click(function(e) {
                e.preventDefault();

                if (!confirm('¿Estás seguro de que deseas eliminar esta imagen?')) {
                    return;
                }

                var button = $(this);
                var route = button.data('route');
                var container = button.closest('.image-container');

                $.ajax({
                    url: route,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            container.fadeOut(300, function() { $(this).remove(); });
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('Error al eliminar la imagen.');
                    }
                });
            });
        });
    </script>
@endsection