@extends('admin.layouts.master')

@section('content')
@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
@endif
<form method="post" action="{{ route('admin.contenido_bobina.update', ['id' => $contenidoBobina->id]) }}" enctype="multipart/form-data">
    @csrf
    @method('put') <!-- Usando 'put' para la actualización -->

   <div class="form-group my-4">
        <label for="descripcion" class="font-weight-bold">Descripcion</label>
        <textarea class="form-control ckeditor" name="descripcion" id="descripcion" cols="30" rows="10">{!! $contenidoBobina->descripcion !!}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
</form>
@endsection