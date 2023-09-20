<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald&family=Press+Start+2P&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9a726f2fb1.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="login.css">
    <title>Inicio de sesión</title>
</head>

<body>
    <?php
    include("conexion_bd.php");
    include("controlador.php");
    ?>        
<div class="Switch">      
    <div class="position-absolute top-0 end-0">
            <div class="form-check form-switch mt-3 me-3">
            <label class="form-check-label ms-3" for="lightSwitch">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-brightness-high" viewBox="0 0 16 16">
                <path d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
            </svg>
            </label>
            <input class="form-check-input" type="checkbox" id="lightSwitch"/>
        </div>
        <script src="toggle.js"></script>
    </div> 
</div>
    <div class="login-content">
        <div class="content">
            <form method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>">  
                <a href='https://postimg.cc/Wh7nVG7P' target='_blank'><img src='https://i.postimg.cc/Wh7nVG7P/vierci-removebg-preview.png' border='0' class="avatar" alt='vierci-removebg-preview'/></a>
                <h1 class="title">BIENVENIDO</h1>
                <?php
                    if (isset($_SESSION["mensaje"])) {
                        echo '<div class="mensaje">' . $_SESSION["mensaje"] . '</div>';
                        unset($_SESSION["mensaje"]); 
                    }
                    ?>
                <div class="Box">
                    <i class="fa fa-user" aria-hidden="true"></i>
                    <input class="input100" type="text" name="usuario" placeholder="Username">
                </div>
                <div class="Box">
                    <i class="fa-solid fa-lock"></i>
                    <input class="input100" type="password" name="password" placeholder="Password">
                </div>
                <div class="container-login100-form-btn">
                    <input name="btningresar" class="login100-form-btn"  type="submit" value="Iniciar sesión">
                </div>
            </form>
        </div>
    </div>
</body>
</html>