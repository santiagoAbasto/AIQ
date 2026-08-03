@extends('admin.layouts.master')

@section('content')
<h3>Nuevo Slider</h3>
<form method="post" action="{{ route('admin.slider.store', $seccion) }}" enctype="multipart/form-data"> 
	@csrf
  <div class="row">
    <div class="form-group col-md-6"> 
      <label for="orden">orden</label>
      <input type="text" class="form-control" id="orden" name="orden" >  
    </div>
    <div class="form-group col-md-6">
      <label for="titulo">Titulo</label>
      <input type="text" class="form-control" id="titulo" name="titulo">
    </div> 
  </div>
  <div class="form-group col-md-12">
    <label for="descripcion">Descrpcion</label>
    <textarea class="form-control ckeditor" name="descripcion" id="descripcion" cols="30" rows="10" value="" ></textarea>
    
  </div> 
  <div class="form-group col-md-6">
    <label for="imagen">Imagen o Video (Tamaño recomendado: 1400x600px) </label> <br>
    <input type="file" class="form-control-file mt-3" required id="imagen" name="imagen"> <br>
  </div>
  <div class="form-group col-md-6">
    <label for="activo">Activo</label>
    <select class="form-control" id="activo" name="activo">
      <option value="1">Sí</option>
      <option value="0">No</option>
    </select>
  </div>
  <div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-success ">Agregar</button>

  </div>
</form>
@endsection
