<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php'); // Redirige al usuario si no está autenticado
    exit();
}
require "../database/database.php";

// Guardar la URL actual en la sesión
$_SESSION['prev_url'] = $_SERVER['REQUEST_URI'];
$_SESSION['initial_page'] = 'admin_albums.php';

// Instancia la clase Database
$database = new Database();

// Obtiene la conexión a la base de datos
$conn = $database->getConnection();

// Define la consulta SQL
$query = "SELECT * FROM usuarios";

// Ejecuta la consulta
$stmt = $conn->prepare($query);
$stmt->execute();

// Obtiene los resultados
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width">
    <title>Galeria de fotos A.J.V.</title>
    <link rel="icon" type="image/x-icon" href="../assets/logovierciblanco.svg">
    <!-- Librerías -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <!-- CSS -->
    <script  type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <!-- nanogallery2 -->
    <link rel="stylesheet" href="../node_modules/nanogallery2/src/css/nanogallery2.css"  type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css'>
    <link rel="stylesheet" type="text/css" href="../estilos.css">
    <link rel="stylesheet" type="text/css" href="../NavBar/navbar2.css">
    <!-- Scripts -->    
    <!-- Librerias Utilizadas: JSzip y Nanogallery2 -->
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
                        <img src="../assets/logovierciazul.svg" alt="LogoVierci" class="LogoVierciAzul">
                        <img src="../assets/logovierciblanco.svg" alt="LogoVierciBlanco" class="LogoVierciBlanco" style="display: none;">
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
                            <a href="../index2.php">
                                <i class='bx bx-home-alt icon'></i>
                                <span class="text nav-text">Imágenes</span>
                            </a>
                        </li>

                        <li class="nav-link" id="navAlbumes">
                            <a href="admin_albums.php" id="hrefAlbumes">
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

                        <li class="nav-link" id="DashboardMenu">
                            <a href="../dashboard/dashboard.php">
                                <i class="bi bi-menu-button-wide-fill icon"></i>
                                <span class="text nav-text">Usuarios</span>
                            </a>
                        </li>

                        <li class="nav-link" id="GaleriaPublica">
                            <a href="../indexGaleriaPublicaAlbumes.php">
                                <i class="bi bi-globe icon"></i>
                                <span class="text nav-text">Albumes Publicos</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Parte de abajo de la navbar -->

                <div class="bottom-content">
                    <li class="">
                        <a href="../login/logout.php">
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
                            <span class="switch"></spasn>
                        </div>

                    </li>

                </div>
            </div>

        </nav>


    <!-- Dialog para agregar un Album -->    
    <dialog class="dialogAlbum">
        <div class="wrapper">
            <form enctype="multipart/form-data" method="POST" id="albumForm">
            <header class="headerDialogAlbum">
                <h1 class="headerTextoDialogAlbum">Elige las imágenes</h1>
                <button onclick=showDialog(false) type="button" class="botonHeader">
                <span class="spanHeader"><i class="bi bi-x-lg"></i></span>
                </button>
            </header>
            <main class="Main">
                <div class="imagenDivDialogAlbum">
                <label class="imagenLabelDialogAlbum">Imágen</label>
                <label for="imagenInputDialogAlbum" class="inputFileMask">Agregar Imágen <i class="bi bi-images fa-lg"></i></label>
                <input type="file" class="imagenInputDialogAlbum" id="imagenInputDialogAlbum" name="files[]" multiple id="file" accept=".png, .jpg, .jpeg, .gif" maxlength="50">
                </div>
                <div class="imagenDivDialogAlbum">
                <label class="imagenLabelDialogAlbum">Descripción</label>
                <input class="imagenInputDialogAlbum" id="imagenInput" type="text" name="description" maxlength="20" required>
                </div>
            </main>
            <footer class="footerDialogAlbum">
                <button onclick=showDialog(false) class="cancelFooterDialogAlbum" value="cancel">
                Cancelar
                </button>
                <button class="saveFooterDialogAlbum" formMethod="dialog" value="submit" type="submit">
                Subir Album
                </button>
            </footer>
            </form>
        </div>  
    </dialog>
<div class="main-content">
    <!-- Formulario para la subida de las imágenes -->
    <div id = "Container1" class="container justify-content-center">            
            <div class="mt-5 mx-auto">
                <span class="titulo">Albumes</span>
                <button id="CrearAlbum" class="btn btn-primary mb-3 show-modal ColorAzul CrearAlbum" onclick=showDialog(true)>Crear un álbum</button>
            </div>  
            <p id="noResultsMessage" style="display: none;">No se ha encontrado nada.</p>
    </div>

    <!-- Menú de Herramientas -->
    <button class="fab" id="fab">
        <i class="bi bi-gear fa-lg"></i>
    </button>

    <div class="fab-menu fab-menu-4-btns" id="fab-menu">
        <div class="fab-menu-content">
            <button class="fab-menu-btn DescargarVarios" id="btnDescargar" data-tooltip="Descargar imágenes seleccionadas"><i class="bi bi-download"></i></button>
            <button class="fab-menu-btn EliminarVarios" id="btnEliminar" data-tooltip="Eliminar imágenes seleccionadas"><i class="bi bi-trash3"></i></button>
            <button class="fab-menu-btn PublicarVarios" id="btnPublicar" data-tooltip="Publicar imágenes seleccionadas"><i class="bi bi-eye"></i></button>
            <button class="fab-menu-btn SeleccionarVarios" id="BotonSelector" data-tooltip="Seleccionar Albumes"><i class="bi bi-check2-square"></i></button>
        </div>
    </div>

    <!-- Imágenes -->
    <div class="container justify-content-center mt-5 mb-5 py-2" id="galeriaContainer">
    </div>

    <script src="../node_modules/nanogallery2/dist/jquery.nanogallery2.js"></script>
<script src="../login/ScriptTipoUsuario.js"></script> 
<script src="../NavBar/navbar2.js"></script>
<script src="ScriptModoOscuro.js"></script>
<script src="scriptGeneracionImagenes.js"></script>
</body>
</html> 