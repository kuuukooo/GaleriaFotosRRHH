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
<link rel="stylesheet" type="text/css" href="../navbar2.css">
<link rel="stylesheet" type="text/css" href="../estilos.css">
<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script><script src="ScriptDashboard.js"></script>
</head>
<body>
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

                <!-- Buscador -->

                    <li class="search-box">
                    <i class='bx bx-search icon'></i>
                    <form class="d-flex" id="search-form" action="buscar_img.php" method="POST">
                        <input class="form-control me-2" type="search" name="search" id="search-input" placeholder="Buscar..." aria-label="Search">
                        <button type="submit" style="display: none;"></button>
                    </form>
                    </li>

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
							<a href="#deleteEmployeeModal" class="btn btn-danger" data-bs-toggle="modal"><i class="material-icons">&#xE15C;</i> <span>Eliminar</span></a>						
						</div>
					</div>
				</div>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>
								<span class="custom-checkbox">
									<input type="checkbox" id="selectAll">
									<label for="selectAll"></label>
								</span>
							</th>
							<th>ID</th>
							<th>Nombre</th>
							<th>Contraseña</th>
							<th>Email</th>
							<th>TipoUsuario</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<span class="custom-checkbox">
									<input type="checkbox" id="checkbox1" name="options[]" value="1">
									<label for="checkbox1"></label>
								</span>
							</td>
							<td>Thomas Hardy</td>
							<td>thomashardy@mail.com</td>
							<td>89 Chiaroscuro Rd, Portland, USA</td>
							<td>(171) 555-2222</td>
							<td>
								<a href="#editEmployeeModal" class="edit" data-bs-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
								<a href="#deleteEmployeeModal" class="delete" data-bs-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
							</td>
						</tr>
						<tr>
							<td>
								<span class="custom-checkbox">
									<input type="checkbox" id="checkbox2" name="options[]" value="1">
									<label for="checkbox2"></label>
								</span>
							</td>
							<td>Dominique Perrier</td>
							<td>dominiqueperrier@mail.com</td>
							<td>Obere Str. 57, Berlin, Germany</td>
							<td>(313) 555-5735</td>
							<td>
								<a href="#editEmployeeModal" class="edit" data-bs-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
								<a href="#deleteEmployeeModal" class="delete" data-bs-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
							</td>
						</tr>
						<tr>
							<td>
								<span class="custom-checkbox">
									<input type="checkbox" id="checkbox3" name="options[]" value="1">
									<label for="checkbox3"></label>
								</span>
							</td>
							<td>Maria Anders</td>
							<td>mariaanders@mail.com</td>
							<td>25, rue Lauriston, Paris, France</td>
							<td>(503) 555-9931</td>
							<td>
								<a href="#editEmployeeModal" class="edit" data-bs-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
								<a href="#deleteEmployeeModal" class="delete" data-bs-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
							</td>
						</tr>
						<tr>
							<td>
								<span class="custom-checkbox">
									<input type="checkbox" id="checkbox4" name="options[]" value="1">
									<label for="checkbox4"></label>
								</span>
							</td>
							<td>Fran Wilson</td>
							<td>franwilson@mail.com</td>
							<td>C/ Araquil, 67, Madrid, Spain</td>
							<td>(204) 619-5731</td>
							<td>
								<a href="#editEmployeeModal" class="edit" data-bs-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
								<a href="#deleteEmployeeModal" class="delete" data-bs-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
							</td>
						</tr>					
					</tbody>
				</table>
				<div class="clearfix">
					<div class="hint-text">Mostrando <b>5</b> de <b>25</b> entradas</div>
				</div>
			</div>
		</div>        
	</div>
	<!-- Add Modal HTML -->
	<div id="addEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form>
					<div class="modal-header">						
						<h4 class="modal-title">Añadir Usuario</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>ID</label>
							<input type="text" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Nombre</label>
							<input type="text" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Contraseña</label>
							<input type="password" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Usuario</label>
							<textarea class="form-control" required></textarea>
						</div>
						<div class="form-group">
							<label>Teléfono</label>
							<input type="text" class="form-control" required>
						</div>	
						<div class="form-group">
							<label>TipoUsuario</label>
							<textarea class="form-control" required></textarea>
						</div>				
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-danger" data-bs-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-success" value="Add">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Edit Modal HTML -->
	<div id="editEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form>
					<div class="modal-header">						
						<h4 class="modal-title">Editar Usuario</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
						<div class="form-group">
							<label>ID</label>
							<input type="text" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Nombre</label>
							<input type="text" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Contraseña</label>
							<input type="password" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Usuario</label>
							<textarea class="form-control" required></textarea>
						</div>
						<div class="form-group">
							<label>Teléfono</label>
							<input type="text" class="form-control" required>
						</div>	
						<div class="form-group">
							<label>TipoUsuario</label>
							<textarea class="form-control" required></textarea>
						</div>								
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-danger" data-bs-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-success" value="Save">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Delete Modal HTML -->
	<div id="deleteEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form>
					<div class="modal-header">						
						<h4 class="modal-title">Eliminar Usuario</h4>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">					
						<p>¿Estás seguro que quieres eliminar?</p>
						<p class="text-warning"><small>Esta acción no se deshacer.</small></p>
					</div>
					<div class="modal-footer">
						<input type="button" class="btn btn-outline-danger" data-bs-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-danger" value="Delete">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="../navbar2.js"></script>
<script src="./darkmodeDashboard.js"></script>
</body>
</html>
