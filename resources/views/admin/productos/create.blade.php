@extends('admin.layouts.master')

@section('content')
{{-- Estilos Select2 --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<h3>Nuevo Producto</h3>
<form method="post" action="{{ route('admin.productos.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="form-group col-md-4">
            <label for="orden">Orden</label>
            <input type="text" class="form-control" id="orden" name="orden">
        </div>
        <div class="form-group col-md-4">
            <label for="titulo">titulo</label>
            <input type="text" class="form-control" id="titulo" name="titulo">
        </div>
        {{-- agregar un input hex para elegir los colores  --}}
        
        <div class="form-group col-md-4">
            <label for="color">Color</label>
            <input type="color" class="form-control" id="color" name="color" value="{{ old('color', '#000000') }}">
        </div>

    </div>



    {{-- Relaciones Categoria / Subcategoria --}}
    <div class="row my-3">
        <div class="col-md-12">
            <label class="font-weight-bold">Categorías y Subcategorías</label>
            <div id="relaciones-container"></div>
            <button type="button" class="btn btn-secondary btn-sm mt-2" id="agregar-relacion">
                <i class="fas fa-plus"></i> Agregar Relación
            </button>
        </div>
    </div>

    {{-- Caracteristica --}}
    <div class="row my-3">
        <div class="form-group col-md-6">
            <label for="caracteristica_id">Característica</label>
            <select class="form-control" id="caracteristica_id" name="caracteristica_id">
                <option value="">Sin característica</option>
                @foreach($caracteristicas as $caracteristica)
                    <option value="{{ $caracteristica->id }}">{{ $caracteristica->titulo }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Template oculto fila relación --}}
    <template id="relacion-template">
        <div class="row align-items-end mb-2 relacion-row border rounded p-2">
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
            <textarea class="form-control ckeditor" name="descripcion" id="descripcion" cols="30" rows="10"></textarea>
        </div>
    </div>
  

    {{-- Productos Relacionados --}}
    <div class="row my-3">
        <div class="form-group col-md-12">
            <label for="relacionados">Productos Relacionados</label>
            <select class="form-control select2" id="relacionados" name="relacionados[]" multiple="multiple">
                @foreach($productos as $prod)
                    <option value="{{ $prod->id }}">{{ $prod->titulo }} ({{ $prod->codigo }})</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row my-3">
        

        <div class="form-group col-md-6 my-4">
            <label for="imagen">Imagen 900x675px</label> <br>
            <input type="file" class="form-control-file" required id="imagen" name="imagen">
        </div>
        <div class="form-group col-md-6 my-4">
            <label for="pdf">pdf </label> <br>
            <input type="file" class="form-control-file"  id="pdf" name="pdf">
        </div>
    </div>


   <div class="row my-4">
        <div class="form-group col-md-12">
            <label for="galeria">Galeria Tamaño · 225 x 225 (imágenes y videos)</label><br>
            <input type="file" class="form-control-file" id="galeria" name="galeria[]" multiple accept="image/*,video/mp4,video/avi,video/mov,video/webm">
        </div>
    </div> 

    {{-- destacado --}}
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="destacado" name="destacado">
        <label class="form-check-label" for="destacado">Destacado</label>
    </div>

    <div class="d-flex justify-content-start">
        <button type="submit" class="btn btn-primary">Agregar</button>
    </div>
</form>
@endsection

@push('scripts')
{{-- Script Select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const subcategoriasData = @json($subcategorias->groupBy('categoria_id'));
    let relacionIndex = 0;

    function agregarFila() {
        const template = document.getElementById('relacion-template');
        const clone = template.content.firstElementChild.cloneNode(true);
        const idx = relacionIndex++;

        clone.querySelector('.select-categoria').name = `relaciones[${idx}][categoria_id]`;
        clone.querySelector('.select-subcategoria').name = `relaciones[${idx}][subcategoria_id]`;

        clone.querySelector('.select-categoria').addEventListener('change', function () {
            const subSelect = clone.querySelector('.select-subcategoria');
            const categoriaId = this.value;
            subSelect.innerHTML = '<option value="">Sin subcategoría</option>';
            if (categoriaId && subcategoriasData[categoriaId]) {
                subcategoriasData[categoriaId].forEach(function (sub) {
                    const opt = document.createElement('option');
                    opt.value = sub.id;
                    opt.textContent = sub.titulo;
                    subSelect.appendChild(opt);
                });
            }
        });

        clone.querySelector('.btn-eliminar-relacion').addEventListener('click', function () {
            clone.remove();
        });

        document.getElementById('relaciones-container').appendChild(clone);
    }

    document.getElementById('agregar-relacion').addEventListener('click', agregarFila);

    // Agregar primera fila automáticamente
    agregarFila();

    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Seleccione una opción",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush


