<?php
require "../database/database.php";

// Crea una instancia de la clase Database
$database = new Database();

// Obtiene la conexión
$conn = $database->getConnection();

// Verifica si se recibieron los datos necesarios
if (isset($_POST['id_imagen']) && isset($_POST['es_publico'])) {
    $id_imagen = intval($_POST['id_imagen']);
    $es_publico = intval($_POST['es_publico']);

    // Prepara la consulta para actualizar el campo es_publico
    $query = "UPDATE imagenes_sueltas SET es_publico = :es_publico WHERE id_imagen = :id_imagen";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':es_publico', $es_publico, PDO::PARAM_INT);
    $stmt->bindParam(':id_imagen', $id_imagen, PDO::PARAM_INT);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        echo json_encode(array('status' => 'success', 'message' => 'Estado actualizado correctamente.'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Error al actualizar el estado.'));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Datos incompletos.'));
}
?>
