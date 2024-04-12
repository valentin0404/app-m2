<!doctype html>
<html lang="en">

<head>
  <title>Metaguardado - Inicio</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

</head>

<body>
    <?php include(__DIR__ . "/partials_header.php"); ?>
  <main style="">
    <?php
    session_start(); 
    if (!isset($_SESSION['user'])) : ?>
      <div class="container mt-4 mb-5">
          <div class="jumbotron">
              <h1 class="display-4">¡Bienvenido a Metaguardado!</h1>
              <p class="lead">El mejor gestor de ficheros empresarial con seguridad y fiabilidad.</p>
              <hr class="my-4">
              <div class="card mb-3">
                  <img src="https://blogthinkbig.com/wp-content/uploads/sites/4/2021/12/MicrosoftTeams-image-32.jpg?resize=1040%2C400" class="card-img-top" alt="...">
                  <div class="card-body">
                      <h5 class="card-title">Crecimiento exponencial</h5>
                      <p class="card-text">Descubre cómo Metaguardado está ayudando a miles de empresas a administrar sus archivos de forma segura.</p>
                  </div>
              </div>
              <p class="lead">
                  <a class="btn btn-primary btn-lg" href="user_register.php" role="button">Quiero saber más</a>
              </p>
          </div>
      </div>
  <?php else : ?>
      <div class="container mt-4 mb-5">
          <div class="jumbotron">
              <h1 class="display-4">¡Bienvenido de nuevo!</h1>
              <p class="lead">Gracias por usar Metaguardado. ¡Tu solución de almacenamiento de archivos de confianza!</p>
              <hr class="my-4">
              <div class="card mb-3">
                  <img src="https://forbes.es/wp-content/uploads/2022/11/tecnologia-cambio-futuro-humano.jpg" class="card-img-top" alt="...">
                  <div class="card-body">
                      <h5 class="card-title">Crecimiento continuo</h5>
                      <p class="card-text">Continuamos mejorando para brindarte una experiencia inigualable en la gestión de archivos.</p>
                  </div>
              </div>
              <p class="lead">
                  <a class="btn btn-primary btn-lg" href="ficheros.php" role="button">Descubre nuestras novedades</a>
              </p>
          </div>
      </div>
  <?php endif; ?>  

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