<?php
ini_set ('display_errors', 1);
ini_set ('display_startup_errors', 1);
error_reporting (E_ALL);
// Verificar si se han enviado nombres de archivo
if(isset($_POST['image_names']) && is_array($_POST['image_names'])) {
    $imageNames = $_POST['image_names'];

    // Crear un archivo ZIP
    $zip = new ZipArchive();
    $zipFileName = __DIR__ . '/images.zip';

    if($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        foreach($imageNames as $imageName) {
            // Agregar cada imagen al archivo ZIP
            $imagePath = './assets/images/posts/' . $imageName;
            $zip->addFile($imagePath, $imageName);
        }

        // Cerrar el archivo ZIP
        $zip->close();

        // Enviar el archivo ZIP al navegador para su descarga
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
        header('Content-Length: ' . filesize($zipFileName));

        readfile($zipFileName);

        // Eliminar el archivo ZIP después de enviarlo
        unlink($zipFileName);

        exit;
    } else {
        echo 'No se pudo crear el archivo ZIP.';
    }
} else {
    echo 'No se proporcionaron nombres de archivo.';
}
?>
