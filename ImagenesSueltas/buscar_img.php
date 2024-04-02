<?php
require "../database/database.php";

try {
    // Create an instance of the Database class
    $database = new Database();

    // Get the connection
    $conn = $database->getConnection();

    // Get the page number from the AJAX request
    $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
    $por_pagina =  9; // Number of images per page

    // Calculate the start and end for the SQL query
    $inicio = ($pagina - 1) * $por_pagina;

    // Get the search term sent from the form
    if (isset($_POST['search'])) {
        $searchTerm = $_POST['search'];
        if(empty($searchTerm)) {
            echo json_encode(array('error' => 'empty_search'));
        } else {
            // SQL query to search for an image by description
            $sql = "SELECT * FROM imagenes_sueltas WHERE descripcion LIKE :searchTerm";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
            $stmt->execute();

            // Create an array to store the image data
            $imagenes = array();

            while ($post = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $imagesName = explode(",", $post['imagen']);

                // Add the data for each image to the array
                $imagen = array(
                    'id_imagen' => $post['id_imagen'],
                    'descripcion' => $post['descripcion'],
                    'imagenes' => $imagesName,
                    'pagina_actual' => $pagina, // Add the current page number
                );

                $imagenes[] = $imagen;
            }

            if(count($imagenes) == 0) {
                echo json_encode(array('error' => 'no_images_found'));
            } else {
                // Get the total number of images
                $queryTotal = "SELECT COUNT(*) as total FROM imagenes_sueltas";
                $stmtTotal = $conn->prepare($queryTotal);
                $stmtTotal->execute();
                $total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

                // Calculate the total number of pages
                $totalPaginas = ceil($total / $por_pagina);

                // Create an array that includes the images and the total number of pages
                $respuesta = array(
                    'imagenes' => $imagenes,
                    'totalPaginas' => $totalPaginas
                );

                // Return the image data and the total number of pages in JSON format
                echo json_encode($respuesta);
            }
        }
    } else {
        // If no results were found
        echo json_encode(array('error' => 'No se encontraron imágenes que coincidan con la búsqueda.'));
    }
} catch (PDOException $e) {
    // Error handling for database connection
    echo json_encode(array('error' => 'Error en la conexión a la base de datos: ' . $e->getMessage()));
}
?>