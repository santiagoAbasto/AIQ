@extends('admin.layouts.master')

@section('content')
<h3>Editar categoria</h3>
<form method="post" action="{{ route('admin.categorias.update', ['id' => $categorias->id]) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT') {{-- Para indicar que es un método PUT (actualización) --}}
    <div class="row">
        <div class="form-group col-md-6">
            <label for="orden">Orden</label>
            <input type="text" class="form-control" id="orden" name="orden" value="{{ $categorias->orden }}">
        </div>
        
        <div class="form-group col-md-6">
            <label for="titulo">titulo</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $categorias->titulo }}">
        </div>

        
        
    </div>


    

    

   

    <div class="d-flex justify-content-start">
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </div>
</form>
@endsection
