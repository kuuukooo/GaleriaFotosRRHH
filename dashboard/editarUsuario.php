<?php
// Tu conexión a la base de datos
require "../database/database.php";

$database = new Database();
$conn = $database->getConnection();

if ($conn) {
    $id_usuario = $_POST['id_usuario'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $tipousuario = $_POST['tipousuario'];

    //Preparar la consulta para actualizar los cambios y editarlos
    $query = "UPDATE usuarios SET Usuario = :usuario, contrasena = :contrasena, tipo_usuario = :tipousuario WHERE id_usuario = :id_usuario";
    $stmt = $conn->prepare($query);

    //Asociar los parámetros
    $stmt->bindParam(':id_usuario', $id_usuario);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':contrasena', $contrasena);
    $stmt->bindParam(':tipousuario', $tipousuario);

    //Ejecutar la consulta
    if ($stmt->execute()) {
        //Éxito: Devolver una respuesta en formato JSON
        $response = array('success' => true, 'message' => 'Usuario editado correctamente.');
    } else {
        //Error: Devolver una respuesta en formato JSON
        $response = array('success' => false, 'message' => 'Error al editar usuario.');
    }
} else {
    //Error de conexión a la base de datos
    $response = array('success' => false, 'message' => 'Error en la conexión a la base de datos.');
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>