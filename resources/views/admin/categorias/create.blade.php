@extends('admin.layouts.master')

@section('content')
<h3>Nuevo categoria</h3>
<form method="post" action="{{ route('admin.categorias.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row mb-3">
        <div class="form-group col-md-4">
            <label for="orden">Orden</label>
            <input type="text" class="form-control" id="orden" name="orden">
        </div>
      
        <div class="form-group col-md-4">
            <label for="titulo">Titulo</label>
            <input type="text" class="form-control" id="titulo" name="titulo">
        </div>
    </div>

     


    <div class="d-flex justify-content-start">
        <button type="submit" class="btn btn-primary">Agregar</button>
    </div>
</form>
@endsection


