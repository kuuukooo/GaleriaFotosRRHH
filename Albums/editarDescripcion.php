<?php
session_start();

header('Content-Type: application/json');

require $_SERVER['DOCUMENT_ROOT'] . '/Galeria5-AJAX/database/database.php';

try {
    // Crear una instancia de la clase Database para obtener la conexión PDO
    $database = new Database();
    $conn = $database->getConnection();

    // Verificar si se enviaron los datos necesarios para actualizar la descripción del álbum
    if (isset($_POST['albumId'], $_POST['newDescription'])) {
        $albumId = $_POST['albumId'];
        $newDescription = $_POST['newDescription'];

        // Comenzar una transacción
        $conn->beginTransaction();

        try {
            // Preparar la consulta SQL para actualizar la descripción del álbum
            $sql = "UPDATE albumes SET descripcion = :newDescription WHERE id_album = :albumId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':newDescription', $newDescription, PDO::PARAM_STR);
            $stmt->bindParam(':albumId', $albumId, PDO::PARAM_INT);
            $stmt->execute();

            // Confirmar la transacción
            $conn->commit();

            echo json_encode(array("success" => true));
        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $conn->rollBack();

            echo json_encode(array("error" => "Error al actualizar la descripción del álbum: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("error" => "Parámetros incompletos para actualizar la descripción del álbum"));
    }
} catch (PDOException $e) {
    // Capturar cualquier excepción relacionada con la conexión a la base de datos
    echo json_encode(array("error" => "Error en la conexión a la base de datos: " . $e->getMessage()));
}