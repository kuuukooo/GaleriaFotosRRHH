<?php
require "../database/database.php";

// Crea una instancia de la clase Database
$database = new Database();

// Obtiene la conexión
$conn = $database->getConnection();

// Verifica si se recibieron los datos necesarios
if (isset($_POST['selectedImages'])) {
    $selectedImages = $_POST['selectedImages'];

    // Inicializa un contador para rastrear las imágenes actualizadas con éxito
    $updatedCount = 0;

    // Prepara la consulta para actualizar el campo es_publico
    $query = "UPDATE imagenes_sueltas SET es_publico = CASE WHEN es_publico = 0 THEN 1 ELSE 0 END WHERE id_imagen = :id_imagen";
    $stmt = $conn->prepare($query);

    // Itera sobre cada imagen seleccionada para actualizar su estado
    foreach ($selectedImages as $id_imagen) {
        $stmt->bindParam(':id_imagen', $id_imagen, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $updatedCount++;
        }
    }

    // Verifica si todas las imágenes se actualizaron correctamente
    if ($updatedCount === count($selectedImages)) {
        echo json_encode(array('status' => 'success', 'message' => 'Estados actualizados correctamente.'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Error al actualizar algunos estados.'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Datos incompletos.'));
}