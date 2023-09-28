<?php
session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/Galeria5-AJAX/database/database.php';

$image = "";
$description = "";
$date = date('Y-m-d H:i:s');

if(isset($_POST['description'])){
    $description = $_POST['description'];
}

// Verificar si se han subido archivos
if(isset($_FILES['files'])){
    // Contamos la cantidad de imágenes que queremos publicar:
    $countfiles = count($_FILES['files']['name']);
    
    // Array para guardar los nombres de las imágenes en la base de datos:
    $images = array();

    if($countfiles > 0){
        for($i = 0; $i < $countfiles; $i++){
            $fileTmpPath = $_FILES['files']['tmp_name'][$i];
            $fileName = $_FILES['files']['name'][$i];
            $fileType = $_FILES['files']['type'][$i];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $image = $newFileName;

            $allowedFileExtensions = array('png', 'jpg', 'jpeg');

            if(in_array($fileExtension, $allowedFileExtensions)){
                // Directorio donde guardamos la imagen
                $uploadFileDir = '../assets/images/posts/';
                $dest_path = $uploadFileDir . $newFileName;

                // Comprimimos la imagen
                $calidad = 40;
                $originalImage = "";
                if($fileExtension == 'png'){
                    $originalImage = imagecreatefrompng($fileTmpPath);
                }else{
                    $originalImage = imagecreatefromjpeg($fileTmpPath);
                }

                if($originalImage !== false && imagejpeg($originalImage, $dest_path, $calidad)){
                    array_push($images, $image);
                }
            }
        }

        $imagesList = implode(",", $images);

        $sql = 'INSERT INTO imagenes_sueltas (imagen, descripcion, fecha_carga) VALUES (:imagen, :descripcion, :fecha_carga)';
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':imagen', $imagesList);
        $stmt->bindParam(':descripcion', $description);
        $stmt->bindParam(':fecha_carga', $date);

        if($stmt->execute()){
            $respuesta = "Post publicado correctamente";
            $_SESSION['success'] = $respuesta;
            header('location: ../index2.php');
        }else{
            $respuesta = "No ha sido posible publicar el post";
            $_SESSION['error'] = $respuesta;
            header('location: ../index2.php');
        }
    }
}
?>

}
?>
