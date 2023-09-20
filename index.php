<!-- Inicio de la sesión de PHP -->
<?php
session_start();
require "./controllers/posts.php";

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirige al usuario si no está autenticado
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

if (isset($_GET['pagina'])) {
    $pagina_actual = $_GET['pagina'];
} else {
    $pagina_actual = 1;
}

$empieza = ($pagina_actual - 1) * $por_pagina;
$query = "SELECT * FROM imagenes_sueltas ORDER BY id_imagen DESC LIMIT $empieza, $por_pagina";
$stmt = $conn->prepare($query);
$stmt->execute();
?>
<!-- Inicio del Html -->
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria de fotos A.J</title>
    <link rel="icon" type="image/x-icon" href="logovierciazul.svg">
    <!-- Librerías -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="estilos.css"></link>
    <link rel="stylesheet" type="text/css" href="navbar2.css"></link>
    <style>
    /* CSS para limitar el tamaño máximo de las imágenes en el carrusel */
    .carousel-inner img {
        max-width: 100%;
        height: auto;
    }
</style>
</head>
<body>
<div class="container">
    <!-- NavBar --> 
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <img src="logovierciazul.svg" alt="LogoVierci">
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

                <li class="search-box">
                    <i class='bx bx-search icon'></i>
                    <form class="d-flex" role="search" action="index.php" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Buscar..." aria-label="Search">
                    </form>
                </li>

                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="./index.php">
                            <i class='bx bx-home-alt icon' ></i>
                            <span class="text nav-text">Inicio</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="./admin_albums.php">
                            <i class='bx bx-photo-album icon'></i>
                            <span class="text nav-text">Albumes</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="./logout.php">
                        <i class='bx bx-log-out icon' ></i>
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
                <?php if(isset($_SESSION['error'])){ ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $_SESSION['error']?>
                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error']);
                } ?>
                <?php if(isset($_SESSION['success'])){ ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success']?>
                    <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']);
                } ?>
                <form action="controllers/new-post-photo.php" method="POST" enctype="multipart/form-data">
                    <h5 class="mb-3">Elige tu foto o fotos y añade una descripción</h5>
                    <div class="d-flex justify-content-between">
                        <input type="file" class="form-control mb-3" name="files[]" multiple id="file" accept=".png, .jpg, .jpeg" style="width: 40%" required>
                        <textarea name="description" id="description" rows="1" class="form-control mb-3 mx-3" style="resize: none;" placeholder="Añade una descripción" required></textarea>
                        <button class="btn btn-primary mb-3" type="submit" style="width: 20%;" name="btn-new-post-photo">Publicar</button>       
                    </div>
                </form>
            </div>
        </div>
    </div>


<!-- Imágenes -->
<div class="container mt-5 mb-5 py-2" id="image-container">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 mb-5 justify-content-center">
        <?php
        $stmt = $conn->prepare($query);
        $stmt->execute();
        while ($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Divide la cadena de imágenes en un array
            $imagesName = explode(",", $post['imagen']);
        ?>
        <div class="col">
            <div class="card">
                <div class="carousel slide" id="carousel<?=$post['id_imagen']?>" data-bs-ride="false">
                    <div class="carousel-inner">
                    <?php foreach ($imagesName as $j => $imageName) {
                            $isActive = $j == 0 ? 'active' : '';
                            ?>
                        <div class="carousel-item <?= $isActive ?>">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modal<?=$post['id_imagen']?>-<?=$j?>">
                                <!-- Agrega un atributo data-description con la descripción de la imagen -->
                                <div class="image-container" id="image-<?=$post['id_imagen']?>-<?=$j?>" data-description="<?= $descriptionsArray[$j] ?>" style="background-image: url('./assets/images/posts/<?= $imageName ?>');"></div>
                            </a>
                        </div>
                    <?php } ?>
                    </div>
                </div>

                <div class="card-body" id="card-body-<?=$post['id_imagen']?>">
                    <div class="original-description">
                        <?= $post['descripcion'] ?>
                    </div>
            <div class="botones-utilidades">
                <button class="delete-button" data-image-id="<?=$post['id_imagen']?>"><i class="bi bi-trash3 fa-6x"></i></button>
                <button class="btn-edit-description" data-image-id="<?=$post['id_imagen']?>"><i class="bi bi-pencil-square"></i></button>
                
                <!-- Botón de descarga -->
                <?php
                    // Crear una cadena de descripciones separadas por comas
                    $descriptionsArray = explode(",", $post['descripcion']);
                    $descriptionsString = implode(',', $descriptionsArray);
                ?>
                <a href="#" class="download-button" 
                   data-images="<?= implode(',', $imagesName) ?>" 
                   data-description="<?= $post['descripcion'] ?>" 
                   data-descriptions="<?= $descriptionsString ?>">
                    <i class="bi bi-download"></i>
                </a>
            </div>
        </div>

        <!-- Agrega el formulario de edición de descripción aquí -->
        <div class="description-edit-container" id="description-edit-<?=$post['id_imagen']?>" style="display: none;">
            <form action="editar-descripcion.php" method="POST" id="edit-form-<?= $post['id_imagen'] ?>">
                <textarea maxlength="25" name="new-description" class="form-control"><?= $post['descripcion'] ?></textarea>
                <input type="hidden" name="id_imagen" value="<?= $post['id_imagen'] ?>">
                <input type="hidden" name="edit-description" value="<?= isset($_GET['edit-description']) ? $_GET['edit-description'] : ''; ?>">
                <input type="hidden" name="pagina_actual" value="<?= $pagina_actual ?>">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="#" class="btn btn-secondary cancel-edit" data-image-id="<?= $post['id_imagen'] ?>">Cancelar </a>
            </form>
        </div>
    </div>
</div>
<?php } ?>





<!-- Paginación -->
<section class="paginación">
<div class="pagination">
    <?php
    $query_total = "SELECT COUNT(*) as total FROM imagenes_sueltas";
    $stmt_total = $conn->prepare($query_total);
    $stmt_total->execute();
    $row_total = $stmt_total->fetch(PDO::FETCH_ASSOC);

    $total_imagenes = $row_total['total'];
    $total_paginas = ceil($total_imagenes / $por_pagina);

    $pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

    // Define cuántos enlaces quieres mostrar antes y después de la página actual
    $num_enlaces_mostrados = 2;

    // Calcula el rango de páginas a mostrar
    $rango_inicio = max(1, $pagina_actual - $num_enlaces_mostrados);
    $rango_fin = min($total_paginas, $pagina_actual + $num_enlaces_mostrados);

    if ($pagina_actual > 1) {
        echo "<a href='index.php?pagina=" . ($pagina_actual - 1) . "' class='btn btn-outline-primary'>&lt;</a>";
    }

    for ($i = $rango_inicio; $i <= $rango_fin; $i++) {
        $clase_activo = ($i == $pagina_actual) ? 'active' : '';
        echo "<a href='index.php?pagina=$i' class='btn btn-outline-primary $clase_activo'>$i</a> ";
    }

    if ($pagina_actual < $total_paginas) {
        echo "<a href='index.php?pagina=" . ($pagina_actual + 1) . "' class='btn btn-outline-primary'>&gt;</a>";
    }
    ?>
</div>
</section>




<!-- Modales -->
<?php
$stmt = $conn->prepare($query); // Usar la misma consulta con PDO
$stmt->execute(); // Ejecutar la consulta
while ($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $imagesName = explode(",", $post['imagen']);
    foreach ($imagesName as $j => $imageName) { ?>
<!-- Código del Modal -->
<div class="modal fade" id="modal<?=$post['id_imagen']?>-<?=$j?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="carouselModal<?=$post['id_imagen']?>-<?=$j?>" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php for($k = 0; $k < count($imagesName); $k++) {
                            $isActive = $k == $j ? 'active' : '';
                        ?>
                        <div class="carousel-item <?= $isActive ?>">
                            <img src="./assets/images/posts/<?= $imagesName[$k] ?>" class="d-block w-100" alt="Imagen <?= $k ?>">
                        </div>
                        <?php } ?>
                    </div>
                    <?php if(count($imagesName) > 1){ ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselModal<?=$post['id_imagen']?>-<?=$j?>" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselModal<?=$post['id_imagen']?>-<?=$j?>" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                    <!-- Indicadores dentro del Modal -->
                    <ol class="carousel-indicators">
                        <?php for($k = 0; $k < count($imagesName); $k++) { ?>
                            <li data-bs-target="#carouselModal<?=$post['id_imagen']?>-<?=$j?>" data-bs-slide-to="<?= $k ?>" <?= $k == 0 ? 'class="active"' : '' ?>></li>
                        <?php } ?>
                    </ol>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
<?php } ?>
<?php } ?>

<!-- Script de Botones -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
$(document).ready(function() {
    // Función para guardar el estado del modo en el almacenamiento local
    function saveDarkModeState(isDarkMode) {
        localStorage.setItem('darkMode', isDarkMode);
    }

    // Función para cargar el estado del modo desde el almacenamiento local
    function loadDarkModeState() {
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode) {
            $("body").addClass("dark");
            $("#darkModeSwitch").prop("checked", true);
        }
    }

    // Cargar el estado del modo al cargar la página
    loadDarkModeState();

    // Manejar el cambio de modo
    $("#darkModeSwitch").click(function() {
        const isDarkMode = $("body").hasClass("dark");
        $("body").toggleClass("dark");
        $("#darkModeSwitch").prop("checked", !isDarkMode);
        // Guardar el estado del modo en el almacenamiento local
        saveDarkModeState(!isDarkMode);
        // Redirigir a la misma página con el estado del modo como parámetro en la URL
        const currentPage = window.location.href;
        const newUrl = currentPage + (currentPage.includes("?") ? "&" : "?") + "darkMode=" + (!isDarkMode ? "1" : "0");
        window.location.href = newUrl;
    });

    // Manejar la paginación
    $(".pagination a").click(function(e) {
        e.preventDefault(); // Evitar que el enlace navegue a otra página

        var pagina = $(this).text(); // Obtener el número de página
        // Pasar el estado del modo actual como parámetro en la URL al cambiar de página
        const isDarkMode = $("body").hasClass("dark") ? "1" : "0";
        window.location.href = "index.php?pagina=" + pagina + "&darkMode=" + isDarkMode; // Redirigir a la página correspondiente
    });
});
// Manejar la paginación
$(".pagination a").click(function(e) {
    e.preventDefault(); // Evitar que el enlace navegue a otra página

    var pagina = $(this).text(); // Obtener el número de página
    $.ajax({
        url: "./cargar_imagenes.php",
        method: "GET",
        data: { pagina: pagina },
        success: function(data) {
            // Actualiza el contenido del contenedor de imágenes con el nuevo contenido
            $("#image-container").html(data);
        }
    });
});
});
$(".delete-button").click(function(event) {
        event.preventDefault(); // Evitar que el enlace navegue a otra página

        const imageId = $(this).data("image-id");

        $.ajax({
            url: "eliminar-imagen.php",
            method: "POST",
            data: { id_imagen: imageId },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    // Eliminación exitosa, puedes mostrar un mensaje de éxito en la página
                    alert("Imagen eliminada exitosamente.");
                    // Ocultar tanto el image-container como el card-body específicos
                    $(`#image-${imageId}`).hide();
                    $(`#card-body-${imageId}`).hide();
                } else {
                    // Error al eliminar, puedes mostrar un mensaje de error en la página
                    alert("Error al eliminar la imagen: " + response.error);
                }
            },
            error: function(xhr, status, error) {
                // Manejar errores de la solicitud AJAX aquí
                console.error(error);
            }
        });
    });


</script>
<script src="descarga_imágenes.js"></script>

<!-- Función para editar la descripción -->

<script>
$(document).ready(function() {
    console.log("Script de edición de descripción cargado."); // Agrega este console.log para verificar si el script se carga

    $(".btn-edit-description").click(function() {
        console.log("Botón de edición de descripción clickeado");

        var imageId = $(this).data("image-id");
        var descriptionEditContainer = $(`#description-edit-${imageId}`);
        var cardBody = $(this).closest('.card-body');

        // Ocultar descripción original y botones
        cardBody.find('.botones-utilidades').hide();
        cardBody.find('.original-description').hide();

        // Agregar la clase 'visible' al description-edit-container
        descriptionEditContainer.addClass('visible');

        // Mover el elemento al div.card-body correspondiente
        cardBody.append(descriptionEditContainer);

        descriptionEditContainer.toggle();
    });

    // Manejar el click en el botón "Cancelar"
    $(".cancel-edit").click(function(e) {
        e.preventDefault(); // Evitar que el enlace navegue a otra página

        var imageId = $(this).data("image-id");
        var descriptionEditContainer = $(`#description-edit-${imageId}`);
        var cardBody = descriptionEditContainer.closest('.card-body');

        // Ocultar el formulario de edición y mostrar la descripción original y botones
        descriptionEditContainer.hide();
        cardBody.find('.original-description').show();
        cardBody.find('.botones-utilidades').show();
    });
});
</script>
<script src="editar-descripcion.php"></script>
<script src="eliminar-imagen.php"></script>
</body>
</html>