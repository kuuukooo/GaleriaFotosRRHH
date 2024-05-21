<?php
// Tu conexión a la base de datos
require "../database/database.php";

$database = new Database();
$conn = $database->getConnection();

// Verificar la conexión a la base de datos
if ($conn) {
    // Inicializar la respuesta como un array
    $response = array();

    // Obtener los datos del formulario
    $ids_usuario = isset($_POST['id_usuario']) ? $_POST['id_usuario'] : array();

    // Verificar si se proporcionaron IDs de usuario
    if (!empty($ids_usuario)) {
        foreach ($ids_usuario as $id_usuario) {
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
                    $response[] = array('id_usuario' => $id_usuario, 'success' => true, 'message' => 'Usuario borrado correctamente.');
                } else {
                    // Error al borrar usuario
                    $response[] = array('id_usuario' => $id_usuario, 'success' => false, 'message' => 'Error al borrar usuario.');
                }
            } else {
                // El usuario no existe
                $response[] = array('id_usuario' => $id_usuario, 'success' => false, 'message' => 'El usuario no existe.');
            }
        }
    } else {
        // No se proporcionaron IDs de usuario
        $response = array('success' => false, 'message' => 'No se proporcionaron IDs de usuario.');
    }
} else {
    // Error de conexión a la base de datos
    $response = array('success' => false, 'message' => 'Error en la conexión a la base de datos.');
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
