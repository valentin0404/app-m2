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
    // Si el campo "name" está vacío, se añade un mensaje de error al array de errores
    if (empty($_POST['nombre_archivo'])) {
        $errors[] = "No has introducido el nuevo nombre del archivo";
    }
    if (empty($_POST['categoria'])) {
     $errors[] = "Debes de seleccionar una categoría";
     }
 }


$id_fichero = $_POST['id_fichero'];

if (empty($errors)) { // Comprueba si la variable $errors está vacía
    $usuario_email = $_SESSION['user'];
    $nuevo_nombre = $_POST["nombre_archivo"];
    $nueva_categoria = $_POST["categoria"];
    $usuario_id = obtenerIdUsuario($usuario_email);
    if ($usuario_id) {
        $actualizado = actualizar_fichero($usuario_id, $id_fichero, $nuevo_nombre, $nueva_categoria);
        if ($actualizado) {
            $confirmations[] = "¡El archivo ha sido actualizado correctamente! Redirigiendo a la página de ficheros...";
        } else { // Si el archivo no existe
            $errors[] = "El archivo no ha podido ser actualizado o los datos introducidos son idénticos a los anteriores"; // Agrega un error al array $errors
        }
    } else { // Si el archivo no existe
        $errors[] = "No se ha encontrado tu ID de usuario"; // Agrega un error al array $errors
    }
}

if (!empty($errors)) { // Si el array $errors no está vacío
    $query = http_build_query(["errors" => $errors]); // Crea una cadena de consulta URL-encoded con el array $errors y lo almacena en $query
    header("Location: ./editar.php?id=$id_fichero" . "&{$query}"); // Redirige al usuario a la página de registro con los errores en la cadena de consulta
}

if (!empty($confirmations)) { // Si el array $confirmations no está vacío
    $query = http_build_query(["confirmations" => $confirmations]); // Crea una cadena de consulta URL-encoded con el array $confirmations y lo almacena en $query
    header("Location: ./editar.php?id=$id_fichero" . "&{$query}"); // Redirige al usuario a la página de inicio de sesión con los errores en la cadena de consulta
}
     