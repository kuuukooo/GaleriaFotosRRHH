<?php
session_start();

require "../database/database.php";

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

            // Realizar la consulta a la base de datos para autenticar al usuario y obtener el tipo de usuario
            $sql = $conex->prepare("SELECT id_usuario, tipo_usuario, usuario FROM usuarios WHERE usuario=? AND contrasena=?");
            $sql->bindParam(1, $usuario, PDO::PARAM_STR);
            $sql->bindParam(2, $clave, PDO::PARAM_STR);
            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Autenticación exitosa
                $_SESSION["user_id"] = $result["id_usuario"]; // Almacena el ID del usuario en la sesión

                $tipo_usuario = $result["tipo_usuario"];
                $_SESSION["tipo_usuario"] = $tipo_usuario;

                // Almacena el tipo de usuario en una cookie
                setcookie("tipo_usuario", $tipo_usuario, time() + (86400 * 30), "/"); // Caduca en 30 días

                // Crea una cookie llamada "id_usuario" con el valor correspondiente a la columna "Usuario"
                setcookie("id_usuario", $result["usuario"], time() + (86400 * 30), "/");

                switch ($tipo_usuario) {
                    case "Admin":
                        header("Location: ../index2.php");
                        break;
                    case "Usuario":
                        header("Location: ../index2.php");
                        break;
                    default:
                        header("Location: ../index2.php");
                        break;
                }
            } else {
                $_SESSION["mensaje"] = 'Acceso Denegado';
                header("location: login.php");
            }
        } catch (PDOException $e) {
            $_SESSION["mensaje"] = 'Error en la conexión a la base de datos';
            // Manejo del error (puedes redirigir a una página de error o hacer lo que consideres apropiado)
            header("location: login.php");
        }
    }
}
?>
