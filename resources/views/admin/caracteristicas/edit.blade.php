@extends('admin.layouts.master')

@section('content')
<h3>Editar característica</h3>
<form method="post" action="{{ route('admin.caracteristicas.update', ['id' => $caracteristica->id]) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT') {{-- Para indicar que es un método PUT (actualización) --}}
    <div class="row">
        <div class="form-group col-md-6">
            <label for="orden">Orden</label>
            <input type="text" class="form-control" id="orden" name="orden" value="{{ $caracteristica->orden }}">
        </div>
        
        <div class="form-group col-md-6">
            <label for="titulo">titulo</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $caracteristica->titulo }}">
        </div>
        
    </div>

<div class="row">

    
    {{-- imagen --}}
    <div class="form-group col-md-6 my-3">
        <label for="imagen">imagen</label>
        <input type="file" class="form-control" id="imagen" name="imagen">
        @if($caracteristica->imagen)
            <img src="{{ media_url($caracteristica->imagen) }}" alt="imagen" class="img-thumbnail mt-2" style="max-width: 200px;">
        @endif 
     
    </div>  
</div>

  

    <div class="d-flex justify-content-start">
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </div>
</form>
@endsection
