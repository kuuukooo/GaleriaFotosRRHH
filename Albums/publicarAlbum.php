<?php
// Iniciar la sesión si es necesario
session_start();

// Incluir el archivo de configuración de la base de datos
require $_SERVER['DOCUMENT_ROOT'] . '/Galeria5-AJAX/database/database.php';

// Configurar el contenido de respuesta como JSON
header('Content-Type: application/json');

// Array para la respuesta
$response = [];

// Verificar que se haya recibido un POST válido
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_album']) && isset($_POST['es_publico'])) {
    
    // Obtener el ID del álbum y el nuevo estado de visibilidad
    $id_album = intval($_POST['id_album']);
    $es_publico = intval($_POST['es_publico']);

    try {
        // Establecer la conexión con la base de datos
        $database = new Database();
        $conn = $database->getConnection();

        // Preparar la consulta para actualizar el campo 'es_publico'
        $query = "UPDATE albumes SET es_publico = :es_publico WHERE id_album = :id_album";
        $stmt = $conn->prepare($query);
        
        // Enlazar los parámetros
        $stmt->bindParam(':es_publico', $es_publico, PDO::PARAM_INT);
        $stmt->bindParam(':id_album', $id_album, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Si la actualización fue exitosa
            $response['success'] = true;
            $response['message'] = 'Estado del álbum actualizado correctamente.';
        } else {
            // Si hubo un error al ejecutar la consulta
            $response['success'] = false;
            $response['error'] = 'Error al actualizar el estado del álbum.';
        }
    } catch (PDOException $e) {
        // Manejar errores de la base de datos
        $response['success'] = false;
        $response['error'] = 'Error de conexión a la base de datos: ' . $e->getMessage();
    }
} else {
    // Manejar solicitudes no válidas
    $response['success'] = false;
    $response['error'] = 'Solicitud no válida. Asegúrate de enviar los datos correctos.';
}

// Enviar la respuesta como JSON
echo json_encode($response);
