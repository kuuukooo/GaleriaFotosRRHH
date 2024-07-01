<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require $_SERVER['DOCUMENT_ROOT'] . '/Galeria5-AJAX/database/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();

    // Obtener el id_imagen del parámetro de la solicitud
    $imagenID = $_POST['id_imagen'];

    $sql = "SELECT 
                i.id_imagen, 
                i.descripcion, 
                i.imagen, 
                i.fecha_carga, 
                i.id_usuario, 
                u.Usuario AS usuario
            FROM 
                imagenes_sueltas i
            LEFT JOIN 
                usuarios u ON i.id_usuario = u.id_usuario
            WHERE 
                i.id_imagen = :imagenID";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':imagenID', $imagenID, PDO::PARAM_INT);
    $stmt->execute();

    $datos = array();
    $datos["imagenes"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Almacenar los datos de la imagen
        if (empty($datos["id_imagen"])) {
            $datos["id_imagen"] = $row["id_imagen"];
            $datos["descripcion"] = $row["descripcion"];
            $datos["fecha_carga"] = $row["fecha_carga"];
            $datos["usuario"] = $row["usuario"];
        }

        // Separar las imágenes por comas y almacenar en un array
        $imagenes = explode(',', $row["imagen"]);
        foreach ($imagenes as $imagen) {
            $datos["imagenes"][] = array(
                "imagen" => trim($imagen),
                "descripcion" => $row["descripcion"],
            );
        }
    }

    echo json_encode($datos);
} catch (PDOException $e) {
    echo json_encode(array("error" => "Error en la conexión a la base de datos: " . $e->getMessage()));
}
?>
