<?php

// Se requiere el archivo "user_functions.php"
require_once(__DIR__ . "./../library/ficheros_function.php");

// Se define un array vacío para almacenar los errores
$errors = [];
$confirmations = [];

session_start();

// Si el método de solicitud no es POST, se añade un mensaje de error al array de errores
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
   $errors[] = "Método de solicitud no es POST";
} else {
    if ($tamañoArchivo = $_FILES["archivo"]["size"] > 10485760) {
        $errors[] = "El tamaño supera el límite máximo";
    } else { // Si el campo "name" está vacío, se añade un mensaje de error al array de errores
        if (empty($_FILES["archivo"]["name"])) {
            $errors[] = "Debes de subir un archivo";
        }
        if (empty($_POST["categoria"])) {
            $errors[] = "Debes de seleccionar una categoría";
        }
    }
}

if (empty($errors)) { // Comprueba si la variable $errors está vacía
    $nombreArchivo = $_FILES["archivo"]["name"];
    $tamañoArchivo = $_FILES["archivo"]["size"];
    $rutaTemporal = $_FILES["archivo"]["tmp_name"];
    $tipoArchivo = $_FILES["archivo"]["type"];
    $categoria     = $_POST["categoria"]; // Obtiene el valor del campo de entrada "categoria" del formulario enviado por POST
    $usuario_email = $_SESSION['user'];
    $usuario_id = obtenerIdUsuario($usuario_email);
    $exists = archivoExiste($nombreArchivo, $usuario_id); // Comprueba si el archivo ya existe en la base de datos
    if ($exists) { // Si el archivo ya existe
        $errors[] = "El archivo ya existe!"; // Agrega un error al array $errors
    } else { // Si el usuario no existe
        // $confirmations[] = "¡El archivo NO existe!";
        $success = subirArchivo($nombreArchivo, $tamañoArchivo, $rutaTemporal, $categoria, $usuario_id, $tipoArchivo);
        if ($success) { // Si la creación de usuario fue exitosa
            $confirmations[] = "¡El archivo ha sido subido!";
        } else { // Si la creación de usuario falló
            $errors[] = "No ha sido posible subir el archivo"; // Agrega un error al array $errors
        }
    }
} 

if (!empty($errors)) { // Si el array $errors no está vacío
    $query = http_build_query(["errors" => $errors]); // Crea una cadena de consulta URL-encoded con el array $errors y lo almacena en $query
    header("Location: ./ficheros.php" . "?{$query}"); // Redirige al usuario a la página de registro con los errores en la cadena de consulta
}

if (!empty($confirmations)) { // Si el array $confirmations no está vacío
    $query = http_build_query(["confirmations" => $confirmations]); // Crea una cadena de consulta URL-encoded con el array $confirmations y lo almacena en $query
    header("Location: ./ficheros.php" . "?{$query}"); // Redirige al usuario a la página de inicio de sesión con los errores en la cadena de consulta
}
     