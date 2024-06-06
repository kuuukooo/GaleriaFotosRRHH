<?php
require "../database/database.php";

// Crea una instancia de la clase Database
$database = new Database();

// Obtiene la conexión
$conn = $database->getConnection();

// Obtiene los IDs de las imágenes seleccionadas desde la solicitud AJAX
$selectedImages = isset($_POST['selectedImages']) ? $_POST['selectedImages'] : [];

if (empty($selectedImages)) {
    echo json_encode(['error' => 'No se han seleccionado imágenes.']);
    exit;
}

// Convierte los IDs a una cadena separada por comas para la consulta SQL
$ids = implode(',', array_map('intval', $selectedImages));

// Realiza la consulta para obtener los detalles de las imágenes seleccionadas
$query = "SELECT id_imagen, descripcion, imagen FROM imagenes_sueltas WHERE id_imagen IN ($ids)";
$stmt = $conn->prepare($query);
$stmt->execute();

// Crear un array para almacenar los detalles de las imágenes
$imagenes = array();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $images = explode(',', $row['imagen']);
    foreach ($images as $image) {
        $imagenes[] = [
            'id_imagen' => $row['id_imagen'],
            'descripcion' => $row['descripcion'],
            'imagen' => trim($image)
        ];
    }
}

// Retorna los detalles de las imágenes en formato JSON
echo json_encode($imagenes);