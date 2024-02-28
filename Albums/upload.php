<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/Galeria5-AJAX/database/database.php';

date_default_timezone_set('America/Asuncion');

$image = "";
$description = "";
$date = date('Y-m-d H:i:s');
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['description']) && isset($_FILES['files'])) {
    $response_message = "Datos recibidos del formulario:";
    $response_message .= "Descripción: " . $_POST['description'] . "";
    $response_message .= "Archivos:";
    
    $description = $_POST['description'];
    $userId = $_SESSION['user_id'];

    try {
        $database = new Database();
        $conn = $database->getConnection();

        if (isset($_FILES['files'])) {
            $countfiles = count($_FILES['files']['name']);
            $images = array();

            if ($countfiles > 0) {
                for ($i = 0; $i < $countfiles; $i++) {
                    $fileTmpPath = $_FILES['files']['tmp_name'][$i];
                    $fileName = $_FILES['files']['name'][$i];
                    $fileType = $_FILES['files']['type'][$i];
                    $fileNameCmps = explode(".", $fileName);
                    $fileExtension = strtolower(end($fileNameCmps));
                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                    $image = $newFileName;

                    $allowedFileExtensions = array('png', 'jpg', 'jpeg');

                    if (in_array($fileExtension, $allowedFileExtensions)) {
                        $uploadFileDir = './fotos/';
                        $dest_path = $uploadFileDir . $newFileName;
                        $imageType = exif_imagetype($fileTmpPath);

                        if ($imageType !== false && ($imageType == IMAGETYPE_JPEG || $imageType == IMAGETYPE_PNG)) {
                            $calidad = 40;
                            $originalImage = "";

                            if ($imageType == IMAGETYPE_PNG) {
                                $originalImage = imagecreatefrompng($fileTmpPath);
                            } else {
                                $originalImage = imagecreatefromjpeg($fileTmpPath);
                            }

                            if ($originalImage !== false && imagejpeg($originalImage, $dest_path, $calidad)) {
                                array_push($images, $image);
                            }
                        } else {
                            $response['error'] = "El archivo $fileName no es una imagen válida";
                            break;
                        }
                    } else {
                        $response['error'] = "El archivo $fileName no es válido";
                        break;
                    }
                }

                $maxImages = 50;

                if (count($images) > $maxImages) {
                    $response['error'] = "Por favor, seleccione hasta $maxImages imágenes.";
                } else {
                    if (!isset($response['error'])) {
                        $imagesList = implode(",", $images);
                        $query = "INSERT INTO albumes (imagen, descripcion, fecha_creacion, id_usuario) VALUES ('$imagesList', '$description', :fecha_creacion, :id_usuario)";
                        $stmt = $conn->prepare($query);
                        $stmt->bindParam(':id_usuario', $userId);
                        $stmt->bindParam(':fecha_creacion', $date);

                        if ($stmt->execute()) {
                            $albumId = $conn->lastInsertId();
                            $queryImagenes = "INSERT INTO imagenes_albumes (id_usuario, id_album, imagen, descripcion, fecha_carga) VALUES (:id_usuario, :id_album, :imagen, :descripcion, :fecha_carga)";
                            $stmtImagenes = $conn->prepare($queryImagenes);
                            $stmtImagenes->bindParam(':id_usuario', $userId);
                            $stmtImagenes->bindParam(':id_album', $albumId);
                            $stmtImagenes->bindParam(':imagen', $imagesList);
                            $stmtImagenes->bindParam(':descripcion', $description);
                            $stmtImagenes->bindParam(':fecha_carga', $date);

                            if ($stmtImagenes->execute()) {
                                $response['success'] = "Álbum y imágenes subidos correctamente";
                            } else {
                                $response['error'] = "Error al insertar en la tabla imagenes_albumes: " . $stmtImagenes->errorInfo()[2];
                            }
                        } else {
                            $response['error'] = "Error al insertar en la tabla albumes: " . $stmt->errorInfo()[2];
                        }
                    }
                }
            }
        }
    } catch (PDOException $e) {
        $response['error'] = "Error en la conexión a la base de datos: " . $e->getMessage();
    }
} else {
    $response['error'] = "Faltan datos del formulario";
}

$response['message'] = $response_message;
header('Content-Type: application/json');
echo json_encode($response);