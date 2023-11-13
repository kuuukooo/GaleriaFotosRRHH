<?php

require "./database/database.php";

// Crea una instancia de la clase Database
$database = new Database();

// Obtiene la conexión
$conn = $database->getConnection();

$posts = array();
$stmt = $conn->prepare("SELECT * FROM imagenes_sueltas");
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    array_push($posts, $row);
}
?>
