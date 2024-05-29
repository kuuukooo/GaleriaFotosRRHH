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
            // Preparar la consulta para eliminar las imágenes
            $placeholders = implode(',', array_fill(0, count($selectedImages), '?'));
            $sql = "DELETE FROM imagenes_sueltas WHERE id_imagen IN ($placeholders)";

            $stmt = $conn->prepare($sql);

            // Vincular los parámetros
            foreach ($selectedImages as $key => $value) {
                $stmt->bindValue(($key + 1), $value, PDO::PARAM_INT);
            }

            // Ejecutar la consulta
            $stmt->execute();

            echo "Imagenes eliminadas con éxito.";
        } catch (PDOException $e) {
            echo "Error al eliminar las imagenes: " . $e->getMessage();
        }

        // Cerrar la conexión y la declaración
        $stmt = null;
        $conn = null;
    } else {
        echo "No se recibieron álbumes para eliminar.";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>
