<?php
session_start();

require "./database/database.php";

if (!empty($_POST["btningresar"])) {
    if (empty($_POST["usuario"]) || empty($_POST["password"])) {
        $_SESSION["mensaje"] = 'Complete los campos';
    } else {
        $usuario = $_POST['usuario'];
        $clave = $_POST['password'];
        
        try {
            // Crea una instancia de la clase Database
            $database = new Database();

            // Obtiene la conexión
            $conex = $database->getConnection();

            // Realizar la consulta a la base de datos para autenticar al usuario
            $sql = $conex->prepare("SELECT id_usuario FROM usuarios WHERE usuario=? AND contrasena=?");
            $sql->bindParam(1, $usuario, PDO::PARAM_STR);
            $sql->bindParam(2, $clave, PDO::PARAM_STR);
            $sql->execute();
            $user_id = $sql->fetchColumn();

            if ($user_id) {
                // Autenticación exitosa
                $_SESSION["user_id"] = $user_id; // Almacena el ID del usuario en la sesión
            
                // Realiza una consulta adicional para obtener el tipo de usuario
                $sql = $conex->prepare("SELECT tipo_usuario FROM usuarios WHERE id_usuario=?");
                $sql->bindParam(1, $user_id, PDO::PARAM_INT);
                $sql->execute();
                $tipo_usuario = $sql->fetchColumn();
            
                if ($tipo_usuario) {
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
        } catch (PDOException $e) {
            $_SESSION["mensaje"] = 'Error en la conexión a la base de datos';
            // Manejo del error (puedes redirigir a una página de error o hacer lo que consideres apropiado)
            header("location:login.php");
        }
    }
}
?>
