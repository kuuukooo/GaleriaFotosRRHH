<?php
session_start();
require "../database/database.php";

try {
    // Asegúrate de que el usuario haya iniciado sesión y el ID de usuario esté disponible
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['tipo_usuario'])) {
        echo json_encode(['error' => 'Usuario no autenticado o tipo de usuario no disponible']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $tipo_usuario = $_SESSION['tipo_usuario'];

    // Crear una instancia de la clase Database
    $database = new Database();

    // Obtener la conexión
    $conn = $database->getConnection();

    // Obtener el número de página de la solicitud AJAX
    $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
    $por_pagina = 9; // Número de imágenes por página

    // Calcular el inicio y el final para la consulta SQL
    $inicio = ($pagina - 1) * $por_pagina;

    // Obtener el término de búsqueda enviado desde el formulario
    if (isset($_POST['search'])) {
        $searchTerm = $_POST['search'];
        if (empty($searchTerm)) {
            echo json_encode(['error' => 'empty_search']);
            exit;
        }

        // Construir la consulta SQL según el tipo de usuario
        if ($tipo_usuario == 'Admin') {
            // Consulta SQL para buscar imágenes por descripción para admin
            $sql = "SELECT * FROM imagenes_sueltas WHERE descripcion LIKE :searchTerm ORDER BY id_imagen DESC LIMIT :inicio, :por_pagina";
        } else {
            // Consulta SQL para buscar imágenes por descripción para publisher
            $sql = "SELECT * FROM imagenes_sueltas WHERE id_usuario = :id_usuario AND descripcion LIKE :searchTerm ORDER BY id_imagen DESC LIMIT :inicio, :por_pagina";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
        $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':por_pagina', $por_pagina, PDO::PARAM_INT);

        if ($tipo_usuario != 'Admin') {
            $stmt->bindValue(':id_usuario', $user_id, PDO::PARAM_INT);
        }

        $stmt->execute();

        // Crear un array para almacenar los datos de la imagen
        $imagenes = [];

        while ($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $imagesName = explode(",", $post['imagen']);

            // Agregar los datos de cada imagen al array
            $imagen = [
                'id_imagen' => $post['id_imagen'],
                'descripcion' => $post['descripcion'],
                'imagenes' => $imagesName,
                'pagina_actual' => $pagina, // Agregar el número de página actual
            ];

            $imagenes[] = $imagen;
        }

        if (count($imagenes) == 0) {
            echo json_encode(['error' => 'no_images_found']);
            exit;
        }

        // Obtener el número total de imágenes según el tipo de usuario y el término de búsqueda
        if ($tipo_usuario == 'Admin') {
            $queryTotal = "SELECT COUNT(*) as total FROM imagenes_sueltas WHERE descripcion LIKE :searchTerm";
            $stmtTotal = $conn->prepare($queryTotal);
            $stmtTotal->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
        } else {
            $queryTotal = "SELECT COUNT(*) as total FROM imagenes_sueltas WHERE id_usuario = :id_usuario AND descripcion LIKE :searchTerm";
            $stmtTotal = $conn->prepare($queryTotal);
            $stmtTotal->bindValue(':id_usuario', $user_id, PDO::PARAM_INT);
            $stmtTotal->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
        }

        $stmtTotal->execute();
        $total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

        // Calcular el número total de páginas
        $totalPaginas = ceil($total / $por_pagina);

        // Crear un array que incluya las imágenes y el número total de páginas
        $respuesta = [
            'imagenes' => $imagenes,
            'totalPaginas' => $totalPaginas,
        ];

        // Devolver los datos de la imagen y el número total de páginas en formato JSON
        echo json_encode($respuesta);
    } else {
        // Si no se recibió término de búsqueda
        echo json_encode(['error' => 'No se encontraron imágenes que coincidan con la búsqueda.']);
    }
} catch (PDOException $e) {
    // Manejo de errores para la conexión a la base de datos
    echo json_encode(['error' => 'Error en la conexión a la base de datos: ' . $e->getMessage()]);
}
