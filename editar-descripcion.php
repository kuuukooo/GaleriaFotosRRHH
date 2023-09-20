<?php
session_start();
require "./database/database.php"; // Asegúrate de incluir tu archivo de conexión a la base de datos

if (isset($_POST['new-description']) && isset($_POST['id_imagen'])) {
    $newDescription = $_POST['new-description'];
    $imageId = $_POST['id_imagen'];

    try {
        // Actualiza la descripción en la base de datos utilizando PDO
        $updateQuery = "UPDATE imagenes_sueltas SET descripcion = :newDescription WHERE id_imagen = :imageId";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':newDescription', $newDescription, PDO::PARAM_STR);
        $stmt->bindParam(':imageId', $imageId, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['success'] = "Descripción actualizada con éxito.";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al actualizar la descripción: " . $e->getMessage();
    }

    header("Location: index.php?pagina=" . $_POST['pagina_actual']);
} else {
    $_SESSION['error'] = "Falta información para editar la descripción.";
    header("Location: index.php"); // Redirige de vuelta a la página principal
    exit();
}
?>
