<?php
// Tu conexión a la base de datos
require "../database/database.php";

$database = new Database();
$conn = $database->getConnection();

if ($conn) {
    // Realizar la consulta a la base de datos utilizando PDO
    $query = "SELECT id_usuario, Usuario, contrasena, tipo_usuario FROM usuarios";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Construir un array con los datos de los usuarios
    $data = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
} else {
    $data = array("error" => "Error en la conexión a la base de datos.");
}

// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
