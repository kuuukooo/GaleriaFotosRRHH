<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<?php
// Código para conectarse a la base de datos y configurar las consultas SQL
require "./database/database.php";
$conn = mysqli_connect("localhost", "root", "", "galeria");

$por_pagina = 12;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $por_pagina;
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT * FROM imagenes_sueltas WHERE descripcion LIKE '%$searchTerm%' ORDER BY id_imagen DESC LIMIT $start, $por_pagina";
$resultado = mysqli_query($conn, $query);

// Genera el HTML para las imágenes y descripciones
$output= '';

while ($post = mysqli_fetch_assoc($resultado)) {
    $imagesName = explode(",", $post['imagen']);
    
    $output .= '<div class="col" style="width: 275px">';
    
    // Loop para mostrar todas las imágenes del post
    $output .= '<div class="carousel slide" id="carousel' . $post['id_imagen'] . '" data-bs-ride="false">';
    $output .= '<div class="carousel-inner">';
    
    foreach ($imagesName as $j => $imageName) {
        $isActive = $j === 0 ? 'active' : '';
        $output .= '<div class="carousel-item ' . $isActive . '">';
        $output .= '<a href="#" data-bs-toggle="modal" data-bs-target="#modal' . $post['id_imagen'] . '-' . $j . '">';
        $output .= '<div class="image-container" style="background-image: url(\'./assets/images/posts/' . $imagesName[$j] . '\');"></div>';
        $output .= '</a>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    $output .= '</div>';
    
    $output .= '<div class="card mt-2">';
    $output .= '<div class="card-body">';
    $output .= $post['descripcion'];
    $output .= '</div>';
    $output .= '</div>';
    
    $output .= '</div>';
}

echo $output; // Envía la respuesta AJAX
?>
