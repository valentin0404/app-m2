<?php
// Datos de conexión a la base de datos
$host = 'localhost'; // Cambiar si tu base de datos está en un servidor remoto
$port = '3306'; // Puerto predeterminado de MariaDB
$dbname = 'appphp'; // Nombre de tu base de datos
$username = 'root'; // Nombre de usuario de PostgreSQL
$password = 'root'; // Contraseña de PostgreSQL

// Intenta establecer la conexión
try {
    // Cadena de conexión
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
    
    // Crear una nueva instancia de PDO (PHP Data Objects)
    $pdo = new PDO($dsn, $username, $password);
    
    // Configurar el modo de error de PDO para lanzar excepciones en lugar de advertencias
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Mensaje de éxito si la conexión se establece correctamente
    // echo "Conexión exitosa a la base de datos.";
} catch (PDOException $e) {
    // Si hay algún error al conectar, se captura aquí
    // Puedes mostrar el mensaje de error o manejarlo de otra manera
    echo "Error al conectar a la base de datos: " . $e->getMessage();
}
?>
