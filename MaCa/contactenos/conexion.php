<?php
// Configuración de la conexión a la base de datos
$host = 'localhost';          // Dirección del servidor (IP de servidor)
$dbname = 'sistema_registro'; // Nombre de la base de datos
$username = 'root';     // Usuario de MySQL
$password = 'seba';  // Contraseña de MySQL

try {
    // Establecer conexión con la base de datos usando PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configurar PDO para que lance excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Si llegamos aquí, la conexión fue exitosa
    // echo "Conexión exitosa a la base de datos."; 
} catch (PDOException $e) {
    // Si ocurre un error en la conexión, se muestra un mensaje de error
    die("Error de conexión: " . $e->getMessage());
}
?>
