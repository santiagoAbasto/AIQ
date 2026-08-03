@extends('admin.layouts.master')

@section('content')
<h3>Nueva Novedad</h3>
<form method="post" action="{{ route('admin.novedades.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">

        <div class="form-group col-md-4">
            <label for="orden">Orden</label>
            <input type="text" class="form-control" id="orden" name="orden">
        </div>
        <div class="form-group col-md-4">
            <label for="titulo">Titulo</label>
            <input type="text" class="form-control" id="titulo" name="titulo">
        </div>
        <div class="form-group col-md-4">
            <label for="categoria">categoria</label>
            <input type="text" class="form-control" id="categoria" name="categoria">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-12">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control ckeditor" name="descripcion" id="descripcion" cols="30" rows="10"></textarea>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6 my-4">
            <label for="imagen">Imagen 900x675px</label> <br>
            <input type="file" class="form-control-file" required id="imagen" name="imagen">
        </div>
        
        {{-- <div class="form-group col-md-6 my-4">
            <label for="galeria">Galería de imágenes</label> <br>
            <input type="file" class="form-control-file" multiple id="galeria" name="galeria[]">
            <small class="form-text text-muted">Puede seleccionar múltiples imágenes para la galería.</small>
        </div> --}}
    </div>

    <div class="d-flex justify-content-start">
        <button type="submit" class="btn btn-primary">Agregar</button>
    </div>
</form>
@endsection
