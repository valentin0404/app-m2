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
    if (empty($_POST['id'])) {
     $errors[] = "No se ha informado el identificador, vuelva a intentarlo";
     }
 }

 if (empty($errors)) {
    $usuario_email = $_SESSION['user'];
    $id_archivo = $_POST['id'];
    $success = eliminar_archivo($usuario_email, $id_archivo);
    if ($success) {
        $confirmations[] = "¡El archivo ha sido eliminado!";
    } else {
        $errors[] = "El archivo NO ha podido ser eliminado"; // Agrega un error al array $errors
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
     

?>
  