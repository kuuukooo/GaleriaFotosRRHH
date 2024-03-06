<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/Galeria5-AJAX/database/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();

    $sql = "SELECT 
            a.id_album, 
            a.descripcion AS album_descripcion, 
            a.imagen AS album_miniatura, 
            i.id_img_alb, 
            i.descripcion AS imagen_descripcion, 
            i.imagen, 
            i.id_album AS imagen_id_album,
            GROUP_CONCAT(i.imagen) AS imagenes
        FROM 
            albumes a
        LEFT JOIN 
            imagenes_albumes i ON a.id_album = i.id_album
        GROUP BY 
            a.id_album, 
            i.id_img_alb
        ORDER BY 
            a.id_album, 
            i.id_img_alb";

    $result = $conn->query($sql);

    $datos = array();

    if ($result->rowCount() > 0) {
        $current_album = null;

        while ($row = $result->fetch()) {
            if ($current_album !== $row["id_album"]) {
                $current_album = $row["id_album"];
                $datos[$current_album]["id_album"] = $row["id_album"];
                $datos[$current_album]["descripcion"] = $row["album_descripcion"];
                $datos[$current_album]["miniatura"] = $row["album_miniatura"];
                $datos[$current_album]["imagenes"] = array();
        
                // Dividir la cadena de imágenes en una matriz
                $imagenes = explode(',', $row['imagenes']);
                foreach ($imagenes as $imagen) {
                    $datos[$current_album]["imagenes"][] = array(
                        "id_img_alb" => $row["id_img_alb"], // o puedes asignar un valor si es necesario
                        "descripcion" => $row["imagen_descripcion"], // o puedes asignar un valor si es necesario
                        "imagen" => $imagen,
                        "id_album" => $row["imagen_id_album"]
                    );
                }

                // Si hay imágenes en el álbum, designa la primera como miniatura
                if (count($datos[$current_album]["imagenes"]) > 0) {
                    $datos[$current_album]["miniatura"] = $datos[$current_album]["imagenes"][0]["imagen"];
                }
            }
        }  
    }

    echo json_encode($datos);
} catch (PDOException $e) {
    echo json_encode(array("error" => "Error en la conexión a la base de datos: " . $e->getMessage()));
}
