<?php
// Se requiere el archivo "user_functions.php"
require_once(__DIR__ . "./../library/ficheros_function.php");

// Se define un array vacío para almacenar los errores
$errors = [];
$confirmations = [];

session_start();

// // Si el método de solicitud no es POST, se añade un mensaje de error al array de errores
// if ($_SERVER['REQUEST_METHOD'] != 'POST') {
//     $errors[] = "Método de solicitud no es POST";
//  } else {
//     // Si el campo "name" está vacío, se añade un mensaje de error al array de errores
//     if (empty($_POST['nombre_archivo'])) {
//         $errors[] = "No has introducido el nuevo nombre del archivo";
//     }
//     if (empty($_POST['categoria'])) {
//      $errors[] = "Debes de seleccionar una categoría";
//      }
//  }

// Obtener todos los campos del formulario
$campos_post = $_POST;
$campos_fichero = $_FILES;
// Mostrar nombre y valor de cada campo
echo "<h2>Información del formulario</h2>";
echo "<h3>Campos fichero</h3>";
echo "<h3>Campos fichero</h3>";
echo "<ul>";
// Verificar si hubo un error al cargar el archivo
if ($detalles['error'] !== UPLOAD_ERR_OK) {
  echo "<li><strong>Nombre del campo:</strong> $nombre</li>";
  echo "<li><strong>Error:</strong> ";
  switch ($detalles['error']) {
      case UPLOAD_ERR_INI_SIZE:
          echo "El archivo excede la directiva upload_max_filesize en php.ini";
          break;
      case UPLOAD_ERR_FORM_SIZE:
          echo "El archivo excede la directiva MAX_FILE_SIZE que fue especificada en el formulario HTML";
          break;
      case UPLOAD_ERR_PARTIAL:
          echo "El archivo fue sólo parcialmente subido";
          break;
      case UPLOAD_ERR_NO_FILE:
          echo "Ningún archivo fue subido";
          break;
      case UPLOAD_ERR_NO_TMP_DIR:
          echo "Falta el directorio temporal";
          break;
      case UPLOAD_ERR_CANT_WRITE:
          echo "Error al escribir el archivo en el disco";
          break;
      case UPLOAD_ERR_EXTENSION:
          echo "Una extensión de PHP detuvo la carga del archivo";
          break;
      default:
          echo "Error desconocido";
          break;
  }
  echo "</li><br>";
} else {
  // Mostrar información detallada del archivo
  echo "<li><strong>Nombre del campo:</strong> $nombre</li>";
  echo "<li><strong>Nombre del archivo:</strong> {$detalles['name']}</li>";
  echo "<li><strong>Tipo de archivo:</strong> {$detalles['type']}</li>";
  echo "<li><strong>Ruta temporal:</strong> {$detalles['tmp_name']}</li>";
  echo "<li><strong>Código de error:</strong> {$detalles['error']}</li>";
  echo "<li><strong>Tamaño del archivo:</strong> {$detalles['size']} bytes</li>";
  echo "<br>";
}
echo "</ul>";
echo "<h3>Campos</h3>";
echo "<ul>";
foreach ($campos_post as $nombre => $valor) {
  echo "<li><strong>Nombre:</strong> $nombre</li>";
  echo "<li><strong>Valor:</strong> $valor</li>";
  echo "<br>";
}
echo "</ul>";

// Aquí puedes agregar código para realizar
// comprobaciones específicas en los campos


// if (!empty($errors)) { // Si el array $errors no está vacío
//     $query = http_build_query(["errors" => $errors]); // Crea una cadena de consulta URL-encoded con el array $errors y lo almacena en $query
//     header("Location: ./editar.php?id=$id_fichero" . "&{$query}"); // Redirige al usuario a la página de registro con los errores en la cadena de consulta
// }

// if (!empty($confirmations)) { // Si el array $confirmations no está vacío
//     $query = http_build_query(["confirmations" => $confirmations]); // Crea una cadena de consulta URL-encoded con el array $confirmations y lo almacena en $query
//     header("Location: ./editar.php?id=$id_fichero" . "&{$query}"); // Redirige al usuario a la página de inicio de sesión con los errores en la cadena de consulta
// }
     
?>
