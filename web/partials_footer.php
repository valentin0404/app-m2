  <div class="container mt-5 pt-5">
  <footer class="mt-5 d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
    <div class="mt-5 col-md-4 d-flex align-items-center">
      <a href="/" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
        <svg class="bi" width="30" height="24"><use xlink:href="#bootstrap"></use></svg>
      </a>
      <div class="me-4 d-flex align-items-center"><span class="mb-3 mb-md-0 text-muted">© 2024 Metaguardado, Inc</span></div>
      <div class="me-4 d-flex align-items-center">
      <?php
          if (isset($_SESSION['user'])) {
            //mensaje de bienvenida
            echo '<p class="mb-3 mb-md-0 ml-3 text-muted"> Estás conectado con ('.$_SESSION['user'].')</p>';
          }
          else{
            echo '<p class="mb-3 mb-md-0 text-muted"> Valentin Duca - 1r DAW</p>';
          }
      ?>
      </div>
    </div>
  </footer>
</div>