@extends('admin.layouts.master')

@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<form method="post" action="{{ route('admin.inicio.update', ['id' => $contenido->id]) }}" enctype="multipart/form-data">
    @csrf
    @method('put') <!-- Usando 'put' para la actualización -->

    <div class="form-group">
        <label for="titulo" class="font-weight-bold">Título</label>
        <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $contenido->titulo }}">
    </div>

    <div class="form-group my-4">
        <label for="descripcion" class="font-weight-bold">Descripcion</label>
        <textarea class="form-control ckeditor" name="descripcion" id="descripcion" cols="30" rows="10">{!! $contenido->descripcion !!}</textarea>
    </div>

    <div class="form-group">
        <label for="imagen" class="font-weight-bold">Imagen (tamaño 671 × 580 px)</label><br>
        <input type="file" class="form-control-file my-3" id="imagen" name="imagen"> <br>
        <img src="{{ media_url($contenido->imagen) }}" class="img-thumbnail w-25 mt-4">
    </div>

    <hr>
        <div class="form-group">
            <label for="titulo_banner" class="font-weight-bold">Título marca</label>
            <input type="text" class="form-control" id="titulo_banner" name="titulo_banner" value="{{ $contenido->titulo_banner }}">
        </div>
        <div class="form-group">
        <label for="banner" class="font-weight-bold">Marca (tamaño 230 x 130 px)</label><br>
        <input type="file" class="form-control-file my-3" id="banner" name="banner"> <br>
        <img src="{{ media_url($contenido->banner) }}" class="img-thumbnail w-25 mt-4">
    </div>

<div class="form-group">
            <label for="descripcion_banner" class="font-weight-bold">Título marca</label>
            <input type="text" class="form-control" id="descripcion_banner" name="descripcion_banner" value="{{ $contenido->descripcion_banner }}">
        </div>

          <div class="form-group">
        <label for="banner_dos" class="font-weight-bold">Marca (tamaño 230 x 130 px)</label><br>
        <input type="file" class="form-control-file my-3" id="banner_dos" name="banner_dos"> <br>
        <img src="{{ media_url($contenido->banner_dos) }}" class="img-thumbnail w-25 mt-4">
    </div>
      
    <hr>
    <div class="form-group">
        <label for="titulouno" class="font-weight-bold">Título</label>
        <input type="text" class="form-control" id="titulouno" name="titulouno" value="{{ $contenido->titulouno }}">
    </div>
     <div class="form-group">
        <label for="imagenuna" class="font-weight-bold">imagenuna (tamaño 1900x 1500 px)</label><br>
        <input type="file" class="form-control-file my-3" id="imagenuna" name="imagenuna"> <br>
        <img src="{{ media_url($contenido->imagenuna) }}" class="img-thumbnail w-25 mt-4">
    </div>
    <div class="form-group">
        <label for="titulodos" class="font-weight-bold">Título</label>
        <input type="text" class="form-control" id="titulodos" name="titulodos" value="{{ $contenido->titulodos }}">
    </div>
     <div class="form-group">
        <label for="imagendos" class="font-weight-bold">imagendos (tamaño 1900x 1500 px)</label><br>
        <input type="file" class="form-control-file my-3" id="imagendos" name="imagendos"> <br>
        <img src="{{ media_url($contenido->imagendos) }}" class="img-thumbnail w-25 mt-4">
    </div>
    <div class="form-group">
        <label for="titulotres" class="font-weight-bold">Título</label>
        <input type="text" class="form-control" id="titulotres" name="titulotres" value="{{ $contenido->titulotres }}">
    </div>
 <div class="form-group">
        <label for="imagentres" class="font-weight-bold">imagentres (tamaño 1900x 1500 px)</label><br>
        <input type="file" class="form-control-file my-3" id="imagentres" name="imagentres"> <br>
        <img src="{{ media_url($contenido->imagentres) }}" class="img-thumbnail w-25 mt-4">
    </div>
   

   

    <button type="submit" class="btn btn-primary">Editar</button>
</form>
@endsection

