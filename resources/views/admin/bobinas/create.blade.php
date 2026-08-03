{{-- filepath: c:\laragon\www\sinkevicius\resources\views\admin\bobinas\create.blade.php --}}
@extends('admin.layouts.master')
@section('content')
<h3>Nuevo bobina</h3>
<form method="post" action="{{route('admin.bobinas.store')}}" enctype="multipart/form-data">
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
        <div class="form-group col-md-12">
             <label for="descripciondos">Descripción 2</label>
             <textarea class="form-control ckeditor" name="descripciondos" id="descripciondos" cols="30" rows="10"></textarea>
        </div>
    </div>  

    <div class="row">
      <div class="form-group col-md-6 my-4">
          <label for="imagen">imagen 280x180px</label> <br>
          <input type="file" class="form-control-file" required id="imagen" name="imagen">
      </div>
  </div>


  <div class="d-flex justify-content-start">
    <button type="submit" class="btn btn-primary ">Agregar</button>
  </div>
</form>

@endsection
