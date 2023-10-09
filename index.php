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
    $pagina_actual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="estilos.css"></link>
    <link rel="stylesheet" type="text/css" href="navbar2.css"></link>
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

<!-- Card Bodies y Botones de utilidad -->

<div class="container mt-5 mb-5 py-2">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 mb-5 justify-content-center">
        <?php
        $conn = mysqli_connect("localhost", "root", "", "galeria");
        $por_pagina = 12;

        if (isset($_GET['pagina'])) {
            $pagina = $_GET['pagina'];
        } else {
            $pagina = 1;
        }

        $empieza = ($pagina - 1) * $por_pagina;
        $query = "SELECT * FROM imagenes_sueltas ORDER BY id_imagen DESC LIMIT $empieza, $por_pagina"; // Cambia el ORDER BY
        $resultado = mysqli_query($conn, $query);

        while ($post = mysqli_fetch_assoc($resultado)) {
            $imagesName = explode(",", $post['imagen']);
        ?>
        <div class="col" style="width: 275px">
            <div class="carousel slide" id="carousel<?=$post['id_imagen'] ?>" data-bs-ride="false">
                <div class="carousel-inner">
                    <?php for($j = 0; $j < count($imagesName); $j++) {
                        $isActive = $j == 0 ? 'active' : '';
                    ?>
                    <div class="carousel-item <?= $isActive ?>">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modal<?=$post['id_imagen']?>-<?=$j?>">
                            <div class="image-container" style="background-image: url('./assets/images/posts/<?= $imagesName[$j] ?>');"></div>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-body">
                    <div class="original-description">
                     <?php if (!isset($_GET['edit-description']) || $_GET['edit-description'] != $post['id_imagen']) { ?>
                        <?= $post['descripcion'] ?>
                        </div>
                        <div class="botones-utilidades">
                            <a href="eliminar-imagen.php?id_imagen=<?=$post['id_imagen']?>&index=<?=$j?>"><i class="bi bi-trash3"></i></a>
                            <button class="btn-edit-description" data-image-id="<?=$post['id_imagen']?>"><i class="bi bi-pencil-square"></i></button>
                            <a href="./assets/images/posts/<?= $imagesName[$j] ?>" download><i class="bi bi-download"></i></a>
                        </div>
                        <?php } ?>
                </div>
            </div>
            
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
        <?php } ?>
    </div>
</div>


<!-- Paginación -->

<div class="pagination">
    <?php
    $query_total = "SELECT COUNT(*) as total FROM imagenes_sueltas";
    $resultado_total = mysqli_query($conn, $query_total);
    $row_total = mysqli_fetch_assoc($resultado_total);

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



<!-- Modales -->
<?php
$resultado = mysqli_query($conn, $query); // Reiniciar el resultado
while ($post = mysqli_fetch_assoc($resultado)) {
    $imagesName = explode(",", $post['imagen']);
    foreach ($imagesName as $j => $imageName) {
?>
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
$(document).ready(function() {
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

<!-- Script de Modo Oscuro y Claro --> 

<script>
  // Función para establecer una cookie con un nombre, valor y duración
  function setCookie(name, value, days) {
    const expires = new Date();
    expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
  }

  // Función para obtener el valor de una cookie por nombre
  function getCookie(name) {
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
      const cookie = cookies[i].trim();
      if (cookie.startsWith(name + '=')) {
        return cookie.substring(name.length + 1);
      }
    }
    return null;
  }

  const modeText = document.querySelector(".mode-text");
  const darkModeSwitch = document.getElementById("darkModeSwitch");
  const body = document.body;
  let darkModeEnabled = false;

  // Función para cambiar el modo y guardar en cookies
  function toggleDarkMode() {
    darkModeEnabled = !darkModeEnabled;
    body.classList.toggle("dark");
    updateModalBackground();
    updateCardBodyBackground();

    if (body.classList.contains("dark")) {
      modeText.innerText = "Modo Claro";
      setCookie("darkMode", "enabled", 365);
    } else {
      modeText.innerText = "Modo Oscuro";
      setCookie("darkMode", "disabled", 365);
    }
  }

  // Comprueba el estado del modo oscuro al cargar la página
  document.addEventListener("DOMContentLoaded", () => {
    const darkMode = getCookie("darkMode");

    if (darkMode === "enabled") {
      body.classList.add("dark");
      darkModeEnabled = true;
      updateModalBackground();
      updateCardBodyBackground();
      modeText.innerText = "Modo Claro";
    } else if (darkMode === "disabled") {
      body.classList.remove("dark");
      updateModalBackground();
      updateCardBodyBackground();
      modeText.innerText = "Modo Oscuro";
    }

    // Evita la animación al recargar la página
    body.classList.add("initial-dark-mode");
    setTimeout(() => {
      body.classList.remove("initial-dark-mode");
    }, 0);

    if (!darkModeEnabled) {
      body.classList.remove("dark");
      setTimeout(() => {
        body.classList.add("dark");
        updateModalBackground();
        updateCardBodyBackground();
      }, 100);
    }
  });

  // Agrega un event listener al interruptor de modo oscuro
  darkModeSwitch.addEventListener("click", () => {
    toggleDarkMode();
  });

  // Función para actualizar el fondo de las cajas de modales
  function updateModalBackground() {
    const modalBodies = document.querySelectorAll(".modal-body");
    modalBodies.forEach((modalBody) => {
      if (darkModeEnabled) {
        modalBody.style.backgroundColor = "#18191a";
      } else {
        modalBody.style.backgroundColor = "#fff";
      }
    });
  }

  // Función para cambiar el color de fondo de los elementos .card-body
  function updateCardBodyBackground() {
    const cardBodies = document.querySelectorAll(".card-body");
    cardBodies.forEach((cardBody) => {
      if (darkModeEnabled) {
        cardBody.style.backgroundColor = "#18191a";
      } else {
        cardBody.style.backgroundColor = "#fff";
      }
    });
  }

  // Función para cambiar la barra lateral (sidebar)
  function toggleSidebar() {
    const sidebar = document.querySelector('nav');
    const mainContent = document.querySelector(".gallery-container");
    sidebar.classList.toggle("close");
    updateMargin();

    // Guarda el estado de la barra lateral en cookies
    const sidebarState = sidebar.classList.contains("close") ? "closed" : "open";
    setCookie("sidebarState", sidebarState, 365);
  }

  // Event listener para el botón de alternar la barra lateral
  toggle.addEventListener("click", () => {
    toggleSidebar();
  });

  // Función para actualizar el margen del contenido principal
  function updateMargin() {
    const sidebar = document.querySelector('nav');
    const mainContent = document.querySelector(".gallery-container");
    if (sidebar.classList.contains("close")) {
      mainContent.style.marginLeft = '0';
    } else {
      mainContent.style.marginLeft = '250px';
    }
  }

  // Llama a applySidebarState() después de cargar la página para establecer el estado de la barra lateral
  function applySidebarState() {
    const sidebarState = getCookie("sidebarState");
    if (sidebarState === "open") {
      toggleSidebar();
    }
  }
  applySidebarState();
</script>
<script src="navbar2.js"></script>
</body>
</html>