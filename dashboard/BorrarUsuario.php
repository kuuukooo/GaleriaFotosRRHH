<?php
// Tu conexión a la base de datos
require "../database/database.php";

$database = new Database();
$conn = $database->getConnection();

// Verificar la conexión a la base de datos
if ($conn) {
    // Obtener los datos del formulario
    $id_usuario = $_GET['id_usuario'] ?? null;

    // Verificar si el usuario existe
    $querySelect = "SELECT * FROM usuarios WHERE id_usuario = :id_usuario";
    $stmtSelect = $conn->prepare($querySelect);
    $stmtSelect->bindParam(':id_usuario', $id_usuario);
    $stmtSelect->execute();

    // Comprobar si el usuario existe antes de intentar borrarlo
    if ($stmtSelect->rowCount() > 0) {
        // El usuario existe, proceder con la eliminación
        $queryDelete = "DELETE FROM usuarios WHERE id_usuario = :id_usuario";
        $stmtDelete = $conn->prepare($queryDelete);
        $stmtDelete->bindParam(':id_usuario', $id_usuario);

        // Ejecutar la consulta de eliminación
        if ($stmtDelete->execute()) {
            // Éxito: Devolver una respuesta en formato JSON
            $response = array('success' => true, 'message' => 'Usuario borrado correctamente.');
        } else {
            // Error al borrar usuario
            $response = array('success' => false, 'message' => 'Error al borrar usuario.');
        }
    } else {
        // El usuario no existe
        $response = array('success' => false, 'message' => 'El usuario no existe.');
    }
} else {
    // Error de conexión a la base de datos
    $response = array('success' => false, 'message' => 'Error en la conexión a la base de datos.');
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
