<?php
session_start();

// Elimina todas las variables de sesión
session_unset();

// Destruye la sesión
session_destroy();

header('Location: login.php'); // Redirige al usuario a la página de inicio de sesión
exit();
?>
