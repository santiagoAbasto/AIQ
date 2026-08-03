@extends('admin.layouts.master')

@section('content')


<h3>Editar producto</h3>
<form method="post" action="{{ route('admin.productos.update', ['id' => $producto->id]) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT') {{-- Para indicar que es un método PUT (actualización) --}}
    <div class="row">
        <div class="form-group col-md-4">
            <label for="orden">Orden</label>
            <input type="text" class="form-control" id="orden" name="orden" value="{{ $producto->orden }}">
        </div>
        <div class="form-group col-md-4">
            <label for="titulo">titulo</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $producto->titulo }}">
        </div>
        <div class="form-group col-md-4">
            <label for="color">Color</label>
            <input type="color" class="form-control" id="color" name="color" value="{{ old('color', $producto->color ?: '#000000') }}">
        </div>
    
        
    </div>
    
    <div class="row">
       
        {{-- Relaciones Categoria / Subcategoria --}}
        <div class="col-md-12 my-3">
            <label class="font-weight-bold">Categorías y Subcategorías</label>
            <div id="relaciones-container"></div>
            <button type="button" class="btn btn-secondary btn-sm mt-2" id="agregar-relacion">
                <i class="fas fa-plus"></i> Agregar Relación
            </button>
        </div>

        {{-- Caracteristica --}}
        <div class="form-group col-md-6 my-3">
            <label for="caracteristica_id">Característica</label>
            <select class="form-control" id="caracteristica_id" name="caracteristica_id">
                <option value="">Sin característica</option>
                @foreach($caracteristicas as $caracteristica)
                    <option value="{{ $caracteristica->id }}" {{ $producto->caracteristica_id == $caracteristica->id ? 'selected' : '' }}>{{ $caracteristica->titulo }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Template oculto fila relación --}}
    <template id="relacion-template">
        <div class="row align-items-end mb-2 relacion-row  rounded p-2">
            <div class="form-group col-md-5 mb-0">
                <label>Categoría <span class="text-danger">*</span></label>
                <select class="form-control select-categoria" name="__CAT__" required>
                    <option value="">Seleccione categoría</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->titulo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-5 mb-0">
                <label>Subcategoría <small class="text-muted">(opcional)</small></label>
                <select class="form-control select-subcategoria" name="__SUB__">
                    <option value="">Sin subcategoría</option>
                </select>
            </div>
            <div class="col-md-2 mb-0">
                <button type="button" class="btn btn-danger btn-sm btn-eliminar-relacion w-100">Eliminar</button>
            </div>
        </div>
    </template>
     <div class="row">
    <div class="form-group col-md-12">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control ckeditor" name="descripcion" id="descripcion" cols="30" rows="10">{{ $producto->descripcion }}</textarea>
        </div>
    </div>


    <div class="row">


 
    {{-- Productos Relacionados --}}
    <div class="row my-3">
        <div class="form-group col-md-12">
            <label for="relacionados">Productos Relacionados</label>
            <select class="form-control select2" id="relacionados" name="relacionados[]" multiple="multiple">
                @foreach($productos as $prod)
                    <option value="{{ $prod->id }}" 
                        {{ $producto->relacionados->contains($prod->id) ? 'selected' : '' }}>
                        {{ $prod->titulo }} ({{ $prod->codigo }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div class="row">
   <div class="form-group col-md-6 my-4">
            <label for="imagen">Imagen 900x675px</label> <br>
        <input type="file" class="form-control-file" id="imagen" name="imagen">
        @if($producto->imagen)
            <p>Imagen actual:</p>
            <img src="{{media_url($producto->imagen)}}" class="img-thumbnail mt-2 w-25">
        @endif
    </div>
        
        <div class="form-group col-md-6 my-4">
            <label for="pdf">PDF (opcional)</label> <br>
            <input type="file" class="form-control-file" id="pdf" name="pdf">
            @if($producto->pdf)
                <p>Archivo actual: <a href="{{ media_url($producto->pdf) }}" target="_blank">Ver PDF</a></p>
            @endif
        </div>
        
     
    </div>
  <div class="form-group col-md-6 my-3 ">
        <label for="galeria">Galería 288x288px (imágenes y videos)</label> <br>
        <input type="file" class="form-control-file" id="galeria" name="galeria[]" multiple accept="image/*,video/mp4,video/avi,video/mov,video/webm">
        @php
            // Normalizar galería a array seguro para evitar errores en foreach
            $galeriaItems = [];
            if (!empty($producto->galeria)) {
                if (is_array($producto->galeria)) {
                    $galeriaItems = $producto->galeria;
                } elseif (is_string($producto->galeria)) {
                    $str = trim($producto->galeria);
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
                        <button class="btn btn-danger btn-sm delete-image position-absolute" data-id="{{ $producto->id }}" data-key="{{ $key }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted">No hay imágenes en la galería.</p>
        @endif
    </div> 
   
    {{-- destacado --}}
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="destacado" name="destacado" {{ $producto->destacado ? 'checked' : '' }}>
        <label class="form-check-label" for="destacado">Destacado</label>
    </div>
    <div class="d-flex justify-content-start">
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </div>
</form>
@endsection
@push('scripts')
{{-- Script Select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const subcategoriasData = @json($subcategorias->groupBy('categoria_id'));
    const relacionesExistentes = @json($producto->relaciones->map(fn($r) => ['categoria_id' => $r->categoria_id, 'subcategoria_id' => $r->subcategoria_id]));
    let relacionIndex = 0;

    function agregarFila(catId = null, subId = null) {
        const template = document.getElementById('relacion-template');
        const clone = template.content.firstElementChild.cloneNode(true);
        const idx = relacionIndex++;

        const catSelect = clone.querySelector('.select-categoria');
        const subSelect = clone.querySelector('.select-subcategoria');

        catSelect.name = `relaciones[${idx}][categoria_id]`;
        subSelect.name = `relaciones[${idx}][subcategoria_id]`;

        function cargarSubcategorias(categoriaId, selected = null) {
            subSelect.innerHTML = '<option value="">Sin subcategoría</option>';
            if (categoriaId && subcategoriasData[categoriaId]) {
                subcategoriasData[categoriaId].forEach(function (sub) {
                    const opt = document.createElement('option');
                    opt.value = sub.id;
                    opt.textContent = sub.titulo;
                    if (selected && sub.id == selected) opt.selected = true;
                    subSelect.appendChild(opt);
                });
            }
        }

        catSelect.addEventListener('change', function () {
            cargarSubcategorias(this.value);
        });

        // Pre-cargar valores existentes
        if (catId) {
            catSelect.value = catId;
            cargarSubcategorias(catId, subId);
        }

        clone.querySelector('.btn-eliminar-relacion').addEventListener('click', function () {
            clone.remove();
        });

        document.getElementById('relaciones-container').appendChild(clone);
    }

    document.getElementById('agregar-relacion').addEventListener('click', function() {
        agregarFila();
    });

    // Cargar relaciones existentes o una fila vacía si no hay ninguna
    if (relacionesExistentes.length > 0) {
        relacionesExistentes.forEach(function(rel) {
            agregarFila(rel.categoria_id, rel.subcategoria_id);
        });
    } else {
        agregarFila();
    }

$(document).ready(function() {
    // Inicializar Select2
    $('.select2').select2({
        placeholder: "Seleccione productos relacionados",
        allowClear: true,
        width: '100%'
    });

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
                    url: "{{ route('admin.productos.eliminarImagen', ['id' => ':id', 'key' => ':key']) }}".replace(':id', id).replace(':key', key),
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