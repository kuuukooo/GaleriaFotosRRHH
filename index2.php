<!-- Inicio de la sesión de PHP -->
<?php
session_start();
require "./controllers/posts.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: login/login.php'); // Redirige al usuario si no está autenticado
    exit();
}

require "./database/database.php";
$user_id = $_SESSION['user_id'];
$query = "SELECT Usuario FROM usuarios WHERE id_usuario = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) { 
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $nombre_usuario = $row['Usuario'];
} else {
    $nombre_usuario = "Usuario desconocido";
}
$por_pagina = 12;
$pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

// Ajustamos la lógica para obtener el valor de $empieza
if ($pagina_actual > 1) {
    $empieza = ($pagina_actual - 1) * $por_pagina;
} else {
    $empieza = 0;
}

// Usamos una variable adicional para llevar registro de la página actual
$pagina_mostrada = $pagina_actual;

$query = "SELECT * FROM imagenes_sueltas ORDER BY id_imagen DESC LIMIT " . intval($empieza) . ", $por_pagina";
$stmt = $conn->prepare($query);
$stmt->execute();
?>
<!-- Inicio del Html -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width">
    <title>Galeria de fotos A.J</title>
    <link rel="icon" type="image/x-icon" href="assets/logovierciblanco.svg">
    <!-- Librerías -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <link rel="stylesheet" type="text/css" href="navbar2.css">
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
</head>

<body>
    <div class="container">
        <!-- NavBar -->
        <nav class="sidebar close">
            <header>
                <div class="image-text">
                    <span class="image">
                        <img src="assets/logovierciazul.svg" alt="LogoVierci" class="LogoVierciAzul">
                        <img src="assets/logovierciblanco.svg" alt="LogoVierciBlanco" class="LogoVierciBlanco" style="display: none;">
                    </span>

                    <div class="text logo-text">
                        <span class="name">Galeria de Fotos</span>
                        <span class="profession">A.J Vierci</span>
                    </div>
                </div>

                <i class='bx bx-chevron-right toggle'></i>
            </header>

            <div class="menu-bar">
                <div class="menu">

                <!-- Buscador -->

                    <li class="search-box">
                    <i class='bx bx-search icon'></i>
                    <form class="d-flex" id="search-form" action="buscar_img.php" method="POST">
                        <input class="form-control me-2" type="search" name="search" id="search-input" placeholder="Buscar..." aria-label="Search">
                        <button type="submit" style="display: none;"></button>
                    </form>
                    </li>

                <!-- Iconos de la navbar -->

                    <ul class="menu-links">
                        <li class="nav-link">
                            <a href="./index2.php">
                                <i class='bx bx-home-alt icon'></i>
                                <span class="text nav-text">Inicio</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="Albums/admin_albums.php">
                                <i class='bx bx-photo-album icon'></i>
                                <span class="text nav-text">Albumes</span>
                            </a>
                        </li>

                        <li class="nav-link">
                            <a href="./dashboard.php">
                                <i class="bi bi-menu-button-wide-fill icon"></i>
                                <span class="text nav-text">Menú</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Parte de abajo de la navbar -->

                <div class="bottom-content">
                    <li class="">
                        <a href="login/logout.php">
                            <i class='bx bx-log-out icon'></i>
                            <span class="text nav-text">Cerrar Sesión</span>
                        </a>
                    </li>

                    <li class="mode">
                        <div class="sun-moon">
                            <i class='bx bx-moon icon moon'></i>
                            <i class='bx bx-sun icon sun'></i>
                        </div>
                        <span class="mode-text text">Modo Oscuro</span>

                        <div class="toggle-switch" id="darkModeSwitch">
                            <span class="switch"></span>
                        </div>

                    </li>

                </div>
            </div>

        </nav>

        
        <div class="main-content">
            <!-- formulario -->
            <div class="container justify-content-center">
                <div class="mt-5 mx-auto">
                    <div>
                        <?php if (isset($_SESSION['error'])) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $_SESSION['error'] ?>
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php unset($_SESSION['error']);
                        } ?>
                        <?php if (isset($_SESSION['success'])) { ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= $_SESSION['success'] ?>
                                <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php unset($_SESSION['success']);
                        } ?>
                        <form id="uploadForm" action="controllers/new-post-photo.php" method="POST" enctype="multipart/form-data">
                            <h5 class="mb-3">Elige tu foto y añade una descripción</h5>
                            <div class="d-flex justify-content-between">
                                <input type="file"id="InputElegirImagenes" class="form-control mb-3" name="files[]" multiple id="file" accept=".png, .jpg, .jpeg" style="width: 40%" required>
                                <!-- Se agregó un label para ocultar el botón de Elegir imágenes en dispositivos móviles -->
                                <label for="InputElegirImagenes" id="LabelElegirImagenes">
                                    <i class="bi bi-images fa-lg"></i>
                                </label>
                                <textarea name="description" id="description" rows="1" class="form-control mb-3 mx-3" style="resize: none;" placeholder="Descripción..." required></textarea>
                                <button class="btn btn-primary mb-3" type="button" name="btn-new-post-photo-no-icon" id="btn-new-post-photo"><i class="bi bi-upload"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            

            <!-- Imágenes -->
            <div class="container mt-5 mb-5 py-2">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 mb-5 justify-between" id="image-container">
                </div>
            </div>

            <!-- Paginación -->
            <div id="pagination-container">
            <ul class="pagination">
                <!-- Los botones de paginación se generarán aquí -->
            </ul>
            </div>

<!-- Script de Botones -->

<script src="imagenesDinámicas.js"></script>
<script src="descarga_imágenes.js"></script>
<script src="eliminar_imagen.js"></script>
<script src="navbar2.js"></script>
<script src="login/ScriptTipoUsuario.js"></script> 
</body>
</html>