<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/Galeria5-AJAX/database/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();

    // Get the search term sent from the form
    if (isset($_POST['search'])) {
        $searchTerm = $_POST['search'];
        if(empty($searchTerm)) {
            echo json_encode(array('error' => 'empty_search'));
        } else {
            // SQL query to search for an image by description
            $sql = "SELECT 
                        a.id_album, 
                        a.descripcion AS album_descripcion, 
                        a.imagen AS album_miniatura, 
                        a.fecha_creacion, -- Agregar la columna fecha_creacion aquí
                        i.id_img_alb, 
                        i.descripcion AS imagen_descripcion, 
                        i.imagen, 
                        i.id_album AS imagen_id_album,
                        GROUP_CONCAT(i.imagen) AS imagenes
                    FROM 
                        albumes a
                    LEFT JOIN 
                        imagenes_albumes i ON a.id_album = i.id_album
                    WHERE
                        i.descripcion LIKE :searchTerm
                    GROUP BY 
                        a.id_album, 
                        i.id_img_alb
                    ORDER BY 
                        a.id_album, 
                        i.id_img_alb";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($result)) {
                echo json_encode(array('error' => 'no_images_found'));
            } else {
                $datos = array();
                $current_album = null;

                foreach ($result as $row) {
                    if ($current_album !== $row["id_album"]) {
                        $current_album = $row["id_album"];
                        $datos[$current_album]["id_album"] = $row["id_album"];
                        $datos[$current_album]["descripcion"] = $row["album_descripcion"];
                        $datos[$current_album]["miniatura"] = $row["album_miniatura"];
                        $datos[$current_album]["fecha_creacion"] = $row["fecha_creacion"];
                        $datos[$current_album]["imagenes"] = array();

                        $imagenes = explode(',', $row['imagenes']);
                        foreach ($imagenes as $imagen) {
                            $datos[$current_album]["imagenes"][] = array(
                                "id_img_alb" => $row["id_img_alb"],
                                "descripcion" => $row["imagen_descripcion"],
                                "imagen" => $imagen,
                                "id_album" => $row["imagen_id_album"],
                                "fecha_creacion" => $row["fecha_creacion"]
                            );
                        }

                        if (count($datos[$current_album]["imagenes"]) > 0) {
                            $datos[$current_album]["miniatura"] = $datos[$current_album]["imagenes"][0]["imagen"];
                        }
                    }
                }

                echo json_encode($datos);
            }
        }
    }
} catch (PDOException $e) {
    echo json_encode(array("error" => "Error en la conexión a la base de datos: " . $e->getMessage()));
}