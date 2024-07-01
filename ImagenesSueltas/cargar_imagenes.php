<?php
session_start();
require "../database/database.php";

// Asegúrate de que el usuario haya iniciado sesión y el ID de usuario esté disponible
if (!isset($_SESSION['user_id']) || !isset($_SESSION['tipo_usuario'])) {
    echo json_encode(['error' => 'Usuario no autenticado o tipo de usuario no disponible']);
    exit;
}

$user_id = $_SESSION['user_id'];
$tipo_usuario = $_SESSION['tipo_usuario'];

// Crea una instancia de la clase Database
$database = new Database();

// Obtiene la conexión
$conn = $database->getConnection();

// Obtener el número de página desde la solicitud AJAX
$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$por_pagina = 9; // Cantidad de imágenes por página

// Calcula el inicio y fin para la consulta SQL
$inicio = ($pagina - 1) * $por_pagina;

if ($tipo_usuario == 'Admin') {
    // Realiza la consulta para obtener todas las imágenes si el usuario es Admin
    $query = "SELECT * FROM imagenes_sueltas ORDER BY id_imagen DESC LIMIT :inicio, :por_pagina";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
    $stmt->bindParam(':por_pagina', $por_pagina, PDO::PARAM_INT);
} else {
    // Realiza la consulta para obtener las imágenes del usuario logueado si el usuario es Publisher
    $query = "SELECT * FROM imagenes_sueltas WHERE id_usuario = :id_usuario ORDER BY id_imagen DESC LIMIT :inicio, :por_pagina";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_usuario', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
    $stmt->bindParam(':por_pagina', $por_pagina, PDO::PARAM_INT);
}

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
        'es_publico' => $post['es_publico'], // Incluir el campo es_publico
        'pagina_actual' => $pagina, // Agrega el número de página actual
    );

    $imagenes[] = $imagen;
}

// Obtener el número total de imágenes
if ($tipo_usuario == 'Admin') {
    $queryTotal = "SELECT COUNT(*) as total FROM imagenes_sueltas";
    $stmtTotal = $conn->prepare($queryTotal);
} else {
    $queryTotal = "SELECT COUNT(*) as total FROM imagenes_sueltas WHERE id_usuario = :id_usuario";
    $stmtTotal = $conn->prepare($queryTotal);
    $stmtTotal->bindParam(':id_usuario', $user_id, PDO::PARAM_INT);
}

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

