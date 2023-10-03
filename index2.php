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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria de fotos A.J</title>
    <link rel="icon" type="image/x-icon" href="logovierciazul.svg">
    <!-- Librerías -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
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

        /* Estilos CSS para el contenedor de imágenes */
        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }

        .image-container {
            position: relative;
            overflow: hidden;
            width: 100%;
            padding-bottom: 100%;
        }

        .image-container {
            width: 100%;
            padding-bottom: 75%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
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
                        <form class="d-flex" role="search" action="index2.php" method="GET">
                            <input class="form-control me-2" type="search" name="search" placeholder="Buscar..." aria-label="Search">
                        </form>
                    </li>

                    <ul class="menu-links">
                        <li class="nav-link">
                            <a href="./index2.php">
                                <i class='bx bx-home-alt icon'></i>
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
            <div class="container mt-5 mb-5 py-2">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 mb-5 justify-between" id="image-container">
                </div>
            </div>






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

                    $pagina_actual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
                    $num_enlaces_mostrados = 2;
                    
                    $rango_inicio = max(1, $pagina_actual - $num_enlaces_mostrados);
                    $rango_fin = min($total_paginas, $pagina_actual + $num_enlaces_mostrados);
                    

                    if ($pagina_actual > 1) {
                        echo "<a href='index2.php?pagina=" . ($pagina_actual - 1) . "' class='btn btn-outline-primary'>&lt;</a>";
                    }

                    for ($i = $rango_inicio; $i <= $rango_fin; $i++) {
                        $clase_activo = ($i == $pagina_actual) ? 'active' : '';
                        echo "<a href='index2.php?pagina=$i' class='btn btn-outline-primary $clase_activo'>$i</a> ";
                    }

                    if ($pagina_actual < $total_paginas) {
                        echo "<a href='index2.php?pagina=" . ($pagina_actual + 1) . "' class='btn btn-outline-primary'>&gt;</a>";
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
                    <div class="modal fade" id="modal<?= $post['id_imagen'] ?>-<?= $j ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div id="carouselModal<?= $post['id_imagen'] ?>-<?= $j ?>" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            <?php for ($k = 0; $k < count($imagesName); $k++) {
                                                $isActive = $k == $j ? 'active' : '';
                                            ?>
                                                <div class="carousel-item <?= $isActive ?>">
                                                    <img src="./assets/images/posts/<?= $imagesName[$k] ?>" class="d-block w-100" alt="Imagen <?= $k ?>">
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <?php if (count($imagesName) > 1) { ?>
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselModal<?= $post['id_imagen'] ?>-<?= $j ?>" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Anterior</span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carouselModal<?= $post['id_imagen'] ?>-<?= $j ?>" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Siguiente</span>
                                            </button>
                                            <!-- Indicadores dentro del Modal -->
                                            <ol class="carousel-indicators">
                                                <?php for ($k = 0; $k < count($imagesName); $k++) { ?>
                                                    <li data-bs-target="#carouselModal<?= $post['id_imagen'] ?>-<?= $j ?>" data-bs-slide-to="<?= $k ?>" <?= $k == 0 ? 'class="active"' : '' ?>></li>
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
$(document).ready(function() {
    console.log("Script de edición de Cargado de Imagenes cargado.");

    // Función para cargar las imágenes y luego activar la edición
    function cargarImagenesYActivarEdicion(pagina) {
        console.log("Solicitando imágenes para la página: " + pagina);
        $.ajax({
            url: "cargar_imagenes.php",
            method: "GET",
            data: {
                pagina: pagina
            },
            dataType: "json",
            success: function(data) {
                console.log("Respuesta exitosa de la solicitud AJAX:", data);

                    // Iterar sobre los datos de las imágenes y agregarlas al contenedor
                    data.forEach(function(imagen, index) {
                        // Crear un nuevo elemento .col y .card con jQuery
                        var colCardContainer = $('<div>');
                            colCardContainer.addClass('col');
                            colCardContainer.attr('id', 'col' + imagen.id_imagen);

                            var cardElement = $('<div>');
                            cardElement.addClass('card');
                            // Crear un nuevo elemento div con jQuery
                            var carouselElement = $('<div>');

                            // Asignar el ID dinámicamente al elemento carousel
                            carouselElement.attr('id', 'carousel' + imagen.id_imagen);
                            carouselElement.addClass('carousel slide');
                            carouselElement.attr('data-bs-ride', 'false');

                            // Crear el elemento interno carousel-inner
                            var carouselInner = $('<div>');
                            carouselInner.addClass('carousel-inner');

                        // Recorrer las imágenes y crear los elementos de carousel-item
                        $.each(imagen.imagenes, function(j, imageName) {
                            var isActive = j === 0 ? 'active' : '';
                            var carouselItem = $('<div>');
                            carouselItem.addClass('carousel-item ' + isActive);

                            // Crear el enlace y el contenedor de la imagen
                            var link = $('<a>');
                            link.attr('href', '#');
                            link.attr('data-bs-toggle', 'modal');
                            link.attr('data-bs-target', '#modal' + imagen.id_imagen + '-' + j);

                            var imageContainer = $('<div>');
                            imageContainer.addClass('image-container');
                            imageContainer.attr('id', 'image-' + imagen.id_imagen + '-' + j);
                            imageContainer.attr('data-description', imagen.descripcion[j]);
                            imageContainer.css('background-image', 'url(\'./assets/images/posts/' + imageName + '\')');

                            link.append(imageContainer);
                            carouselItem.append(link);

                            carouselInner.append(carouselItem);

                        });

                        // Crear el elemento card-body dentro de la .card
                        var cardBodyElement = $('<div>').addClass('card-body');
                        cardBodyElement.attr('id', 'card-body-' + imagen.id_imagen);
                        cardBodyElement.append('<div class="original-description">' + imagen.descripcion + '</div>');
                        
                        // Crear el contenedor para botones-utilidades y agregar botones
                        var botonesUtilidadesContainer = $('<div>').addClass('botones-utilidades');
                        botonesUtilidadesContainer.append('<button class="delete-button" data-image-id="' + imagen.id_imagen + '"><i class="bi bi-trash3 fa-6x"></i></button>');
                        botonesUtilidadesContainer.append('<button class="btn-edit-description" data-image-id="' + imagen.id_imagen +'"><i class="bi bi-pencil-square"></i></button>');
                        botonesUtilidadesContainer.append('<a class="download-button" href="#" data-images="' + imagen.imagenes.join(',') + '" data-description="' + imagen.descripcion + '" data-descriptions="' + imagen.descripcion + '"><i class="bi bi-download"></i></a>');
                        
                        // Agregar el contenedor de botones-utilidades al card-body
                        cardBodyElement.append(botonesUtilidadesContainer);
                

                        carouselElement.append(carouselInner);
                        cardElement.append(carouselElement, cardBodyElement);
                        colCardContainer.append(cardElement);
                        

                        // Crea el elemento .description-edit-container
                        var descriptionEditContainer = $("<div>").addClass("description-edit-container").attr("id", "description-edit-" + imagen.id_imagen).css("display", "none");

                        // Crea el formulario dentro del contenedor
                        var formElement = $("<form>").attr("action", "editar-descripcion.php").attr("method", "POST").attr("id", "edit-form-" + imagen.id_imagen);

                        // Crea el textarea dentro del formulario
                        var textareaElement = $("<textarea>").attr("maxlength", "25").attr("name", "new-description").addClass("form-control").text(imagen.descripcion);

                        // Crea los elementos input ocultos dentro del formulario
                        var hiddenInputId = $("<input>").attr("type", "hidden").attr("name", "id_imagen").val(imagen.id_imagen);
                        var hiddenInputEditDescription = $("<input>").attr("type", "hidden").attr("name", "edit-description").val(imagen.descripcion || ''); // Cambia esto según tu necesidad
                        var hiddenInputPaginaActual = $("<input>").attr("type", "hidden").attr("name", "pagina_actual").val(imagen.pagina_actual); // Cambia esto según tu necesidad

                        // Crea el botón "Guardar" dentro del formulario
                        var saveButton = $("<button>").attr("type", "submit").addClass("btn btn-primary").attr("id","guardar-btn").text("Guardar");

                        // Crea el enlace "Cancelar" dentro del formulario
                        var cancelButton = $("<a>").attr("href", "#").addClass("btn btn-secondary cancel-edit").attr("data-image-id", imagen.id_imagen).text("Cancelar"); 

                        // Agrega los elementos al formulario
                        formElement.append(textareaElement, hiddenInputId, hiddenInputEditDescription, hiddenInputPaginaActual, saveButton, cancelButton);

                        // Agrega el formulario al contenedor .description-edit-container
                        descriptionEditContainer.append(formElement);

                        // Agrega el contenedor al lugar adecuado en tu página (por ejemplo, a un div con un ID específico)
                        colCardContainer.find('.card-body').append(descriptionEditContainer); // Cambia ".card-body" al selector adecuado dentro del contexto de colCardContainer

                        // Agregar el elemento carousel al DOM (por ejemplo, a un contenedor con clase "container")
                        $('#image-container').append(colCardContainer);
                    });

                //Descargar Imágenes
                downloadimage();

                //activar el botón de eliminar
                BotonEliminar();
                console.log("Botón de eliminar correctamente activado");

                // Llama a la función para adjuntar el manejador de eventos
                GuardarAJAX(); 
            },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", error);
                    // Manejar errores de la solicitud AJAX aquí
                }
        });
    }

    // Cargar imágenes y activar edición en el documento listo
    cargarImagenesYActivarEdicion(1);
});


        // Manejar la paginación cuando se hace clic en los enlaces de paginación
        $(".pagination a").click(function(e) {
            e.preventDefault();
            var pagina = $(this).text();
            cargarImagenesYActivarEdicion(pagina);
        });


    //Función BotonEliminar
    function BotonEliminar() {
    $(".delete-button").click(function(event) {
        event.preventDefault(); // Evitar que el enlace navegue a otra página

        const imageId = $(this).data("image-id");

        // Mostrar una alerta de confirmación
        const confirmacion = confirm("¿Quieres eliminar la imagen?");

        if (confirmacion) {
            $.ajax({
                url: "eliminar-imagen.php",
                method: "POST",
                data: { id_imagen: imageId },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // Ocultar tanto el image-container como el card-body específicos
                        $(`#col${imageId}`).remove();
                        
                        // Eliminación exitosa, puedes mostrar un mensaje de éxito en la página
                        //Añadido un delay pequeño para que deje mostrar que se eliminó la foto
                        setTimeout(function () {
                            alert("Imágen Eliminada Exitosamente.");
                        }, 100);
                    } else {
                        // Error al eliminar, puedes mostrar un mensaje de error en la página
                        alert("Error al eliminar la imagen: " + response.error);
                    }
                },
                error: function(error) {
                    // Manejar errores de la solicitud AJAX aquí
                    console.log(error);
                }
            });
        }
    });
}
function GuardarAJAX() {
    $('body').on('click', '#guardar-btn', function(event) {
    event.preventDefault(); // Evitar que el formulario se envíe automáticamente

    // Obtener los valores del formulario
    var form = $(this).closest('form');
    var newDescription = form.find('textarea[name="new-description"]').val();
    var imageId = form.find('input[name="id_imagen"]').val();
    var paginaActual = form.find('input[name="pagina_actual"]').val();

    console.log('newDescription:', newDescription);
    console.log('imageId:', imageId);
    console.log('paginaActual:', paginaActual);

    // Realizar la solicitud AJAX
    $.ajax({
        url: 'editar-descripcion.php',
        type: 'POST',
        data: {
            'new-description': newDescription,
            'id_imagen': imageId,
            'pagina_actual': paginaActual
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                // Actualiza la descripción en la página sin recargarla
                var originalDescription = form.closest('.card-body').find('.original-description');
                originalDescription.text(newDescription);

                // Restaura la visibilidad de la descripción original y los botones
                form.closest('.description-edit-container').hide();
                originalDescription.show();
                form.closest('.card-body').find('.botones-utilidades').show();

                // Muestra un mensaje de éxito
                alert(response.message);
            } else {
                // Muestra un mensaje de error
                alert(response.message);
            }
        },
        error: function (xhr, status, error) {
            console.log('Error en la solicitud AJAX:');
            console.log('Status:', status);
            console.log('Error:', error);
        }
    });
});
}
</script>


    <script>
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
</script>
<script>
$(document).ready(function() {
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: './controllers/new-post-photo.php', // Cambia esto al archivo PHP que manejará la carga de imágenes
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                window.location.href = './index2.php'; // Redirecciona a la página principal
            },
            error: function() {
                alert('Error al cargar imágenes');
            }
        });
    });
});
</script>
<script>
    // Función para activar la edición
    // Eventos click para edición y cancelación
    $(document).ready(function() {
        $(document).on("click", ".btn-edit-description", function() {
            console.log("Botón de edición de descripción clickeado");

            var imageId = $(this).data("image-id");
            var cardBody = $(this).closest('.card-body'); // Define cardBody aquí

            var descriptionEditContainer = $(`#description-edit-${imageId}`);

            // Agregar la clase 'visible' al description-edit-container
            descriptionEditContainer.addClass('visible');


            // Ocultar descripción original y botones
            cardBody.find('.botones-utilidades').css("display", "none");
            cardBody.find('.original-description').css("display","none");  

            // Mover el elemento al div.card-body correspondiente
            cardBody.append(descriptionEditContainer);

            descriptionEditContainer.toggle();
        });

        $(document).on("click", ".cancel-edit", function(e) {
            e.preventDefault(); // Evitar que el enlace navegue a otra página
            var imageId = $(this).data("image-id");
            var descriptionEditContainer = $(`#description-edit-${imageId}`);
            var cardBody = descriptionEditContainer.closest('.card-body');

            // Ocultar el formulario de edición y mostrar la descripcións original y botones
            descriptionEditContainer.hide();
            cardBody.find('.original-description').show();
            cardBody.find('.botones-utilidades').show();
        });
    });
</script>
<script src="descarga_imágenes.js"></script>
<script src="eliminar_imagen.js"></script>
<script src="navbar2.js"></script>
</body>
</html>