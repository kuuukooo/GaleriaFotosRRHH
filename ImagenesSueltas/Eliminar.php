<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['selectedImages'])) {
        $selectedImages = $_POST['selectedImages'];

        // Incluir el archivo de conexión a la base de datos
        require "../database/database.php";

        // Crear una instancia de la clase Database
        $db = new Database();
        $conn = $db->getConnection();

        try {
            // Preparar la consulta para obtener las rutas de las imágenes
            $placeholders = implode(',', array_fill(0, count($selectedImages), '?'));
            $sqlSelect = "SELECT imagen FROM imagenes_sueltas WHERE id_imagen IN ($placeholders)";

            $stmtSelect = $conn->prepare($sqlSelect);

            // Vincular los parámetros
            foreach ($selectedImages as $key => $value) {
                $stmtSelect->bindValue(($key + 1), $value, PDO::PARAM_INT);
            }

            // Ejecutar la consulta
            $stmtSelect->execute();
            $imagesToDelete = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);

            // Eliminar las imágenes físicas del sistema de archivos
            foreach ($imagesToDelete as $imageRow) {
                $imagesName = explode(",", $imageRow['imagen']);
                foreach ($imagesName as $imageName) {
                    $imagePath = "../assets/images/posts/" . $imageName;
                    if (file_exists($imagePath)) {
                        if (!unlink($imagePath)) {
                            throw new Exception("Error al eliminar la imagen del sistema de archivos: " . $imagePath);
                        }
                    }
                }
            }

            // Preparar la consulta para eliminar las imágenes de la base de datos
            $sqlDelete = "DELETE FROM imagenes_sueltas WHERE id_imagen IN ($placeholders)";
            $stmtDelete = $conn->prepare($sqlDelete);

            // Vincular los parámetros
            foreach ($selectedImages as $key => $value) {
                $stmtDelete->bindValue(($key + 1), $value, PDO::PARAM_INT);
            }

            // Ejecutar la consulta
            $stmtDelete->execute();

            echo json_encode(["success" => "Imágenes eliminadas con éxito."]);
        } catch (Exception $e) {
            echo json_encode(["error" => "Error al eliminar las imágenes: " . $e->getMessage()]);
        }

        // Cerrar la conexión y las declaraciones
        $stmtSelect = null;
        $stmtDelete = null;
        $conn = null;
    } else {
        echo json_encode(["error" => "No se recibieron imágenes para eliminar."]);
    }
} else {
    echo json_encode(["error" => "Método de solicitud no válido."]);
}
?>
