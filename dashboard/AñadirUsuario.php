<?php
// Tu conexión a la base de datos
require "../database/database.php";

$database = new Database();
$conn = $database->getConnection();

// Verificar la conexión a la base de datos
if ($conn) {
    // Obtener los datos del formulario
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $tipousuario = $_POST['tipousuario'];

    // Validar el campo TipoUsuario
    $tiposPermitidos = ['Admin', 'Usuario'];

    if (!in_array($tipousuario, $tiposPermitidos)) {
        // Tipo de usuario no válido
        $response = array('success' => false, 'message' => "Por favor, introduzca si es 'Admin' o 'Usuario'.");
    } elseif (strlen($usuario) < 3) {
        // Usuario debe tener al menos 3 caracteres
        $response = array('success' => false, 'message' => 'El usuario debe tener al menos 3 caracteres.');
    } elseif (!is_numeric($telefono)) {
        // Teléfono no es un número
        $response = array('success' => false, 'message' => 'Por favor, ingrese un número válido en el campo de teléfono.');
    } else {
        // Encriptar la contraseña
        $hashed_password = password_hash($contrasena, PASSWORD_BCRYPT);

        // Preparar la consulta para insertar el nuevo usuario
        $query = "INSERT INTO usuarios (Usuario, contrasena, correo, numerotel, tipo_usuario) VALUES (:usuario, :contrasena, :correo, :telefono, :tipousuario)";
        $stmt = $conn->prepare($query);

        // Asociar los parámetros
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':contrasena', $hashed_password);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':tipousuario', $tipousuario);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Éxito: Devolver una respuesta en formato JSON
            $response = array('success' => true, 'message' => 'Usuario añadido correctamente.');
        } else {
            // Error: Devolver una respuesta en formato JSON
            $response = array('success' => false, 'message' => 'Error al añadir usuario.');
        }
    }
} else {
    // Error de conexión a la base de datos
    $response = array('success' => false, 'message' => 'Error en la conexión a la base de datos.');
}

// Devolver la respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);