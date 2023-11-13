<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
require "controllers/posts.php";
require "./database/database.php";

$response = []; // Inicializar una respuesta vacía

if (isset($_POST['id_imagen'])) {
    $imageId = $_POST['id_imagen'];

    try {
        // Crea una instancia de la clase Database
        $database = new Database();

        // Obtiene la conexión
        $conn = $database->getConnection();

        // Obtener información de la imagen
        $query = "SELECT * FROM imagenes_sueltas WHERE id_imagen = :imageId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':imageId', $imageId, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post) {
            // Eliminar la imagen del sistema de archivos
            $imagesName = explode(",", $post['imagen']);
            foreach ($imagesName as $imageNameToDelete) {
                $imagePath = "./assets/images/posts/" . $imageNameToDelete;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Eliminar la entrada de la base de datos
            $deleteQuery = "DELETE FROM imagenes_sueltas WHERE id_imagen = :imageId";
            $stmt = $conn->prepare($deleteQuery);
            $stmt->bindParam(':imageId', $imageId, PDO::PARAM_INT);
            $stmt->execute();

            $response['success'] = "Imagen eliminada exitosamente.";
        } else {
            $response['error'] = "No se encontró la imagen.";
        }
    } catch (PDOException $e) {
        $response['error'] = "Error al eliminar la imagen: " . $e->getMessage();
    }
} else {
    $response['error'] = "Error al eliminar la imagen.";
}

header("Content-Type: application/json");
echo json_encode($response);
?>
