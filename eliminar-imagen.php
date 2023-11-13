<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
require "./controllers/posts.php";

$response = []; // Inicializar una respuesta vacía

if (isset($_POST['id_imagen'])) {
    $imageId = $_POST['id_imagen'];

    // Obtener información de la imagen
    $conn = mysqli_connect("localhost", "root", "", "galeria");
    $query = "SELECT * FROM imagenes_sueltas WHERE id_imagen = $imageId";
    $result = mysqli_query($conn, $query);
    $post = mysqli_fetch_assoc($result);

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
        $deleteQuery = "DELETE FROM imagenes_sueltas WHERE id_imagen = $imageId";
        mysqli_query($conn, $deleteQuery);

        $response['success'] = "Imagen eliminada exitosamente.";
    } else {
        $response['error'] = "No se encontró la imagen.";
    }
} else {
    $response['error'] = "Error al eliminar la imagen.";
}
header("Content-Type: application/json");
echo json_encode($response);
//Código revisado con el de Lucas.
?>