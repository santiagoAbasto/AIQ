

{{-- filepath: c:\laragon\www\sinkevicius\resources\views\admin\bobinas\edit.blade.php --}}
@extends('admin.layouts.master')  

@section('content')
<form method="post" action="{{route('admin.bobinas.update',$bobina->id)}}" enctype="multipart/form-data">
    @csrf
  @method('put')
  <div class="form-group col-md-6">
    <label for="orden">Orden</label>
    <input type="text" class="form-control" id="orden" name="orden" value="{{$bobina->orden}}">   
  </div>

<div class="form-group col-md-6 my-4">
    <label for="titulo">titulo</label>
    <input type="text" class="form-control" id="titulo" name="titulo" value="{{$bobina->titulo}}">   
  </div>
{{-- descripcion --}}
<div class="row">
    <div class="form-group col-md-12">
        <label for="descripcion">Descripción</label>
        <textarea class="form-control ckeditor" name="descripcion" id="descripcion" cols="30" rows="10">{!! $bobina->descripcion !!}</textarea>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label for="descripciondos">Descripción 2</label>
        <textarea class="form-control ckeditor" name="descripciondos" id="descripciondos" cols="30" rows="10">{!! $bobina->descripciondos !!}</textarea>
    </div>
</div>



  <div class="form-group col-md-6 my-4">
    <label for="imagen">Imagen 280x180px</label> <br>
    <input type="file" class="form-control-file" id="imagen" name="imagen">
    @if($bobina->imagen)
        <p>Imagen actual:</p>
        <img src="{{media_url($bobina->imagen)}}" class="img-thumbnail mt-2 w-25">
    @endif
</div>





 <button type="submit" class="btn btn-primary">Editar</button>
</form>


@endsection

@push('scripts')

@endpush