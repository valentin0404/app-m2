<?php

// Se requiere el archivo "user_functions.php"
require_once(__DIR__ . "/../library/user_functions.php");

// Se define un array vacío para almacenar los errores
$errors = [];

// Si el método de solicitud no es POST, se añade un mensaje de error al array de errores
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $errors[] = "Método de solicitud no es POST";
}

// Se comprueba si la variable $errors está vacía
if (empty($errors)) {
    // Se llama a la función my_delete_session y se asigna el resultado a la variable 
    $borrarsesion = my_delete_session();
}

if (!empty($errors)) { // Si el array $errors no está vacío
    $query = http_build_query(["errors" => $errors]); // Crea una cadena de consulta URL-encoded con el array $errors y lo almacena en $query
    header("Location: ./user_login.php" . "?{$query}"); // Redirige al usuario a la página de inicio de sesión con los errores en la cadena de consulta
}