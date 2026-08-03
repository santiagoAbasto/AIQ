@extends('admin.layouts.master')

@section('content')
<h3>Nueva caracteristica</h3>
<form method="post" action="{{ route('admin.caracteristicas.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row mb-3">
        <div class="form-group col-md-6">
            <label for="orden">Orden</label>
            <input type="text" class="form-control" id="orden" name="orden">
        </div>
      
        <div class="form-group col-md-6">
            <label for="titulo">titulo</label>
            <input type="text" class="form-control" id="titulo" name="titulo">
        </div>   
    </div>

    <div class="row">
       
        {{-- pdf --}}
        <div class="form-group col-md-6">
            <label for="imagen">imagen</label>
            <input type="file" class="form-control" id="imagen" name="imagen">
        </div>
    </div>

    <div class="d-flex justify-content-start">
        <button type="submit" class="btn btn-primary">Agregar</button>
    </div>
</form>
@endsection


