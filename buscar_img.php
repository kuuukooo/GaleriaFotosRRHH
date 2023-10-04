<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'galeria';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión a la base de datos fallida: " . $conn->connect_error);
}

// Obtener el término de búsqueda enviado desde el formulario
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];

    // Consulta SQL para buscar una imagen por descripción
    $sql = "SELECT id_imagen, descripcion FROM imagenes_sueltas WHERE descripcion LIKE '%" . $searchTerm . "%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Si se encontró una imagen que coincide con la búsqueda, enviar los datos de esa imagen como respuesta JSON
        $row = $result->fetch_assoc();
        $response = array(
            'id_imagen' => $row['id_imagen'],
            'descripcion' => $row['descripcion'],
        );
        echo json_encode($response);
    } else {
        // Si no se encontraron resultados
        echo json_encode(array('error' => 'No se encontraron imágenes que coincidan con la búsqueda.'));
    }
} else {
    // Si no se envió un término de búsqueda válido
    echo json_encode(array('error' => 'Por favor, ingrese un término de búsqueda válido.'));
}

// Cerrar la conexión a la base de datos
$conn->close();
?>