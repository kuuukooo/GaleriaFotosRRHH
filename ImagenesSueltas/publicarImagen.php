<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/Galeria5-AJAX/database/database.php';
header('Content-Type: application/json');
$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_imagen'])) {
    $id_imagen = intval($_POST['id_imagen']);

    try {
        $database = new Database();
        $conn = $database->getConnection();

        // Preparar la consulta para invertir el estado de es_publico
        $query = "UPDATE imagenes_sueltas SET es_publico = CASE WHEN es_publico = 1 THEN 0 ELSE 1 END WHERE id_imagen = :id_imagen";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_imagen', $id_imagen, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Obtener el nuevo estado de es_publico
            $query = "SELECT es_publico FROM imagenes_sueltas WHERE id_imagen = :id_imagen";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id_imagen', $id_imagen, PDO::PARAM_INT);
            $stmt->execute();
            $nuevoEstado = $stmt->fetchColumn();

            $response['success'] = true;
            $response['message'] = 'Estado actualizado correctamente.';
            $response['nuevo_estado'] = $nuevoEstado;
        } else {
            $response['success'] = false;
            $response['error'] = 'Error al actualizar el estado.';
        }
    } catch (PDOException $e) {
        $response['success'] = false;
        $response['error'] = 'Error de conexión a la base de datos: ' . $e->getMessage();
    }
} else {
    $response['success'] = false;
    $response['error'] = 'Solicitud no válida. Asegúrate de enviar los datos correctos.';
}

echo json_encode($response);

