<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/Galeria5-AJAX/database/database.php';

date_default_timezone_set('America/Asuncion');

$response = array();
$maxImages = 50;

// Verificar si se ha enviado algún archivo
if (!isset($_FILES['files']) || !is_array($_FILES['files']['name']) || empty($_FILES['files']['name'][0])) {
    $response['error'] = "Por favor selecciona una imágen para mandar.";
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Detener la ejecución del código
}

$countfiles = count($_FILES['files']['name']); // Contar la cantidad de archivos antes de procesarlos

if ($countfiles > $maxImages) {
    $response['error'] = "Por favor selecciona hasta $maxImages imagenes.";
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
} else {
    // Verificar si todos los datos del formulario se han enviado
    if ($_SERVER["REQUEST_METHOD"] != "POST" || empty($_POST['description'])) {
        $response['error'] = "No hay descripción en el formulario.";
        header('Content-Type: application/json');
        echo json_encode($response);
        exit; 
    }

    $response_message = "Datos recibidos del formulario:";
    $response_message .= "Descripcion: " . $_POST['description'] . "";
    $response_message .= "Archivos:";

    $description = $_POST['description'];
    $userId = $_SESSION['user_id'];

    try {
        $database = new Database();
        $conn = $database->getConnection();

        $images = array();
        $response_message .= "Cantidad de archivos: " . $countfiles . "";

        for ($i = 0; $i < $countfiles; $i++) {
            $fileTmpPath = $_FILES['files']['tmp_name'][$i];
            $fileName = $_FILES['files']['name'][$i];
            $fileType = $_FILES['files']['type'][$i];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $image = $newFileName;

            $allowedFileExtensions = array('png', 'jpg', 'jpeg', 'gif');

            if (in_array($fileExtension, $allowedFileExtensions)) {
                $uploadFileDir = './fotos/';
                $dest_path = $uploadFileDir . $newFileName;
                $imageType = exif_imagetype($fileTmpPath);

                if ($imageType !== false && ($imageType == IMAGETYPE_JPEG || $imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF)) {
                    if ($imageType == IMAGETYPE_GIF) {
                        move_uploaded_file($fileTmpPath, $dest_path); 
                        array_push($images, $image); 
                    } else {
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
                    }
                } else {
                    $response['error'] = "El archivo $fileName no es una imágen válida";
                    break;
                }
            } else {
                $response['error'] = "El archivo $fileName no es válido";
                break;
            }
        }

        if (!isset($response['error'])) {
            $imagesList = implode(",", $images);
            $date = date('Y-m-d H:i:s');
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
                    $response['success'] = "Album e imágenes creados correctamente";
                } else {
                    $response['error'] = "Error insertando datos en la tabla imagenes_albumes: " . $stmtImagenes->errorInfo()[2];
                }
            } else {
                $response['error'] = "Error insertando datos en la tabla albumes: " . $stmt->errorInfo()[2];
            }
        }
    } catch (PDOException $e) {
        $response['error'] = "Error en la conexión a la base de datos: " . $e->getMessage();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit; // Stop the execution of the code
    }
}

$response['message'] = $response_message;
header('Content-Type: application/json');
echo json_encode($response);
