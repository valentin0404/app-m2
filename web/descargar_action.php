<?php

// Se requiere el archivo "user_functions.php"
require_once(__DIR__ . "./../library/ficheros_function.php");

// Se define un array vacío para almacenar los errores
// $errors = [];
// $confirmations = [];

session_start();

// // Si el método de solicitud no es POST, se añade un mensaje de error al array de errores
// if ($_SERVER['REQUEST_METHOD'] != 'POST') {
//     $errors[] = "Método de solicitud no es POST";
//  } else {
    if (empty($_GET['id'])) {
     $errors[] = "No se ha informado el identificador, vuelva a intentarlo";
     }
//  }


 if (empty($errors)) {
    $usuario_email = $_SESSION['user'];
    $id_archivo = $_GET['id'];
    $success = descargar_archivo($usuario_email, $id_archivo);

 }    

?>
  