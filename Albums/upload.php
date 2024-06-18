<?php
// Iniciar la sesión para manejar variables de sesión
session_start();

// Incluir el archivo de configuración de la base de datos
require $_SERVER['DOCUMENT_ROOT'] . '/Galeria5-AJAX/database/database.php';

// Configurar la zona horaria
date_default_timezone_set('America/Asuncion');

// Array para almacenar la respuesta que se enviará como JSON
$response = array();

// Número máximo de imágenes permitidas
$maxImages = 25;

// Verificar si se ha enviado algún archivo
if (!isset($_FILES['files']) || !is_array($_FILES['files']['name']) || empty($_FILES['files']['name'][0])) {
    $response['error'] = "Por favor selecciona una imagen para enviar.";
    header('Content-Type: application/json');
    echo json_encode($response);
    exit; // Detener la ejecución del código
}

// Contar la cantidad de archivos antes de procesarlos
$countfiles = count($_FILES['files']['name']);

// Verificar si se excede el límite de imágenes permitidas
if ($countfiles > $maxImages) {
    $response['error'] = "Por favor selecciona hasta $maxImages imágenes.";
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

    // Mensaje de respuesta para confirmar los datos recibidos del formulario
    $response_message = "Datos recibidos del formulario:";
    $response_message .= " Descripción: " . $_POST['description'] . "";
    $response_message .= " Archivos:";

    // Obtener la descripción del formulario y el ID de usuario de la sesión
    $description = $_POST['description'];
    $userId = $_SESSION['user_id'];

    try {
        // Establecer una conexión con la base de datos
        $database = new Database();
        $conn = $database->getConnection();

        // Array para almacenar los nombres de archivo de las imágenes
        $images = array();
        $response_message .= " Cantidad de archivos: " . $countfiles . "";

        // Iterar a través de cada archivo enviado
        for ($i = 0; $i < $countfiles; $i++) {
            $fileTmpPath = $_FILES['files']['tmp_name'][$i];
            $fileName = $_FILES['files']['name'][$i];
            $fileType = $_FILES['files']['type'][$i];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $image = $newFileName;

            // Extensiones de archivo permitidas
            $allowedFileExtensions = array('png', 'jpg', 'jpeg', 'gif');

            // Verificar si la extensión del archivo es válida
            if (in_array($fileExtension, $allowedFileExtensions)) {
                $uploadFileDir = './fotos/';
                $dest_path = $uploadFileDir . $newFileName;
                $imageType = exif_imagetype($fileTmpPath);

                // Verificar si el archivo es una imagen válida
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

                        // Comprimir y guardar la imagen JPEG con calidad reducida
                        if ($originalImage !== false && imagejpeg($originalImage, $dest_path, $calidad)) {
                            array_push($images, $image);
                        }
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

        // Si no hay errores en el procesamiento de archivos
        if (!isset($response['error'])) {
            // Convertir el array de nombres de archivo en una cadena separada por comas
            $imagesList = implode(",", $images);
            $date = date('Y-m-d H:i:s');
            
            // Insertar información del álbum en la tabla 'albumes'
            $query = "INSERT INTO albumes (imagen, descripcion, fecha_creacion, id_usuario) VALUES ('$imagesList', '$description', :fecha_creacion, :id_usuario)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id_usuario', $userId);
            $stmt->bindParam(':fecha_creacion', $date);

            // Ejecutar la consulta para insertar datos del álbum
            if ($stmt->execute()) {
                $albumId = $conn->lastInsertId();
                
                // Insertar información de las imágenes en la tabla 'imagenes_albumes'
                $queryImagenes = "INSERT INTO imagenes_albumes (id_usuario, id_album, imagen, descripcion, fecha_carga) VALUES (:id_usuario, :id_album, :imagen, :descripcion, :fecha_carga)";
                $stmtImagenes = $conn->prepare($queryImagenes);
                $stmtImagenes->bindParam(':id_usuario', $userId);
                $stmtImagenes->bindParam(':id_album', $albumId);
                $stmtImagenes->bindParam(':imagen', $imagesList);
                $stmtImagenes->bindParam(':descripcion', $description);
                $stmtImagenes->bindParam(':fecha_carga', $date);

                // Ejecutar la consulta para insertar datos de las imágenes
                if ($stmtImagenes->execute()) {
                    $response['success'] = "Álbum e imágenes creados correctamente";
                } else {
                    $response['error'] = "Error insertando datos en la tabla imagenes_albumes: " . $stmtImagenes->errorInfo()[2];
                }
            } else {
                $response['error'] = "Error insertando datos en la tabla albumes: " . $stmt->errorInfo()[2];
            }
        }
    } catch (PDOException $e) {
        // Capturar errores de conexión a la base de datos
        $response['error'] = "Error en la conexión a la base de datos: " . $e->getMessage();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit; // Detener la ejecución del código
    }
}

// Agregar un mensaje de respuesta al registro de mensajes
$response['message'] = $response_message;

// Enviar la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);