<?php
session_start();

require "../database/database.php";

// Incluye la función de autenticación LDAP
function login($user, $pass){
    $DOMINIO = 'ajvierci.com.py';
    //$DOMINIO = 'AJVOLAP';

    $ldaprdn = trim($user).'@'.$DOMINIO; 
    $ldappass = trim($pass); 
    $ds = $DOMINIO; 

    $puertoldap = 389; 
    $ldapconn = ldap_connect($ds, $puertoldap);
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3); 
    ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0); 
    $ldapbind = @ldap_bind($ldapconn, $ldaprdn, $ldappass); 

    ldap_close($ldapconn); 

    return $ldapbind; //$array;
}

if (!empty($_POST["btningresar"])) {
    if (empty($_POST["usuario"]) || empty($_POST["password"])) {
        $_SESSION["mensaje"] = 'Complete los campos';
        header("location: login.php");
        exit();
    } else {
        $usuario = $_POST['usuario'];
        $clave = $_POST['password'];

        // Intentar autenticación LDAP
        if (login($usuario, $clave)) {
            // Autenticación LDAP exitosa
            $_SESSION["user_id"] = $usuario; // Almacena el usuario en la sesión

            $tipo_usuario = "Usuario";
            $_SESSION["tipo_usuario"] = $tipo_usuario;

            // Almacena el tipo de usuario en una cookie
            setcookie("tipo_usuario", $tipo_usuario, time() + (86400 * 30), "/"); // Caduca en 30 días

            // Crea una cookie llamada "id_usuario" con el valor del usuario
            setcookie("id_usuario", $usuario, time() + (86400 * 30), "/");

            header("Location: ../index2.php");
        } else {
            // Autenticación LDAP fallida, intentar con la base de datos
            try {
                // Crea una instancia de la clase Database
                $database = new Database();

                // Obtiene la conexión
                $conex = $database->getConnection();

                // Realizar la consulta a la base de datos para obtener la contraseña encriptada
                $sql = $conex->prepare("SELECT id_usuario, tipo_usuario, usuario, contrasena FROM usuarios WHERE usuario = ?");
                $sql->bindParam(1, $usuario, PDO::PARAM_STR);
                $sql->execute();
                $result = $sql->fetch(PDO::FETCH_ASSOC);

                if ($result && password_verify($clave, $result["contrasena"])) {
                    // Autenticación exitosa en la base de datos
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
}
?>
