<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Galeria de fotos A.J.V</title>
    <link rel="icon" type="image/x-icon" href="./assets/logovierciblanco.svg">
    <!-- Librerías -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nanogallery2/3.0.5/css/nanogallery2.min.css" integrity="sha512-6sOT9zKSKq1CYgNMqtcY84tFPDnG1yX5mxwdGQiAVpAomVr2kUKJ//pFeU/KfaZDVCOru5iFOVswpT4RWWF2dQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css'>
    <link rel="stylesheet" type="text/css" href="./estilos.css">
    <link rel="stylesheet" type="text/css" href="./NavBar/navbar2.css">
    <link rel="stylesheet" type="text/css" href="estilosPublico.css">
    <!-- Scripts -->    
    <!-- Librerias Utilizadas: Nanogallery2 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nanogallery2/3.0.5/jquery.nanogallery2.min.js" integrity="sha512-tvpLVnZrWnnNzV2921XEMx4xkFTUF8xg3s+Mi6cvC/R7A6X1FkpBUXPJFa3Xh5uD9BvOZ2tHeYq/5ZqrweW86Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
</head>
<body>

        <!-- NavBar -->
            <nav class="sidebar close">
            <header>
                <div class="image-text">
                    <span class="image">
                        <img src="./assets/logovierciazul.svg" alt="LogoVierci" class="LogoVierciAzul">
                        <img src="./assets/logovierciblanco.svg" alt="LogoVierciBlanco" class="LogoVierciBlanco" style="display: none;">
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
                    <form class="d-flex" id="search-form">
                        <input class="form-control me-2" type="search" name="search" id="search-input" placeholder="Buscar..." aria-label="Search">
                        <button type="submit" style="display: none;"></button>
                    </form>
                    </li>

                <!-- Iconos de la navbar -->

                    <ul class="menu-links">
                        <li class="nav-link">
                            <a href="indexGaleriaPublica.php">
                                <i class='bx bx-home-alt icon'></i>
                                <span class="text nav-text">Imágenes</span>
                            </a>
                        </li>

                        <li class="nav-link" id="navAlbumes">
                            <a href="#albumes-section" id="hrefAlbumes">
                                <i class='bx bx-photo-album icon' id="iconoAlbumes"></i>
                                <span class="text nav-text" id="textoAlbumes">Albumes</span>
                            </a>
                        </li>

                        <style>
						#hrefAlbumes, #iconoAlbumes, #textoAlbumes {
							background-color: #20327e;
							color: white !important;
						}
						</style>
                        <?php
                        // Verificar si la URL anterior es index2.php o admin_albums.php
                        if (isset($_SESSION['prev_url']) && (strpos($_SESSION['prev_url'], 'index2.php') !== false || strpos($_SESSION['prev_url'], 'admin_albums.php') !== false)) {
                        echo '<li class="nav-link">
                                <a href="' . $_SESSION['prev_url'] . '">
                                    <i class="bx bx-undo icon"></i>
                                    <span class="text nav-text">Regresar</span>
                                </a>
                            </li>';
                        }
                        ?>
                    </ul>
                </div>

                <!-- Parte de abajo de la navbar -->

                <div class="bottom-content">
                    <?php
                    // Verificar si el usuario no ha accedido desde index2.php o admin_albums.php
                    if (!isset($_SESSION['logged_from_initial_page']) || $_SESSION['logged_from_initial_page'] !== true) {
                        echo '<li class="">
                                <a href="./login/logout.php">
                                    <i class="bx bx-log-in icon"></i>
                                    <span class="text nav-text">Iniciar Sesión</span>
                                </a>
                            </li>';
                    }
                    ?>

                    <li class="mode">
                        <div class="sun-moon">
                            <i class='bx bx-moon icon moon'></i>
                            <i class='bx bx-sun icon sun'></i>
                        </div>
                        <span class="mode-text text">Modo Oscuro</span>

                        <div class="toggle-switch" id="darkModeSwitch">
                            <span class="switch"></spasn>
                        </div>

                    </li>

                </div>
            </div>

        </nav>


        <div class="main-content">

            <section id="albumes-section">
                <div class="texto-principal">   
                    <span class="titulo">Albumes</span>
                </div>
                
            <div id="my_nanogalleryAlbumes">   
            </div>
            </section>

        </div>  


<script src="login/ScriptTipoUsuario.js"></script> 
<script src="Albums/ScriptModoOscuro.js"></script>
<script src="NavBar/navbar2.js"></script>
<script src="indexPublicoAlbumes.js"></script>
</body>
</html>