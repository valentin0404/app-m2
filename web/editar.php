<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<body>
<?php
session_start();

// Verificar si ya hay una sesión iniciada
if (!isset($_SESSION['user'])) {
    // Redirigir al usuario al index.php
    header("Location: ./user_login.php");
    exit; // Detener la ejecución del script
}?>
<header id="header">
    <?php include(__DIR__ . "/partials_header.php"); 
    require_once(__DIR__ . "./../library/ficheros_function.php");
    ?>
  </header>
  
  <div class="container">
        <?php
        if (isset($_GET["id"])) {
        $id_fichero = $_GET["id"];
        $usuario_email = $_SESSION['user'];
        }

        // Declarar $archivo fuera del bloque if
        $archivo = obtener_ficheroIndividual($id_fichero, $usuario_email);

        if (!empty($archivo)) {
        ?>
            <h1 class="mt-5">Editar Archivo</h1>
            <?php if (!empty($_GET['errors'] )): ?>
            <ul class="list-group">
            <?php foreach ($_GET['errors'] as $error): ?>
            <li class="list-group-item list-group-item-danger"><?= $error ?></li>
            <?php endforeach ?>
            </ul>
            <?php endif ?>
            <?php if (!empty($_GET['confirmations'] )): ?>
            <ul class="list-group">
            <?php foreach ($_GET['confirmations'] as $confirmation): ?>
                <li class="list-group-item list-group-item-success"><?= $confirmation ?></li>
            <?php endforeach ?>
            </ul>
            <?php endif ?>
            <form action="./editar_action.php" method="post" enctype="multipart/form-data" class="mt-4">
            <div class="row g-5 align-items-center mb-3">
                <input type="hidden" name="id_fichero" value="<?php echo $id_fichero; ?>">
                <div class="col-5">
                    <label for="nombre_archivo" class="form-label">Nombre Archivo</label>
                    <small class="text-muted"> (No olvides la extensión)</small>
                    <input type="text" class="form-control" name="nombre_archivo" id="nombre_archivo" placeholder="<?php echo $archivo[0]['nombre_archivo']; ?>">
                </div>
            </div>

            <div class="row g-3 align-items-center mb-3">
                <div class="col-auto">
                    <select class="form-select" aria-label="Default select example" name="categoria">
                    <option value="" selected disabled>Selecciona una categoría</option>
                    <?php
                        // Llamamos a la función para obtener las categorías
                        $categorias = obtenerCategorias();

                        // Verificamos si se han obtenido categorías
                        if (!empty($categorias)) {
                            // Iteramos sobre el array de categorías para generar las opciones
                            foreach ($categorias as $id => $nombre) {
                                // Imprimimos la opción con el ID como valor y el nombre como texto
                                echo '<option value="' . $id . '">' . $nombre . '</option>';
                            }
                        } else {
                            // Si no se encontraron categorías, puedes imprimir un mensaje o tomar alguna otra acción
                            echo '<option value="">No hay categorías disponibles</option>';
                        }
                    ?>
                    </select>
                </div>
                <div class="col-auto">
                    <span id="categoryHelpInline" class="form-text">
                    Selecciona la categoría del fichero - (Categoria actual: <?php echo $archivo[0]['nombre_categoria']; ?>)
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Aplicar cambios</button>
            </form>

        <?php } else { ?>

            <p class="mt-5">Error: No se encontró el archivo.</p>

        <?php } ?>
    </div>
    <?php
    if (!empty($_GET['confirmations'])): ?>
        <script>
            setTimeout(function() {
            window.location.href = "ficheros.php"; // Reemplaza "otra_pagina.php" con la URL real
            }, 3600); // 4000 milisegundos equivalen a 4 segundos
        </script>
    <?php endif ?>
</body>
</html>