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
        
            // Realiza una consulta adicional para obtener el tipo de usuario
            $sql->close(); // Cierra la consulta anterior
            $sql = $conex->prepare("SELECT tipo_usuario FROM usuarios WHERE id_usuario=?");
            $sql->bind_param("i", $user_id);
            $sql->execute();
            $sql->bind_result($tipo_usuario);
        
            if ($sql->fetch()) {
                $_SESSION["tipo_usuario"] = $tipo_usuario;
        
                // Almacena el tipo de usuario en una cookie
                setcookie("tipo_usuario", $tipo_usuario, time() + (86400 * 30), "/"); // Caduca en 30 días
        
                switch ($tipo_usuario) {
                    case "Admin":
                        header("Location: index2.php");
                        break;
                    case "Usuario":
                        header("Location: index2.php");
                        break;
                    default:
                        header("Location: index2.php"); 
                        break;
                }
            }
        } else {
            $_SESSION["mensaje"] = 'Acceso Denegado';
            header("location:login.php");
        }          
    }
}?>