<?php
// Directorio donde se guardan las imágenes cargadas
$uploadDirectory = 'Albums/fotos/';

// Escanear el directorio de carga y obtener las URL de las imágenes
$images = glob($uploadDirectory . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

// Devolver las URL de las imágenes como una lista JSON
echo json_encode($images);
?>
