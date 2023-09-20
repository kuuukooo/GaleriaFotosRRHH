<?php
session_start();

require "./conexion_bd.php";

if (!empty($_POST["btningresar"])) {
    if (empty($_POST["usuario"]) || empty($_POST["password"])) {
        $_SESSION["mensaje"] = 'Complete los campos';
    } else {
        $usuario = $_POST['usuario'];
        $clave = $_POST['password'];
        
        // Realizar la consulta a la base de datos para autenticar al usuario
        $sql = $conex->prepare("SELECT id_usuario FROM usuarios WHERE usuario=? AND contrasena=?");
        $sql->bind_param("ss", $usuario, $clave);
        $sql->execute();
        $sql->bind_result($user_id);

        if ($sql->fetch()) {
            // Autenticación exitosa
            $_SESSION["user_id"] = $user_id; // Almacena el ID del usuario en la sesión
            header("location:index.php");
            exit();
        } else {
            $_SESSION["mensaje"] = 'Acceso Denegado';
        }
    }
    header("location:login.php");
    exit();
}
?>
