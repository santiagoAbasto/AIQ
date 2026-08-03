<!-- resources/views/admin/logos/edit.blade.php -->

@extends('admin.layouts.master')

@section('content')
    <h3>Editar Logo</h3>

    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif

    <form method="post" action="{{ route('admin.logos.update', $logo->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
<div class="row my-5">

  <div class="form-group col-md-4">
      <label for="logo_header">Logo Header (Image · 544 x 159)</label><br>
      <input type="file" class="form-control-file my-3" id="logo_header" name="logo_header"><br>
      <img src="{{media_url($logo->logo_header)}}" class="img-thumbnail w-50">
  </div>

  <div class="form-group col-md-4">
      <label for="logo_headerdos	">Logo Header Dos (Image · 544 x 159)</label><br>
      <input type="file" class="form-control-file my-3" id="logo_headerdos	" name="logo_headerdos	"><br>
      <img src="{{media_url($logo->logo_headerdos	)}}" class="img-thumbnail w-50">
  </div>

  <div class="form-group col-md-4">
      <label for="logo_footer">Logo Footer</label><br>
      <input type="file" class="form-control-file my-3" id="logo_footer" name="logo_footer"><br>
      <img src="{{ media_url($logo->logo_footer) }}" class="img-thumbnail w-50">
  </div>
</div>

        <button type="submit" class="btn btn-success">Actualizar Logo</button>
    </form>
@endsection
