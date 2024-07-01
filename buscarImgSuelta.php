<?php
require "./database/database.php";

// Crea una instancia de la clase Database
$database = new Database();

// Obtiene la conexión
$conn = $database->getConnection();

// Obtener el término de búsqueda desde la solicitud AJAX
$searchTerm = isset($_POST['search']) ? trim($_POST['search']) : '';

// Verifica si el término de búsqueda está vacío
if (empty($searchTerm)) {
    echo json_encode(['error' => 'empty_search']);
    exit;
}

// Obtener el número de página desde la solicitud AJAX
$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$por_pagina = 9; // Cantidad de imágenes por página

// Calcula el inicio y fin para la consulta SQL
$inicio = ($pagina - 1) * $por_pagina;

// Realiza la consulta para obtener las imágenes de la página actual que coincidan con el término de búsqueda
$query = "
    SELECT imagenes_sueltas.*, usuarios.Usuario AS usuario
    FROM imagenes_sueltas
    INNER JOIN usuarios ON imagenes_sueltas.id_usuario = usuarios.id_usuario
    WHERE imagenes_sueltas.es_publico = 1 AND imagenes_sueltas.descripcion LIKE :searchTerm
    ORDER BY imagenes_sueltas.id_imagen DESC
    LIMIT :inicio, :por_pagina
";
$stmt = $conn->prepare($query);
$searchTermWithWildcards = '%' . $searchTerm . '%';
$stmt->bindParam(':searchTerm', $searchTermWithWildcards, PDO::PARAM_STR);
$stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindParam(':por_pagina', $por_pagina, PDO::PARAM_INT);
$stmt->execute();

// Crear un array para almacenar los datos de las imágenes
$imagenes = array();

while ($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $imagesName = explode(",", $post['imagen']);

    // Agrega los datos de cada imagen al array, asignando el nombre del usuario a 'descripcion' y la descripción original a 'titulo'
    $imagen = array(
        'id_imagen' => $post['id_imagen'],
        'titulo' => $post['descripcion'], // Asigna la descripción original de la imagen a 'titulo'
        'descripcion' => $post['usuario'], // Asigna el nombre del usuario a 'descripcion'
        'imagenes' => $imagesName,
        'pagina_actual' => $pagina, // Agrega el número de página actual
    );

    $imagenes[] = $imagen;
}

// Obtener el número total de imágenes que coincidan con el término de búsqueda
$queryTotal = "SELECT COUNT(*) as total FROM imagenes_sueltas WHERE es_publico = 1 AND descripcion LIKE :searchTerm";
$stmtTotal = $conn->prepare($queryTotal);
$stmtTotal->bindParam(':searchTerm', $searchTermWithWildcards, PDO::PARAM_STR);
$stmtTotal->execute();
$total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

// Verifica si se encontraron imágenes
if ($total == 0) {
    echo json_encode(['error' => 'no_images_found']);
    exit;
}

// Calcular el número total de páginas
$totalPaginas = ceil($total / $por_pagina);

// Crear un array que incluya las imágenes y el número total de páginas
$respuesta = array(
    'imagenes' => $imagenes,
    'totalPaginas' => $totalPaginas
);

// Retorna los datos de las imágenes y el número total de páginas en formato JSON
echo json_encode($respuesta);
