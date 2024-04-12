<?php

// Se requiere el archivo "user_functions.php"
require_once(__DIR__ . "./../library/user_functions.php");

// Se define un array vacío para almacenar los errores
$errors = [];
$confirmations = [];

// Si el método de solicitud no es POST, se añade un mensaje de error al array de errores
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
   $errors[] = "Método de solicitud no es POST";
} else {
   // Si el campo "name" está vacío, se añade un mensaje de error al array de errores
   if (empty($_POST["fname"])) {
       $errors[] = "El campo nombre es obligatorio";
   }
   if (empty($_POST["lname"])) {
    $errors[] = "El campo apellidos es obligatorio";
    }
   // Si el campo "email" está vacío, se añade un mensaje de error al array de errores
   if (empty($_POST["email"])) {
       $errors[] = "El campo email es obligatorio";
   }
   // Si el campo "password" está vacío, se añade un mensaje de error al array de errores
   if (empty($_POST["password"])) {
       $errors[] = "El campo contraseña es obligatorio";
   } else {
    // Si el campo "password2" está vacío, se añade un mensaje de error al array de errores
    if (empty($_POST["password2"])) {
        $errors[] = "Por favor, confirma la contraseña";
       } else {
            // Si el campo "password" no está vacío, se comprueba si coincide con el campo "password2". Si no coinciden, se añade un mensaje de error al array de errores
            if (!empty($_POST["password"])) {
                if ($_POST["password"] !== $_POST["password2"]) {
                    $errors[] = "Las contraseñas no coinciden";
                }
            }
       }
   }
   
   
}

if (empty($errors)) { // Comprueba si la variable $errors está vacía
    $fname     = $_POST["fname"]; // Obtiene el valor del campo de entrada "fname" del formulario enviado por POST
    $lname     = $_POST["lname"]; // Obtiene el valor del campo de entrada "lname" del formulario enviado por POST
    $email    = $_POST["email"]; // Obtiene el valor del campo de entrada "email" del formulario enviado por POST
    $password_plain = $_POST["password"]; // Obtiene el valor del campo de entrada "password" del formulario enviado por POST
    $password = password_hash($password_plain, PASSWORD_DEFAULT); // Cifra la contraseña obtenida mediante password_hash() y la almacena en la variable $password
    
    $exists = usuarioExiste($email); // Comprueba si el usuario ya existe en la base de datos
    
    if ($exists) { // Si el usuario ya existe
        $errors[] = "Ya hay un usuario registrado con el mail introducido!"; // Agrega un error al array $errors
    } else { // Si el usuario no existe
        $success = crearUsuario($email, $fname, $lname, $password); // Crea un nuevo usuario en la base de datos
        if ($success) { // Si la creación de usuario fue exitosa
            $confirmations[] = "¡El registro ha sido exitoso!";
            // header("Location: ./user_login.php"); // Redirige al usuario a la página de inicio de sesión
        } else { // Si la creación de usuario falló
            $errors[] = "No ha sido posible guardar el usuario"; // Agrega un error al array $errors
        }
    }
} 

if (!empty($errors)) { // Si el array $errors no está vacío
    $query = http_build_query(["errors" => $errors]); // Crea una cadena de consulta URL-encoded con el array $errors y lo almacena en $query
    header("Location: ./user_register.php" . "?{$query}"); // Redirige al usuario a la página de registro con los errores en la cadena de consulta
}

if (!empty($confirmations)) { // Si el array $confirmations no está vacío
    $query = http_build_query(["confirmations" => $confirmations]); // Crea una cadena de consulta URL-encoded con el array $confirmations y lo almacena en $query
    header("Location: ./user_login.php" . "?{$query}"); // Redirige al usuario a la página de inicio de sesión con los errores en la cadena de consulta
}
     