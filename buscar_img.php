<?php
require "./database/database.php";

try {
    // Crea una instancia de la clase Database
    $database = new Database();

    // Obtiene la conexión
    $conn = $database->getConnection();

    // Obtener el término de búsqueda enviado desde el formulario
    if (isset($_POST['search'])) {
        $searchTerm = $_POST['search'];

        // Consulta SQL para buscar una imagen por descripción
        $sql = "SELECT id_imagen, descripcion FROM imagenes_sueltas WHERE descripcion LIKE '%" . $searchTerm . "%'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Obtener los resultados de la consulta
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($result)) {
            // Si se encontró una imagen que coincide con la búsqueda, enviar los datos de esa imagen como respuesta JSON
            $response = array(
                'id_imagen' => $result[0]['id_imagen'],
                'descripcion' => $result[0]['descripcion'],
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
} catch (PDOException $e) {
    // Manejo de errores en la conexión a la base de datos
    echo json_encode(array('error' => 'Error en la conexión a la base de datos: ' . $e->getMessage()));
}
?>
