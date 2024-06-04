<?php
session_start();

header('Content-Type: application/json');

require $_SERVER['DOCUMENT_ROOT'] . '/Galeria5-AJAX/database/database.php';

$response = array();  // Array para almacenar la respuesta

try {
    $database = new Database();
    $conn = $database->getConnection();

    if (isset($_POST['albumIDs']) && is_array($_POST['albumIDs'])) {
        $albumIDs = $_POST['albumIDs'];

        $conn->beginTransaction();

        try {
            foreach ($albumIDs as $albumID) {
                $albumID = intval($albumID);

                $stmt = $conn->prepare("SELECT imagen FROM imagenes_albumes WHERE id_album = :albumID");
                $stmt->bindParam(':albumID', $albumID, PDO::PARAM_INT);
                $stmt->execute();
                $imagesString = $stmt->fetchColumn();

                $images = explode(",", $imagesString);

                foreach ($images as $image) {
                    $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/Galeria5-AJAX/Albums/fotos/' . trim($image);

                    if (!empty(trim($image)) && file_exists($imagePath) && !is_dir($imagePath)) {
                        if (!unlink($imagePath)) {
                            throw new Exception("Error al eliminar la imagen: " . $imagePath);
                        }
                    }
                }

                $stmt = $conn->prepare("DELETE FROM imagenes_albumes WHERE id_album = :albumID");
                $stmt->bindParam(':albumID', $albumID, PDO::PARAM_INT);
                $stmt->execute();

                $stmt = $conn->prepare("DELETE FROM albumes WHERE id_album = :albumID");
                $stmt->bindParam(':albumID', $albumID, PDO::PARAM_INT);
                $stmt->execute();
            }

            $conn->commit();

            $response['success'] = true;
        } catch (Exception $e) {
            $conn->rollBack();
            $response['error'] = "Error al eliminar el álbum: " . $e->getMessage();
        }
    } else {
        $response['error'] = "No se proporcionó un ID de álbum para eliminar";
    }
} catch (PDOException $e) {
    $response['error'] = "Error en la conexión a la base de datos: " . $e->getMessage();
}

echo json_encode($response);

