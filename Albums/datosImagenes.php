<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/Galeria5-AJAX/database/database.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['tipo_usuario'])) {
    echo json_encode(['error' => 'Usuario no autenticado o tipo de usuario no disponible']);
    exit;
}

$user_id = $_SESSION['user_id'];
$tipo_usuario = $_SESSION['tipo_usuario'];

try {
    $database = new Database();
    $conn = $database->getConnection();

    if ($tipo_usuario == 'Admin') {
        $sql = "SELECT 
                    a.id_album, 
                    a.descripcion AS album_descripcion, 
                    a.imagen AS album_miniatura, 
                    a.fecha_creacion, 
                    u.usuario AS nombre_usuario, 
                    i.id_img_alb, 
                    i.descripcion AS imagen_descripcion, 
                    i.imagen, 
                    i.id_album AS imagen_id_album,
                    GROUP_CONCAT(i.imagen) AS imagenes
                FROM 
                    albumes a
                LEFT JOIN 
                    imagenes_albumes i ON a.id_album = i.id_album
                LEFT JOIN
                    usuarios u ON a.id_usuario = u.id_usuario
                GROUP BY 
                    a.id_album, 
                    u.usuario
                ORDER BY 
                    a.id_album, 
                    i.id_img_alb";
        $stmt = $conn->prepare($sql);
    } else {
        $sql = "SELECT 
                    a.id_album, 
                    a.descripcion AS album_descripcion, 
                    a.imagen AS album_miniatura, 
                    a.fecha_creacion, 
                    u.usuario AS nombre_usuario, 
                    i.id_img_alb, 
                    i.descripcion AS imagen_descripcion, 
                    i.imagen, 
                    i.id_album AS imagen_id_album,
                    GROUP_CONCAT(i.imagen) AS imagenes
                FROM 
                    albumes a
                LEFT JOIN 
                    imagenes_albumes i ON a.id_album = i.id_album
                LEFT JOIN
                    usuarios u ON a.id_usuario = u.id_usuario
                WHERE 
                    a.id_usuario = :id_usuario
                GROUP BY 
                    a.id_album, 
                    u.usuario
                ORDER BY 
                    a.id_album, 
                    i.id_img_alb";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $user_id, PDO::PARAM_INT);
    }

    $stmt->execute();

    $datos = array();

    if ($stmt->rowCount() > 0) {
        $current_album = null;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $album_id = $row["id_album"];
            if ($current_album !== $album_id) {
                $current_album = $album_id;
                $datos[$album_id] = array(
                    "id_album" => $row["id_album"],
                    "descripcion" => $row["album_descripcion"],
                    "miniatura" => $row["album_miniatura"],
                    "fecha_creacion" => $row["fecha_creacion"],
                    "nombre_usuario" => $row["nombre_usuario"],
                    "imagenes" => array()
                );

                $imagenes = explode(',', $row['imagenes']);
                foreach ($imagenes as $imagen) {
                    $datos[$album_id]["imagenes"][] = array(
                        "id_img_alb" => $row["id_img_alb"],
                        "descripcion" => $row["imagen_descripcion"],
                        "imagen" => $imagen,
                        "id_album" => $row["imagen_id_album"]
                    );
                }

                // Validar si la miniatura es una URL válida
                if (empty($datos[$album_id]["miniatura"]) && !empty($datos[$album_id]["imagenes"])) {
                    $datos[$album_id]["miniatura"] = $datos[$album_id]["imagenes"][0]["imagen"];
                } else {
                    // Validar la ruta de la miniatura si existe
                    $miniaturaPath = $_SERVER['DOCUMENT_ROOT'] . '/Galeria5-AJAX/Albums/fotos/' . $datos[$album_id]["miniatura"];
                    if (!file_exists($miniaturaPath)) {
                        $datos[$album_id]["miniatura"] = !empty($datos[$album_id]["imagenes"]) ? $datos[$album_id]["imagenes"][0]["imagen"] : '';
                    }
                }
            }
        }
    }

    echo json_encode($datos);
} catch (PDOException $e) {
    echo json_encode(array("error" => "Error en la conexión a la base de datos: " . $e->getMessage()));
}

