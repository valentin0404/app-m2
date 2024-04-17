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
    // Redirigir al usuario al login
    header("Location: ./user_login.php");
    exit; // Detener la ejecución del script
}?>
<header id="header">
    <?php include(__DIR__ . "/partials_header.php"); 
    require_once(__DIR__ . "./../library/ficheros_function.php");
    ?>
  </header>
  <div class="container">
        <h1 class="mt-5">Subir nuevo Archivo</h1>
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
        <!-- Formulario para subir archivos -->
        <form action="./ficheros_action.php" method="post" enctype="multipart/form-data" class="mt-4">
            <div class="mb-3">
                <label for="formFile" class="form-label">Añadir archivo</label>
                <small class="text-muted"> (Tamaño máximo: 10MB)</small>
                <input class="form-control" type="file" name="archivo" id="formFile">
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
                    Selecciona la categoría del fichero.
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Subir Archivo</button>
        </form>

        <hr class="mt-5">

        <h2>Archivos Subidos</h2>

        <?php
        // Aquí iría tu lógica para obtener los archivos subidos por el usuario
        $usuario_email = $_SESSION['user'];
        $archivos = []; // Supongamos que esta es una lista de archivos obtenida de la base de datos
        $total = obtener_ficheros($usuario_email);
        $limit = 5;
        $pages = ceil($total / $limit);
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start = ($page - 1) * $limit;
        $previous = $page - 1;
        $next = $page + 1;
        $archivos = obtener_ficheros_paginados($usuario_email, $start, $limit);
        ?>

        <?php if (!empty($archivos)): ?>
            <table class="table mt-4">
              <thead>
                <tr>
                  <th>Nombre del Archivo</th>
                  <th>Categoría</th>
                  <th>Fecha subida</th>
                  <th>Tamaño</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($archivos as $archivo): ?>
                  <tr>
                    <td>
                      <?php echo $archivo['nombre_archivo']; ?>
                    </td>
                    <td>
                      <?php echo $archivo['nombre_categoria']; ?>
                    </td>
                    <td>
                      <?php echo $archivo['fecha_subida']; ?>
                    </td>
                    <td>
                      <?php echo convertir_tamanyo($archivo['tamano']); ?>
                    </td>
                    <td>
                      <a href="editar.php?id=<?php echo $archivo['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                      <!-- <a href="eliminar.php?id=<?php //echo $archivo['id'] ?>" class="btn btn-sm btn-danger">Eliminar</a> -->
                      <!-- Button trigger modal -->
                      <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#eliminarModal<?php echo $archivo['id']?>">
                        Eliminar
                      </button>
                      <a href="descargar_action.php?id=<?php echo $archivo['id'] ?>" class="btn btn-sm btn-info">Descargar</a>

                      <!-- Modal Eliminar -->
                      <div class="modal fade" id="eliminarModal<?php echo $archivo['id']?>" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="eliminarModalLabel">¿Estás seguro de eliminar el fichero?</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <?php 
                              $id_archivo = $archivo['id'];
                              $nombre_archivo = $archivo['nombre_archivo'];
                              echo "Se procederá a eliminar el fichero '" . $nombre_archivo . "'.";
                              ?>
                            </div>
                            <div class="modal-footer">
                              <form action="eliminar_action.php" method="post">
                              <input type="hidden" name="id" value="<?php echo $id_archivo ?>">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Confirmar y eliminar</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                <?php endforeach?>
              </tbody>
            </table>
            <nav aria-label="Botones de navegacion">
            <ul class="pagination justify-content-center">
              <?php if ($page > 1): ?>
                <li class="page-item">
                  <a class="page-link" href="ficheros.php?page=<?= $previous; ?>">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                  </svg>
                  </a>
                </li>
              <?php else: ?>
                  <li class="page-item disabled">
                    <a class="page-link">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                      </svg>
                    </a>
                  </li>
              <?php endif; ?>
              <?php for($i = 1; $i <= $pages; $i++) : ?>
                <?php if ($i == $page): ?>
                  <li class="page-item disabled"><a class="page-link" href="ficheros.php?page=<?= $i; ?>"><?= $i; ?></a></li>
                <?php else: ?>
                  <li class="page-item"><a class="page-link" href="ficheros.php?page=<?= $i; ?>"><?= $i; ?></a></li>
                <?php endif; ?>
              <?php endfor; ?>
              <?php if ($page < $pages): ?>
                <li class="page-item">
                  <a class="page-link" href="ficheros.php?page=<?= $next; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                    </svg>
                  </a>
                </li>
              <?php else: ?>
                <li class="page-item disabled">
                  <a class="page-link" href="ficheros.php?page=<?= $next; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                    </svg>
                  </a>
                </li>
              <?php endif; ?>
            </ul>
          </nav>
          <?php else: ?>
            <p>No tiene archivos subidos.</p>
          <?php endif; ?>
    </div>
    <footer id="footer">
    <?php include(__DIR__ . "/partials_footer.php"); ?>
  </footer>
    <!-- Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>
</html>
