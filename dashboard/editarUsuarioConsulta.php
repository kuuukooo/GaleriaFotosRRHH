<?php
// Tu conexión a la base de datos
require "../database/database.php";

$database = new Database();
$conn = $database->getConnection();

if ($conn) {
    $id_usuario = $_GET['id_usuario'];

    //Preparar la consulta para obtener los datos del usuario
    $query = "SELECT Usuario, contrasena, correo, numerotel, tipo_usuario FROM usuarios WHERE id_usuario = :id_usuario";
    $stmt = $conn->prepare($query);

    //Asociar el parámetro
    $stmt->bindParam(':id_usuario', $id_usuario);

    //Ejecutar la consulta
    if ($stmt->execute()) {
        //Obtener los resultados
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        //Éxito: Devolver los datos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($userData);
    } else {
        //Error: Devolver una respuesta en formato JSON
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Error al obtener datos de usuario.'));
    }
} else {
    //Error de conexión a la base de datos
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Error en la conexión a la base de datos.'));
}
?>
