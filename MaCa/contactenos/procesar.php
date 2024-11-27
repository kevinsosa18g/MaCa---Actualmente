<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar y sanitizar el nombre
    $nombre = trim($_POST['name']); //trim() elimina espacios en blanco adicionales
    if (empty($nombre) || !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) { // preg_match() valida que tenga letras espacios y caracteres especiales en español
        die("El nombre no es válido.");
    }

    // Validar y sanitizar el correo electrónico
    $correo = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); // filter_var() asegura que el correo sea válido.
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) { // FILTER_SANITIZE_EMAIL elimina caracteres no permitidos.
        die("El correo electrónico no es válido.");
    }

    // Validar la contraseña - Se valida que tenga al menos 8 caracteres, una mayúscula y un número.
    $contraseña = $_POST['password'];
    if (empty($contraseña) || strlen($contraseña) < 8 || 
        !preg_match("/[A-Z]/", $contraseña) || !preg_match("/[0-9]/", $contraseña)) {
        die("La contraseña debe tener al menos 8 caracteres, incluir una mayúscula y un número.");
    }

    // Hashear la contraseña (opcional, pero recomendado si almacenas la contraseña en una base de datos)
    $contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);

    // Insertar los datos en la base de datos
    try {
        $sql = "INSERT INTO usuarios (nombre, correo, contraseña) VALUES (:nombre, :correo, :contraseña)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':correo' => $correo,
            ':contraseña' => $contraseñaHash
        ]);
        echo "Registro exitoso en la base de datos.";
    } catch (PDOException $e) {
        die("Error al insertar los datos: " . $e->getMessage());
    }

    // Configurar el correo
    $destinatario = "kevinsosa18k@gmail.com"; 
    $asunto = "Nuevo Registro de Usuario";
    $mensaje = "Se ha registrado un nuevo usuario:\n\n";
    $mensaje .= "Nombre: $nombre\n";
    $mensaje .= "Correo Electrónico: $correo\n";
    $mensaje .= "Contraseña (hash): $contraseñaHash\n"; 

    $cabeceras = "From: no-reply@museoacieloabiertosf.com.ar\r\n"; 
    $cabeceras .= "Reply-To: $correo\r\n";

    // Enviar el correo
    if (mail($destinatario, $asunto, $mensaje, $cabeceras)) {
        echo "Los datos se enviaron a la empresa por correo.";
    } else {
        echo "Error al enviar los datos por correo.";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>

