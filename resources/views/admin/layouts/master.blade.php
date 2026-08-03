<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('favicon.ico') }}?v=20260706" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20260706" type="image/x-icon">
    <title>@yield('title', 'Administrador AIQ')</title>

    <!-- CSS de terceros -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!-- CSS personalizado -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <link href="{{ asset('css/setting.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-panel.css') }}?v={{ filemtime(public_path('css/admin-panel.css')) }}" rel="stylesheet">
    @stack('styles')

    <!-- (Opcional) Ajuste mínimo visual del editor -->
    <style>
        .ck-editor__editable {
            min-height: 300px;
        }
    </style>
</head>

<body class="admin-body">
<div class="wrapper">
    @include('admin.layouts.sidebar')

    <div class="main">
        @include('admin.layouts.navbar')

        <div class="container-fluid">
            <main class="main-content">
                @yield('content')
            </main>
        </div>
    </div>
</div>

<!-- JS de terceros -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- CKEditor 5 Classic DEFAULT -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/translations/es.js"></script>

<!-- JS personalizado -->
<script src="{{ asset('js/dashboard.js') }}"></script>

<!-- Toastr -->
<script>
@if (isset($errors) && $errors->any())
    @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
    @endforeach
@endif

@if (Session::has('message'))
    toastr.success("{{ Session::get('message') }}");
@endif
</script>

<!-- Inicialización CKEditor DEFAULT -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Select2
    $('.select2').select2();

    // CKEditor default
    document.querySelectorAll('.ckeditor').forEach(function (element) {
        ClassicEditor
            .create(element, {
                language: 'es'
            })
          
    });

});
</script>

@stack('scripts')
</body>
</html>
