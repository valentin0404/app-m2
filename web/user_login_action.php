<?php

// Se requiere el archivo "user_functions.php"
require_once(__DIR__ . "/../library/user_functions.php");

// Se define un array vacío para almacenar los errores
$errors = [];

// Si el método de solicitud no es POST, se añade un mensaje de error al array de errores
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $errors[] = "Método de solicitud no es POST";
 } else {
    // Si el campo "email" está vacío, se añade un mensaje de error al array de errores
    if (empty($_POST["email"])) {
        $errors[] = "El campo email es obligatorio";
    }
    // Si el campo "password" está vacío, se añade un mensaje de error al array de errores
    if (empty($_POST["password"])) {
        $errors[] = "El campo contraseña es obligatorio";
    }
}

// Comprueba si la variable $errors está vacía
if (empty($errors)) {
    $email    = $_POST["email"]; // Obtiene el valor del campo de entrada "email" del formulario enviado por POST
    $password = $_POST["password"]; // Obtiene el valor del campo de entrada "password" del formulario enviado por POST

    // Se llama a la función generate_session con los valores de $email y $password como argumentos y se asigna el resultado a la variable $hasSession
    $hasSession = generate_session($email, $password);

    // Si la variable $hasSession es falsa, se agrega un mensaje de error a la variable $errors
    if (!$hasSession) {
        $errors[] = "Credenciales no válidas";
    }
    // Si la variable $hasSession es verdadera, se redirecciona al usuario a la página "image_create.php"
    else {
        header("Location: ./index.php");
    } 

}

if (!empty($errors)) { // Si el array $errors no está vacío
    $query = http_build_query(["errors" => $errors]); // Crea una cadena de consulta URL-encoded con el array $errors y lo almacena en $query
    header("Location: ./user_login.php" . "?{$query}"); // Redirige al usuario a la página de inicio de sesión con los errores en la cadena de consulta
}