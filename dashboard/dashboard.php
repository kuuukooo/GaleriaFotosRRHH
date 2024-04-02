<?php
require "../database/database.php";
// Instancia la clase Database
$database = new Database();

// Obtiene la conexión a la base de datos
$conn = $database->getConnection();

// Define la consulta SQL
$query = "SELECT * FROM usuarios";

// Ejecuta la consulta
$stmt = $conn->prepare($query);
$stmt->execute();

// Obtiene los resultados
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Bootstrap Prueba</title>
<link rel="icon" type="image/x-icon" href="../assets/logovierciblanco.svg">
<!-- Librerías -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<!-- CSS -->
<link rel="stylesheet" href="estilosdashboard.css">
<link rel="stylesheet" type="text/css" href="../NavBar/navbar2.css">
<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
	<nav class="sidebar close">
				<header>
					<div class="image-text">
						<span class="image">
							<img src="../assets/logovierciazul.svg" alt="LogoVierci" class="LogoVierciAzul">
							<img src="../assets/logovierciblanco.svg" alt="LogoVierciBlanco" class="LogoVierciBlanco" style="display: none;">
						</span>

						<div class="text logo-text">
							<span class="name">Galeria de Fotos</span>
							<span class="profession">A.J Vierci</span>
						</div>
					</div>

					<i class='bx bx-chevron-right toggle'></i>
				</header>

				<div class="menu-bar">
					<div class="menu">

					<!-- Iconos de la navbar -->

						<ul class="menu-links">
							<li class="nav-link">
								<a href="../index2.php">
									<i class='bx bx-home-alt icon'></i>
									<span class="text nav-text">Inicio</span>
								</a>
							</li>

							<li class="nav-link">
								<a href="../Albums/admin_albums.php">
									<i class='bx bx-photo-album icon'></i>
									<span class="text nav-text">Albumes</span>
								</a>
							</li>

							<li class="nav-link">
								<a href="dashboard.php">
									<i class="bi bi-menu-button-wide-fill icon"></i>
									<span class="text nav-text">Menú</span>
								</a>
							</li>
						</ul>
					</div>

					<!-- Parte de abajo de la navbar -->

					<div class="bottom-content">
						<li class="">
							<a href="../login/logout.php">
								<i class='bx bx-log-out icon'></i>
								<span class="text nav-text">Cerrar Sesión</span>
							</a>
						</li>

						<li class="mode">
							<div class="sun-moon">
								<i class='bx bx-moon icon moon'></i>
								<i class='bx bx-sun icon sun'></i>
							</div>
							<span class="mode-text text">Modo Oscuro</span>

							<div class="toggle-switch" id="darkModeSwitch">
								<span class="switch"></span>
							</div>

						</li>

					</div>
				</div>

			</nav>
	<!-- Tabla de Usuarios -->
	<div class="main-content">
		<div class="container-lg">
			<div class="table-responsive">
				<div class="table-wrapper">
					<div class="table-title">
						<div class="row">
							<div class="col-sm-6">
								<h2>Administrar <b>Usuarios</b></h2>
							</div>
							<div class="col-sm-6">
								<a href="#addEmployeeModal" class="btn btn-success" data-bs-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Añadir nuevo usuario</span></a>
								<a class="btn btn-danger" id="borrarVariosUsuarios"><i class="material-icons">&#xE15C;</i> <span>Eliminar</span></a>	
							</div>
						</div>
					</div>
					<table class="table table-striped table-hover" id="tablaUsuarios">
						
						<thead>
						<input class="form-control mb-2" id="tableSearch" type="text"
    					placeholder="Introduce un usuario..." maxlength='16'>
							<tr>
								<th>
									<span class="custom-checkbox">
										<input type="checkbox" id="selectAll">
										<label for="selectAll"></label>
									</span>
								</th>
								<th>ID</th>
								<th>Nombre</th>
								<th>Correo</th>
								<th>Teléfono</th>
								<th>TipoUsuario</th>
								<th>Acciones</th>
							</tr>
						</thead>
						<tbody id="myTable">				
						</tbody>
					</table>
					<p id="noResultsMessage" style="display: none;">No se ha encontrado nada.</p>
				</div>
			</div>        
		</div>
	<!-- Add Modal HTML -->
		<div id="addEmployeeModal" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<form id="addUserForm">
						<div class="modal-header">						
							<h4 class="modal-title">Añadir Usuario</h4>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">					
							<div class="form-group">
								<label>Usuario</label>
								<input type='text' minlength='4' maxlength='10' id='usuario' class='form-control' placeholder= 'Usuario...' required>
							</div>
							<div class="form-group">
								<label>Contraseña</label>
								<input type='password' minlength='8' maxlength='16' id='contrasena' class='form-control' placeholder='(8 carácteres y uno especial)' required>
								<i class='bi bi-eye-slash' id='togglePassword'></i>
							</div>
							<div class="form-group">
								<label>Correo</label>
								<input type='email' id='correo' class='form-control' placeholder='Ej: prueba@gmail.com' required >
							</div>
							<div class="form-group">
								<label>Teléfono</label>
								<input type='tel' minlength='10' maxlength='10' id='telefono' class='form-control' placeholder='Teléfono...' required>
							</div>	
							<div class="form-group">
								<label for='tipousuario' class='form-label'>TipoUsuario</label> 
                                <select id='tipousuario' class='form-select' required> 
                                	<option value='Admin'>Admin</option> 
                                	<option value='Usuario'>Usuario</option> 
                                </select> 
							</div>				
						</div>
						<div class="modal-footer">
							<div id="error-message-tipousuario" class="text-danger"></div>
							<input type="button" class="btn btn-danger" data-bs-dismiss="modal" value="Cancelar">
							<input type="submit" class="btn btn-success" value="Añadir" id="addUserBtn">
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- Aquí es donde se generan los modales de borrar y editar -->
	</div>
</div>
<script src="UsuariosDinámicos.js"></script>
<script src="../NavBar/navbar2.js"></script>
<script src="./darkmodeDashboard.js"></script>
<script src="toggleContraseña.js"></script>
</body>
</html>
