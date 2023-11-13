<?php
require "./database/database.php";

// Crea una instancia de la clase Database
$database = new Database();

// Obtiene la conexión
$conn = $database->getConnection();




// Obtener el número de página desde la solicitud AJAX
$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$por_pagina =  9; // Cantidad de imágenes por página

// Calcula el inicio y fin para la consulta SQL
$inicio = ($pagina - 1) * $por_pagina;

// Realiza la consulta para obtener las imágenes de la página actual
$query = "SELECT * FROM imagenes_sueltas ORDER BY id_imagen DESC LIMIT $inicio, $por_pagina";
$stmt = $conn->prepare($query);
$stmt->execute();

// Crear un array para almacenar los datos de las imágenes
$imagenes = array();

while ($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $imagesName = explode(",", $post['imagen']);

    // Agrega los datos de cada imagen al array
    $imagen = array(
        'id_imagen' => $post['id_imagen'],
        'descripcion' => $post['descripcion'],
        'imagenes' => $imagesName,
        'pagina_actual' => $pagina, // Agrega el número de página actual
    );

    $imagenes[] = $imagen;
}

// Obtener el número total de imágenes
$queryTotal = "SELECT COUNT(*) as total FROM imagenes_sueltas";
$stmtTotal = $conn->prepare($queryTotal);
$stmtTotal->execute();
$total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

// Calcular el número total de páginas
$totalPaginas = ceil($total / $por_pagina);

// Crear un array que incluya las imágenes y el número total de páginas
$respuesta = array(
    'imagenes' => $imagenes,
    'totalPaginas' => $totalPaginas
);

// Retorna los datos de las imágenes y el número total de páginas en formato JSON
echo json_encode($respuesta);
?>