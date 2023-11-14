<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/Galeria5-AJAX/database/database.php';

$image = "";
$description = "";
$date = date('Y-m-d H:i:s');
$response = array();

if (isset($_POST['description'])) {
    $description = $_POST['description'];
}

try {
    // Crea una instancia de la clase Database
    $database = new Database();

    // Obtiene la conexión
    $conn = $database->getConnection();

    // Verificar si se han subido archivos
    if (isset($_FILES['files'])) {
        // Contamos la cantidad de imágenes que queremos publicar:
        $countfiles = count($_FILES['files']['name']);

        // Array para guardar los nombres de las imágenes en la base de datos:
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
                    // Directorio donde guardamos la imagen
                    $uploadFileDir = '../assets/images/posts/';
                    $dest_path = $uploadFileDir . $newFileName;

                    // Comprobamos si el archivo es una imagen válida
                    $imageType = exif_imagetype($fileTmpPath);

                    if ($imageType !== false && ($imageType == IMAGETYPE_JPEG || $imageType == IMAGETYPE_PNG)) {
                        // Comprimimos la imagen
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
                    $response['error'] = "El archivo $fileName no es una imagen válida";
                    break;
                }
            }

            if (!isset($response['error'])) {
                $imagesList = implode(",", $images);

                if ($description != '') {
                    // Aquí puedes insertar los datos en la base de datos
                    $sql = 'INSERT INTO imagenes_sueltas (imagen, descripcion, fecha_carga) VALUES (:imagen, :descripcion, :fecha_carga)';
                    $stmt = $conn->prepare($sql);

                    $stmt->bindParam(':imagen', $imagesList);
                    $stmt->bindParam(':descripcion', $description);
                    $stmt->bindParam(':fecha_carga', $date);

                    if ($stmt->execute()) {
                        $response['success'] = "Post publicado correctamente";
                    } else {
                        $response['error'] = "No ha sido posible publicar el post";
                    }
                } else {
                    // La descripción está vacía, por lo que no se pueden insertar los datos
                    $response['error'] = "La descripción es obligatoria";
                }
            }
        }
    }
} catch (PDOException $e) {
    $response['error'] = "Error en la conexión a la base de datos: " . $e->getMessage();
}

// Devolver la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
