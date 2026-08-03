@extends('admin.layouts.master')

@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<form method="post" action="{{route('admin.contacto.update',$contacto->id)}}" enctype="multipart/form-data">
	@csrf
  @method('put')


  <div class="form-group col-md-12">
    <label for="mapa">Mapa</label>
    <textarea class="form-control ckeditor" name="mapa"  id="mapa" cols="30" rows="10" value="" >{!!$contacto->mapa!!}</textarea>
  </div>

    <div class="form-group">
    <label for="enlace">enlace </label>
    <input type="text" class="form-control" id="enlace" name="enlace" value="{{$contacto->enlace}}">
  </div>
  <div class="form-group">
    <label for="direccion">Direccion </label>
    <input type="text" class="form-control" id="direccion" name="direccion" value="{{$contacto->direccion}}">
  </div>
  <div class="form-group">
    <label for="whatsapp">Whatsapp (+549)</label>
    <input type="tel" class="form-control" id="whatsapp" name="whatsapp" pattern="^\+549\d{10}$" value="{{$contacto->whatsapp}}">
    <small class="form-text text-muted">Ingrese el número en el formato +549 seguido de 10 dígitos.</small>
  </div>
   <div class="form-group">
    <label for="telefono"> Celular</label>
    <input type="tel" class="form-control" id="telefono" name="telefono" value="{{$contacto->telefono}}">
  </div>
 
   <div class="form-group">
    <label for="correo">Correo </label>
    <input type="email" class="form-control" id="correo" name="correo" value="{{$contacto->correo}}">
  </div>
 <button type="submit" class="btn btn-success">Editar</button>
</form>


@endsection