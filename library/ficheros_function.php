<?php

// Incluir el archivo de conexión a la base de datos
include 'connect.php';

function obtenerCategorias() {
    global $pdo; // Acceder a la conexión PDO global
    
    // Preparar la consulta para seleccionar todas las categorías
    $consulta = "SELECT id, nombre_categoria FROM categoria";
    
    // Ejecutar la consulta preparada
    $resultado = $pdo->query($consulta);
    
    // Inicializar un array para almacenar las categorías
    $categorias = array();
    
    // Iterar sobre el resultado y almacenar los datos en el array
    while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
        $categorias[$fila['id']] = $fila['nombre_categoria'];
    }
    
    // Devolver el array de categorías
    return $categorias;
}



function obtenerIdUsuario ($usuario_email) {
    
    global $pdo; // Acceder a la conexión PDO global
    $consulta = "SELECT ID FROM usuario WHERE mail = ?";
    $resultado = $pdo->prepare($consulta);
    $resultado->execute([$usuario_email]);
    return $resultado->fetchColumn();
}

function archivoExiste($nombreArchivo, $usuario_id): bool 
{
    // ToDo if ($usuario_email == null)
    global $pdo; // Acceder a la conexión PDO global
    
    // Preparar la consulta para buscar el nombre_archivo en la tabla 'archivo'
    $consulta = "SELECT COUNT(*) FROM archivo WHERE nombre_archivo = ? AND usuario_id = ?";
    
    $resultado = $pdo->prepare($consulta);
    $resultado->execute([$nombreArchivo, $usuario_id]);
    
    // Obtener el número de filas encontradas
    $numeroFilas = $resultado->fetchColumn();
    
    // Devolver verdadero si el archivo existe, falso si no
    return $numeroFilas > 0;

}


function subirArchivo($nombreArchivo, $tamañoArchivo, $rutaTemporal, $categoria, $usuario_id, $tipoArchivo): bool {
    global $pdo; // Acceder a la conexión PDO global

    // Construir la ruta de la carpeta de almacenamiento
    $carpeta_usuario = __DIR__ . '/../storage/' . $usuario_id;
    // Verificar si la carpeta del usuario existe, si no, crearla
    if (!file_exists($carpeta_usuario)) {
        if (!mkdir($carpeta_usuario, 0777, true)) {
            // Si no se pudo crear la carpeta, devolver false
            return false;
        }
    }

    // Generar un nombre de archivo único con prefijo de ID de usuario
    $prefijoNombreArchivo = $usuario_id . "_";
    $extensionArchivo = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
    $nombreArchivoUnico = $prefijoNombreArchivo . uniqid() . "." . $extensionArchivo;

    // Construir la ruta completa del archivo en la carpeta del usuario
    $ruta_archivo_destino = $carpeta_usuario . '/' . $nombreArchivoUnico;

    // Mover el archivo a la carpeta del usuario
    if (move_uploaded_file($rutaTemporal, $ruta_archivo_destino)) {
        // Preparar la consulta para insertar un nuevo registro en la tabla 'archivo'
        $consulta = "INSERT INTO archivo (nombre_archivo, ruta_archivo, usuario_id, categoria_id, tamano, tipo) VALUES (?, ?, ?, ?, ?, ?)";
        
        // Ejecutar la consulta preparada con los valores proporcionados
        $resultado = $pdo->prepare($consulta);
        if ($resultado->execute([$nombreArchivo, $ruta_archivo_destino, $usuario_id, $categoria, $tamañoArchivo, $tipoArchivo])) {
            // Devolver true si se insertó el archivo correctamente
            return true;
        } else {
            // Si no se pudo ejecutar la consulta, devolver false
            return false;
        }
    } else {
        // Si no se pudo mover el archivo, devolver false
        return false;
    }
}

function obtener_ficheros($usuario_email) {
    $usuario_id = obtenerIdUsuario($usuario_email);
    global $pdo;
    $sql = "SELECT COUNT(*) AS total_archivos FROM archivo WHERE usuario_id = ?";
    $resultado = $pdo->prepare($sql);
    $resultado->execute([$usuario_id]);
    $total = $resultado->fetchColumn();
    return $total;
}

function obtener_ficheros_paginados($usuario_email, $start, $limit) {
    $usuario_id = obtenerIdUsuario($usuario_email);
    global $pdo;
    $sql = "SELECT archivo.*, categoria.nombre_categoria AS nombre_categoria
    FROM archivo
    INNER JOIN categoria ON archivo.categoria_id = categoria.id
    WHERE usuario_id = $usuario_id
    ORDER BY fecha_subida DESC
    LIMIT $start, $limit";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtener_ficheroIndividual($id_fichero, $usuario_email) {
    $usuario_id = obtenerIdUsuario($usuario_email);
    global $pdo;
    $sql = "SELECT archivo.nombre_archivo, categoria.nombre_categoria AS nombre_categoria
    FROM archivo
    INNER JOIN categoria ON archivo.categoria_id = categoria.id
    WHERE usuario_id = $usuario_id
    AND archivo.id = $id_fichero";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function actualizar_fichero($usuario_id, $id_fichero, $nuevo_nombre, $nueva_categoria) : bool {
    global $pdo;
    // Consulta SQL
    $sql = "UPDATE archivo
            SET nombre_archivo = :nombre,
                categoria_id = :categoria
            WHERE id = :id
            AND usuario_id = :usuario";

    // Preparación de la consulta
    $stmt = $pdo->prepare($sql);

    // Vinculación de parámetros
    $stmt->bindParam(':nombre', $nuevo_nombre, PDO::PARAM_STR);
    $stmt->bindParam(':categoria', $nueva_categoria, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id_fichero, PDO::PARAM_INT);
    $stmt->bindParam(':usuario', $usuario_id, PDO::PARAM_INT);

    // Ejecución de la consulta
    $stmt->execute();

    // Manejo de errores
    if ($stmt->errorCode() !== '00000') {
        $error = $stmt->errorInfo();
        echo "Error al actualizar el fichero: " . $error[2];
        return false;
    }

    // Retorno del resultado
    return $stmt->rowCount() > 0;
}

function convertir_tamanyo($tamanyo_bytes) {
    if ($tamanyo_bytes < 1048576) {
      return round($tamanyo_bytes / 1024, 2) . " KB";
    } else if ($tamanyo_bytes < 1073741824) {
      return round($tamanyo_bytes / 1048576, 2) . " MB";
    } else {
      return round($tamanyo_bytes / 1073741824, 2) . " GB";
    }
  }

function esPropietarioArchivo($usuario_id, $id_fichero) : bool {
    global $pdo; // Acceder a la conexión PDO global

    // Preparar la consulta para buscar la ruta en la tabla 'archivo'
    $consulta = "SELECT COUNT(*) FROM archivo WHERE id = ? AND usuario_id = ?";
    // Preparar la consulta
    $resultado = $pdo->prepare($consulta);
  
    // Ejecutar la consulta con los parámetros
    $resultado->execute([$id_fichero, $usuario_id]);
  
    // Cerrar el statement
    // $resultado->close();
    
    // Obtener el número de filas encontradas
    $numeroFilas = $resultado->fetchColumn();

    // Devolver verdadero si el archivo existe, falso si no
    return $numeroFilas > 0;
}

function obtenerRutaArchivo($id_fichero) {
    global $pdo;
    
    $consulta = "SELECT ruta_archivo FROM archivo WHERE id = ?";

    // Preparación de la consulta
    $resultado = $pdo->prepare($consulta);

    // Ejecutar la consulta con los parámetros
    $resultado->execute([$id_fichero]);

    // Obtención del resultado
    // $resultado = $resultado->get_result();

    // Si no se encontró el archivo
    if ($resultado->num_rows === 0) {
        return null;
    }
    $rutaArchivo = $resultado->fetchColumn();
    return $rutaArchivo;
}

function obtenerTipoArchivo($id_fichero) {
    global $pdo;
    
    $consulta = "SELECT tipo FROM archivo WHERE id = ?";

    // Preparación de la consulta
    $resultado = $pdo->prepare($consulta);

    // Ejecutar la consulta con los parámetros
    $resultado->execute([$id_fichero]);

    // Obtención del resultado
    // $resultado = $resultado->get_result();

    // Si no se encontró el archivo
    if ($resultado->num_rows === 0) {
        return null;
    }
    $tipoArchivo = $resultado->fetchColumn();
    return $tipoArchivo;
}

function obtenerNombreArchivo($id_fichero) {
    global $pdo;
    
    $consulta = "SELECT nombre_archivo FROM archivo WHERE id = ?";

    // Preparación de la consulta
    $resultado = $pdo->prepare($consulta);

    // Ejecutar la consulta con los parámetros
    $resultado->execute([$id_fichero]);

    // Obtención del resultado
    // $resultado = $resultado->get_result();

    // Si no se encontró el archivo
    if ($resultado->num_rows === 0) {
        return null;
    }
    $nombreArchivo = $resultado->fetchColumn();
    return $nombreArchivo;
}


function eliminar_archivo($usuario_email, $id_fichero) : bool{
  // Obtener ID de usuario
  $usuario_id = obtenerIdUsuario($usuario_email);

  if ($usuario_id) {
    // Validar permisos
    if (esPropietarioArchivo($usuario_id, $id_fichero)) {
       // Obtener ruta del archivo
        $ruta_archivo = obtenerRutaArchivo($id_fichero);
        // Eliminar archivo
        if (unlink($ruta_archivo)) {
            // Eliminar registro del archivo
            global $pdo;
            $consulta = "DELETE FROM archivo WHERE id = ?";
            $resultado = $pdo->prepare($consulta);
            $resultado->execute([$id_fichero]);
            return true;
        } else {
            return false;
        }   
    }
  }
}


function descargar_archivo($usuario_email, $id_fichero) : bool {
    // Obtener ID de usuario
  $usuario_id = obtenerIdUsuario($usuario_email);
  if (!$usuario_id) return false;
  if (!esPropietarioArchivo($usuario_id, $id_fichero)) return false;
  $ruta_archivo = obtenerRutaArchivo($id_fichero);
  if (!$ruta_archivo || $ruta_archivo == null) return false;
    // Verificar si el archivo existe
    if (!file_exists($ruta_archivo)) return false;
    //   header("Content-Type: application/force-download");
    //   header("Content-Disposition: attachment; filename=informacion_red_instituto");
    // Revisar para que vaya por GET y lo haga tipo 'a href' descargar_action
    // readfile($ruta_archivo);
    // return true;

  if ($usuario_id) {
    // Validar permisos
    if (esPropietarioArchivo($usuario_id, $id_fichero)) {
       // Obtener ruta del archivo
       
        $nombreArchivo = obtenerNombreArchivo($id_fichero);
        $ruta_archivo = obtenerRutaArchivo($id_fichero);
        $tipoArchivo = obtenerTipoArchivo($id_fichero);
        if ($ruta_archivo) {
            // Verificar si el archivo existe
            if (file_exists($ruta_archivo)) {
                header("Content-disposition: attachment; filename=" . ($nombreArchivo));
                header("Content-type: $tipoArchivo");
                readfile($ruta_archivo);
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
  }
}