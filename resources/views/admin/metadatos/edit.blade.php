<!-- resources/views/metadata/edit.blade.php -->

@extends('admin.layouts.master')

@section('content')
 
        <h1>Editar Metadatos</h1>
        <form method="POST" action="{{ route('admin.metadatos.update', $metadata->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="keyword">Keyword</label>
                <input type="text" class="form-control" id="keyword" name="keyword" value="{{ $metadata->keyword }}">
            </div>

            <div class="form-group my-5">
                <label for="description">Description</label>
                <textarea class="form-control ckeditor" id="description" name="description">{{ $metadata->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="section">Section</label>
                <input type="text" class="form-control" id="section" name="section" value="{{ $metadata->section }}">
            </div>

            <button type="submit" class="btn btn-primary mt-2">Editar </button>
        </form>
 
@endsection
