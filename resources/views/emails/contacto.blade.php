<!-- resources/views/emails/contacto.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Mensaje de Contacto</title>
</head>
<body>
    <h2>Nuevo Mensaje de Contacto</h2>
    <p><strong>Nombre:</strong> {{ $data['name'] }}</p>
    <p><strong>Nombre:</strong> {{ $data['surname'] }}</p>
    <p><strong>Telefono:</strong> {{ $data['phone'] }}</p>
    <p><strong>Correo Electrónico:</strong> {{ $data['email'] }}</p>
    <p><strong>Mensaje:</strong> {{ $data['message'] }}</p>
</body>
</html>
