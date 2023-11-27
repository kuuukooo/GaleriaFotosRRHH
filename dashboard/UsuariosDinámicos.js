$(document).ready(function () {
    function cargarDatosUsuarios() {
        $.ajax({
            url: 'consultadeUsuarios.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#tablaUsuarios tbody').empty();

                $.each(data, function (index, row) {
                    var editModalId = 'editEmployeeModal_' + row.id_usuario;
                    var deleteModalId = 'deleteEmployeeModal_' + row.id_usuario;

                    $('#tablaUsuarios tbody').append(
                        "<tr>" +
                        "<td>" +
                        "<span class='custom-checkbox'>" +
                        "<input type='checkbox' id='checkbox" + row.id_usuario + "' name='options[]' value='" + row.id_usuario + "'>" +
                        "<label for='checkbox" + row.id_usuario + "'></label>" +
                        "</span>" +
                        "</td>" +
                        "<td>" + row.id_usuario + "</td>" +
                        "<td>" + row.Usuario + "</td>" +
                        "<td>" + row.contrasena + "</td>" +
                        "<td>" + row.correo + "</td>" +
                        "<td>" + row.numerotel + "</td>" +
                        "<td>" + row.tipo_usuario + "</td>" +
                        "<td>" +
                        "<a href='#' class='edit' data-id='" + row.id_usuario + "'>" +
                        "<i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i>" +
                        "</a>" +
                        "<a href='#' class='delete' data-id='" + row.id_usuario + "'>" +
                        "<i class='material-icons' data-toggle='tooltip' title='Delete'>&#xE872;</i>" +
                        "</a>" +
                        "</td>" +
                        "</tr>"
                    );
                    /*  Generación de modales de borrar y editar */
                    $('body').append(
                        /* Modal de borrado */
                        "<div id='" + deleteModalId + "' class='modal fade'>" +
                            "<div class='modal-dialog'>" +
                                "<div class='modal-content'>" +
                                    "<form method='get' action='BorrarUsuario.php' id='deleteUserForm'>" +
                                    "<div class='modal-header'>" +
                                        "<h4 class='modal-title'>Eliminar Usuario</h4>" +
                                        "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>" +
                                    "</div>" +
                                    "<div class='modal-body'>" +
                                        "<p>¿Estás seguro que quieres eliminar al usuario con ID " + row.id_usuario + "?</p>" +
                                        "<p class='text-warning'><small>Esta acción no se deshace.</small></p>" +
                                    "</div>" +
                                    "<div class='modal-footer'>" +
                                        "<input type='button' class='btn btn-outline-danger' data-bs-dismiss='modal' value='Cancel'>" +
                                        "<input type='submit' class='btn btn-danger' value='Delete'>" +
                                    "</div>" +
                                    "</form>" +
                                "</div>" +
                            "</div>" +
                        "</div>" +
                        
                        /* Modal de edición */
                        "<div id='" + editModalId + "' class='modal fade'>" +
                            "<div class='modal-dialog'>" +
                                "<div class='modal-content'>" +
                                    "<form method='get' action='EditarUsuario.php' id='editUserForm'>" +
                                    "<div class='modal-header'>" +
                                        "<h4 class='modal-title'>Editar Usuario</h4>" +
                                        "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>" +
                                    "</div>" +
                                    "<div class='modal-body'>" +
                                        "<div class='form-group'>" +
						                    "<label for='usuario'>Usuario</label>" +
						                    "<input type='text' id='usuario' class='form-control' required>" +
						                "</div>" +
                                        "<div class='form-group'>" +
                                            "<label for='contrasena'>Contraseña</label>"+
                                            "<input type='password' id='contrasena' class='form-control' required>" +
                                        "</div>" +
                                        "<div class='form-group'>" +
                                            "<label for='correo'>Correo</label>" +
                                            "<textarea id='correo' class='form-control' required ></textarea>" +
                                        "</div>" +
                                        "<div class='form-group'>" +
                                            "<label for='telefono'>Teléfono</label>" +
                                            "<input type='text' id='telefono' class='form-control' required>" +                                       	
                                        "</div>" +
                                        "<div class='form-group'>" +
                                            "<label for='tipousuario'>TipoUsuario</label>" +
                                            "<textarea id='tipousuario' class='form-control' required></textarea>" +
                                        "</div>" +	
                                    "<div class='modal-footer'>" +
                                        "<input type='button' class='btn btn-danger' data-bs-dismiss='modal' value='Cancel'>" +
                                        "<input type='submit' class='btn btn-success' value='Save'>" +
                                    "</div>" +
                                    "</form>" +
                                "</div>" +
                            "</div>" +
                        "</div>"
                    );
                });
            },
            error: function (error) {
                console.log('Error al obtener los datos de usuarios:', error);
            }
        });
    }

    cargarDatosUsuarios();  // Llamamos a la función cuando la página se carga inicialmente

    // Evento para abrir el modal de edición
    $('#tablaUsuarios').on('click', '.edit', function () {
        var userId = $(this).data('id');
        var editModalId = 'editEmployeeModal_' + userId;
        
        // Abre el modal de edición usando Bootstrap
        $('#' + editModalId).modal('show');
    });

    // Evento para abrir el modal de eliminación
    $('#tablaUsuarios').on('click', '.delete', function () {
        var userId = $(this).data('id');
        var deleteModalId = 'deleteEmployeeModal_' + userId;
        
        // Abre el modal de eliminación usando Bootstrap
        $('#' + deleteModalId).modal('show');
    });


    $(document).ready(function () {
    // Agregar un nuevo usuario
    $('#addUserForm').submit(function(e){
        e.preventDefault();

        // Obtener los valores de los campos
        var usuario = $('#usuario').val();
        var contrasena = $('#contrasena').val();
        var correo = $('#correo').val();
        var telefono = $('#telefono').val();
        var tipousuario = $('#tipousuario').val();

        // Llamada AJAX para agregar el usuario
        $.ajax({
            url: 'AñadirUsuario.php',
            type: 'POST',
            data: {
                usuario: usuario,
                contrasena: contrasena,
                correo: correo,
                telefono: telefono,
                tipousuario: tipousuario
            },
            success: function(response) {
                // Verificar la respuesta del servidor
                if (response.success) {
                    // Recargar los datos de la tabla
                    cargarDatosUsuarios();
                    // Cerrar el modal
                    $('#addEmployeeModal').modal('hide');
                    //Mostrar una ventana de "Usuario agregado correctamente"
                    setTimeout(function(){
                    alert("Usuario agregado correctamente");
                     }, 500);
                    // Limpiar los campos del formulario
                    $('#addUserForm')[0].reset();
                } else {
                    // Mostrar el mensaje de error en el frontend
                    $('#error-message-tipousuario').text(response.message);
                }
            },
            error: function(error) {
                console.log('Error en la solicitud AJAX:', error);
            }
        });
    });   
    });
    //Botón de Eliminar
   // Espera a que el documento esté completamente cargado
    $(document).ready(function () {
        // Encuentra todos los botones de eliminar y agrega un manejador de eventos
        $('.delete').click(function () {
            // Obtén el id del botón clicado
            var idUsuario = $(this).attr('id');
            
            console.log("ID de usuario:", idUsuario);

            // Actualiza el modal con el id_usuario correcto
            var modal = $('#deleteEmployeeModal');
            var modalBody = modal.find('.modal-body');
            modalBody.html('<p>¿Estás seguro que quieres eliminar al usuario con ID ' + idUsuario + '?</p>');

            // Actualiza el formulario con el id_usuario correcto
            var form = modal.find('form');
            form.attr('action', 'BorrarUsuario.php?id_usuario=' + encodeURIComponent(idUsuario));
        });

        // Manejador de eventos para el envío del formulario de eliminación
        $('#deleteUserForm').submit(function (e) {
            e.preventDefault();

            // Realiza la llamada AJAX para borrar el usuario
            $.ajax({
                url: $(this).attr('action'),
                type: 'GET',
                success: function (response) {
                    // Verifica la respuesta del servidor
                    if (response.success) {
                        // Recargar los datos de la tabla
                        cargarDatosUsuarios();
                        // Cerrar el modal
                        $('#deleteEmployeeModal').modal('hide');
                        // Mostrar una ventana de "Usuario eliminado correctamente"
                        setTimeout(function () {
                            alert("Usuario eliminado correctamente");
                        }, 500);
                    } else {
                        // Mostrar el mensaje de error en el frontend
                        alert("Error al eliminar usuario: " + response.message);
                        console.log("ID de usuario en caso de error:", idUsuario);
                    }
                },
                error: function (error) {
                    console.log('Error en la solicitud AJAX:', error);
                }
            });
        });
    });
});
