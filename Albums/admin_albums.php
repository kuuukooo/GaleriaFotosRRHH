<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login/login.php'); // Redirige al usuario si no está autenticado
    exit();
}
require "../database/database.php";
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nanogallery2/3.0.5/css/nanogallery2.min.css" integrity="sha512-6sOT9zKSKq1CYgNMqtcY84tFPDnG1yX5mxwdGQiAVpAomVr2kUKJ//pFeU/KfaZDVCOru5iFOVswpT4RWWF2dQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css'>
    <link rel="stylesheet" type="text/css" href="../estilos.css">
    <link rel="stylesheet" type="text/css" href="../navbar2.css">
    <!-- Scripts -->    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nanogallery2/3.0.5/jquery.nanogallery2.min.js" integrity="sha512-tvpLVnZrWnnNzV2921XEMx4xkFTUF8xg3s+Mi6cvC/R7A6X1FkpBUXPJFa3Xh5uD9BvOZ2tHeYq/5ZqrweW86Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                    <form class="d-flex" id="search-form" action="buscar_img.php" method="POST">
                        <input class="form-control me-2" type="search" name="search" id="search-input" placeholder="Buscar..." aria-label="Search">
                        <button type="submit" style="display: none;"></button>
                    </form>
                    </li>

                <!-- Iconos de la navbar -->

                    <ul class="menu-links">
                        <li class="nav-link">
                            <a href="../index2.php">
                                <i class='bx bx-home-alt icon'></i>
                                <span class="text nav-text">Inicio</span>
                            </a>
                        </li>

                        <li class="nav-link" id="DashboardMenu">
                            <a href="../dashboard/dashboard.php">
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
                            <span class="switch"></spasn>
                        </div>

                    </li>

                </div>
            </div>

        </nav>


    <!-- Dialog para agregar un álbum -->    
    <dialog class="dialogAlbum">
        <div class="wrapper">
            <form>
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
                <input
                    class="imagenInput"
                    id="imagenInput"
                    type="file"
                    accept=".jpg, .jpeg, .png, .gif"/>
                </div>
                <div class="imagenDivDialogAlbum">
                <label class="imagenLabelDialogAlbum">Descripción</label>
                <input class="imagenInputDialogAlbum" id="imagenInput" type="text">
                </div>
            </main>
            <footer class="footerDialogAlbum">
                <button class="cancelFooterDialogAlbum" formMethod="dialog" value="cancel">
                Cancelar
                </button>
                <button class="saveFooterDialogAlbum" formMethod="dialog" value="submit">
                Subir Imágen
                </button>
            </footer>
            </form>
        </div>  
    </dialog>
<div class="main-content">
    <!-- Formulario para la subida de las imágenes -->
    <div class="container justify-content-center">
                <div class="mt-5 mx-auto">
                    <h5 class="mb-3">Album de imágenes</h5>
                        <button class="btn btn-primary mb-3 show-modal" onclick=showDialog(true)>Agrega tu álbum</button>
                </div>
    </div>

    <!-- Imágenes -->
    <!-- podría hacer una función para borrar varios albumes que lleva la opción
de "thumbnailSelectable": true, para hacer que las miniaturas de las imágenes se puedan
seleccionar para luego borrarse. -->
    <div class="container justify-content-center mt-5 mb-5 py-2">
                <div id="nanogallery2" 
                data-nanogallery2='{
                    "thumbnailWidth":   300,
                    "thumbnailHeight":  300,
                    "thumbnailAlignment": "center",
                    "thumbnailGutterWidth": 90,
                    "thumbnailGutterHeight": 40,
                    "galleryTheme":    {
                        "galleryMaxRows": "3",
                        "galleryDisplayMode": "pagination"
                }
            }'>
                <a href="" data-ngkind="album" data-ngid="1" data-ngthumb="fotos/prueba.png">Album A</a>
                <a href="fotos/prueba.png" data-ngid="10" data-ngalbumid="1" data-ngthumb="fotos/prueba.png">Image 1 / album A</a>
                <a href="fotos/super cap.jpg" data-ngid="11" data-ngalbumid="1" data-ngthumb="fotos/super cap.jpg">Image 2 / album A</a>
    </div>
</div>
</div>

<script src="../login/ScriptTipoUsuario.js"></script> 
<script src="../navbar2.js"></script>
<script src="scriptModoOscuro.js"></script>
<script src="scriptGeneraciónImágenes.js"></script>
</body>
</html>