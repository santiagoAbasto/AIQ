{{-- filepath: c:\laragon\www\sinkevicius\resources\views\admin\trabajos\create.blade.php --}}
@extends('admin.layouts.master')
@section('content')
<h3>Nuevo Trabajo </h3>
<form method="post" action="{{route('admin.trabajos.store')}}" enctype="multipart/form-data">
    @csrf
    <div class="form-group col-md-6">
      <label for="orden">Orden</label>
      <input type="text" class="form-control" id="orden" name="orden" >
    </div>


    <div class="form-group col-md-6 my-4">
      <label for="titulo">titulo</label>
      <input type="text" class="form-control" id="titulo" name="titulo" >
    </div>
  
     <div class="row">
      <div class="form-group col-md-12">
          <label for="descripcion">Descripción</label>
          <textarea class="form-control ckeditor" name="descripcion" id="descripcion" cols="30" rows="10"></textarea>
      </div> 
  </div>

  <div class="row">
      <div class="form-group col-md-12 my-3">
          <label for="relacionados">Trabajos Relacionados</label>
          <select class="form-control select2" name="relacionados[]" multiple="multiple">
              @foreach($trabajos as $item)
                  <option value="{{ $item->id }}">{{ $item->titulo }}</option>
              @endforeach
          </select>
      </div>
  </div>

    <div class="row">
      <div class="form-group col-md-6 my-4">
          <label for="imagen">Foto 280x180px</label> <br>
          <input type="file" class="form-control-file" required id="imagen" name="imagen">
      </div>
  </div>
{{-- pdf --}}
    <div class="form-group col-md-6 my-4">
      <label for="pdf">Archivo PDF o DOC</label> <br>
      <input type="file" class="form-control-file" id="pdf" name="pdf">
  </div>
  {{-- galeria --}}
    <div class="form-group col-md-6 my-3 ">
          <label for="galeria">Galería 288x288px</label> <br>
          <input type="file" class="form-control-file" id="galeria" name="galeria[]" multiple>
      </div>

  <div class="d-flex justify-content-start">
    <button type="submit" class="btn btn-primary ">Agregar</button>
  </div>
</form>

@endsection
