<!doctype html>
<html lang="en">

<head>
  <title>Registro</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

</head>
<body>
<?php
session_start();

// Verificar si ya hay una sesión iniciada
if (isset($_SESSION['user'])) {
    // Redirigir al usuario al index.php
    header("Location: ./index.php");
    exit; // Detener la ejecución del script
}?>
  <header id="header">
    <?php include(__DIR__ . "/partials_header.php"); ?>
  </header>
  <main class="container-fluid">
      <div class="container" style="width: 50%;">
          <h2 class="mt-5 text-center">Registro</h2>
          <?php if (!empty($_GET['errors'] )): ?>
          <ul class="list-group">
          <?php foreach ($_GET['errors'] as $error): ?>
            <li class="list-group-item list-group-item-danger"><?= $error ?></li>
          <?php endforeach ?>
          </ul>
          <?php endif ?>
          <form action="./user_register_action.php" method="POST" class="mt-4">
              <div class="mb-3" >
                  <label for="fname" class="form-label">Nombre</label>
                  <input type="text" class="form-control" name="fname" id="fname" placeholder="Tú nombre">
              </div>
              <div class="mb-3" >
                  <label for="lname" class="form-label">Apellido</label>
                  <input type="text" class="form-control" name="lname" id="lname" placeholder="Tus apellidos">
              </div>
              <div class="mb-3">
                  <label for="email" class="form-label">Correo</label>
                  <input type="email" class="form-control" name="email" id="email" placeholder="abc@mail.com">
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="12345678">
              </div>
              <div class="mb-3">
                <label for="password2" class="form-label">Confirmar contraseña</label>
                <input type="password" class="form-control" name="password2" id="password2" placeholder="Confirmar contraseña">
              </div>
              <div class="mb-3 row">
                  <div class="offset-sm-4 col-sm-8">
                      <button type="submit" class="btn btn-primary" style="width: 12.5rem;">Registrar</button>
                  </div>
              </div>
          </form>
      </div>
  </main>
  <footer id="footer">
    <?php include(__DIR__ . "/partials_footer.php"); ?>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>
</body>

</html>