<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva suscripcion al newsletter</title>
</head>
<body>
    <h2>Nueva suscripcion al newsletter</h2>
    <p><strong>Email:</strong> {{ $newsletter->email }}</p>
    <p><strong>Fecha de alta:</strong> {{ $newsletter->created_at?->format('d/m/Y H:i') }}</p>
</body>
</html>