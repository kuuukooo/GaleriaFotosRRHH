<?php
            require "./database/database.php";

            // Obtener el número de página desde la solicitud AJAX
            $pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
            $por_pagina = 12; // Cantidad de imágenes por página

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
                );

                $imagenes[] = $imagen;
            }

            // Retorna los datos de las imágenes en formato JSON
            echo json_encode($imagenes);
?>