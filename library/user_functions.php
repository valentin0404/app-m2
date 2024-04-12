<?php

// Incluir el archivo de conexión a la base de datos
include 'connect.php';



function usuarioExiste($email): bool 
{
    global $pdo; // Acceder a la conexión PDO global

    // Preparar la consulta para buscar el correo electrónico en la tabla 'usuario'
    $consulta = "SELECT COUNT(*) FROM usuario WHERE mail = ?";
    
    // Ejecutar la consulta preparada con el correo electrónico proporcionado
    $resultado = $pdo->prepare($consulta);
    $resultado->execute([$email]);
    
    // Obtener el número de filas encontradas
    $numeroFilas = $resultado->fetchColumn();
    
    // Devolver verdadero si el usuario existe, falso si no
    return $numeroFilas > 0;

}

function crearUsuario($email, $fname, $lname, $password): bool {
    global $pdo; // Acceder a la conexión PDO global
    
    // Preparar la consulta para insertar un nuevo usuario en la tabla 'usuario'
    $consulta = "INSERT INTO usuario (mail, nombre, apellidos, contraseña) VALUES (?, ?, ?, ?)";
    
    // Ejecutar la consulta preparada con los valores proporcionados
    $resultado = $pdo->prepare($consulta);
    $resultado->execute([$email, $fname, $lname, $password]);
    
    // Devolver true si se insertó el usuario correctamente
    return true;
}

// Esta función genera una sesión de usuario utilizando el email y la contraseña proporcionados
function generate_session($email, $password) : bool
{
    // Se verifica si el usuario es válido llamando a la función my_users_check
    $isValid = verificarUsuario($email, $password);
    // Si el usuario no es válido, se devuelve falso y se termina la función
    if (!$isValid) return false;
    // Se inicia la sesión del usuario
    session_start();
    // Se almacena el email del usuario en la variable de sesión $_SESSION["user"]
    $_SESSION["user"] = $email;
    $_SESSION["nombre"] = $fname;
    // Se devuelve verdadero para indicar que la sesión se ha generado correctamente
    return true;
}

// Función para obtener la contraseña encriptada de un usuario dado
function obtenerContraseña($email) {
    global $pdo;

    // Preparar la consulta para obtener la contraseña encriptada del usuario
    $consulta = "SELECT contraseña FROM usuario WHERE mail = ?";

    // Ejecutar la consulta preparada con el correo electrónico proporcionado
    $resultado = $pdo->prepare($consulta);
    $resultado->execute([$email]);

    // Obtener la contraseña encriptada del resultado
    $hashContraseña = $resultado->fetchColumn();

    return $hashContraseña;
}

function verificarUsuario($email, $password) {
    if (usuarioExiste($email)) {
        // Obtener la contraseña almacenada en la base de datos para el usuario dado
        $hashContraseña = obtenerContraseña($email);

        // Verificar si la contraseña proporcionada coincide con la almacenada
        if (password_verify($password, $hashContraseña)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}



// Esta función elimina la sesión del usuario
function my_delete_session() : bool
{
    // Se inicia la sesión del usuario
    session_start();
    // Se destruye la sesión del usuario
    session_destroy();
    // Se redirecciona al usuario a la página de inicio de sesión
    header("location:./user_login.php");
    // Se devuelve verdadero para indicar que la sesión se ha eliminado correctamente
    return true;
}