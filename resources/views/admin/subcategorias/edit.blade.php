@extends('admin.layouts.master')

@section('content')
<h3>Editar Subcategoría</h3>
<form method="post" action="{{ route('admin.subcategorias.update', ['id' => $subcategoria->id]) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row mb-3">
     
        <div class="form-group col-md-4">
            <label for="orden">Orden</label>
            <input type="text" class="form-control" id="orden" name="orden" value="{{ $subcategoria->orden }}">
        </div>
        <div class="form-group col-md-4">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $subcategoria->titulo }}" required>
        </div>
    </div>

    {{-- categoria --}}
    <div class="row mb-3">
        <div class="form-group col-md-6">
            <label for="categoria_id">Categoría</label>
            <select class="form-control" id="categoria_id" name="categoria_id" required>
                <option value="">Seleccione categoría</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ $subcategoria->categoria_id == $categoria->id ? 'selected' : '' }}>{{ $categoria->titulo }}</option>
                @endforeach 
            </select>
        </div>
    </div>

    {{-- imagen --}}

    <div class="row mb-3">
        <div class="form-group col-md-6">
            <label for="imagen">Imagen</label>
            <input type="file" class="form-control" id="imagen" name="imagen">
            @if($subcategoria->imagen)
                <div class="mt-2">
                    <img src="{{ media_url($subcategoria->imagen) }}" alt="{{ $subcategoria->titulo }}" style="max-width: 200px;">
                </div>
            @endif  
        </div>
    </div>

    <div class="d-flex justify-content-start mt-3">
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </div>
</form>
@endsection
