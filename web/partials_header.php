<nav class="navbar navbar-expand navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="./index.php">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRI0T0xNAxb-yn8o0xjf8oBCrCol9oK96-ce87T9AoPkc96Q-F3fNtDo3lFrXwXVf1bHs4&usqp=CAU" alt="" width="30" height="24" class="d-inline-block align-text-top">
        Metaguardado</a>
    <ul class="nav navbar-nav">
    <?php
    session_start(); 
    if ($_SESSION["user"]): ?>
        <li class="nav-item" style="margin-right: 20px;">
            <a class="nav-link" href="./ficheros.php">Ver/Subir Ficheros</a>
        </li>
        <li class="nav-item">
            <form action="./user_logout_action.php" method="post">
                <button type="submit" class="btn btn-danger">Cerrar sesión</button>
            </form>
        </li>
    <?php else: ?>
        <li class="nav-item" style="margin-right: 20px;">
            <a class="nav-link" href="./user_register.php" aria-current="page">Registro</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="./user_login.php">Iniciar sesión</a>
        </li>
    <?php endif; ?>
    </ul>
</nav>