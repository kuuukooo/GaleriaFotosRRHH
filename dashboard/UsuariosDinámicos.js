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
                            "<td>" + row.correo + "</td>" +
                            "<td>" + row.numerotel + "</td>" +
                            "<td>" + row.tipo_usuario + "</td>" +
                            "<td>" +
                                "<a href='#' class='edit' data-id='" + row.id_usuario + "'>" +
                                    "<i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i>" +
                                "</a>" +
                                "<a href='#' class='delete' data-id='" + row.id_usuario + "'>" +
                                    "<i class='material-icons' title='Delete' id='" + deleteModalId + "'>&#xE872;</i>" +
                                "</a>" +
                            "</td>" +
                        "</tr>"
                    );
                    /*  Generación de modales de borrar y editar */
                    $('body').append(
                        /* Modal de edición */
                        "<div id='" + editModalId + "' class='modal fade'>" +
                            "<div class='modal-dialog'>" +
                                "<div class='modal-content'>" +
                                    "<form method='post' action='EditarUsuario.php' class='editUserForm' data-id='" + row.id_usuario + "'>" +
                                    "<div class='modal-header'>" +
                                        "<h4 class='modal-title'>Editar Usuario</h4>" +
                                        "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>" +
                                    "</div>" +
                                        "<div class='modal-body'>" +                                    
                                            "<div class='form-group'>" +
                                                "<label for='usuario'>Usuario</label>" +
                                                "<input type='text' minlength='4' maxlength='10' id='usuario' class='form-control' required>" +
                                                "<input type='hidden' id='id_usuario' name='id_usuario' value=''>" +
                                            "</div>" +
                                            "<div class='form-group'>" +
                                                "<label for='contrasena'>Contraseña</label>" +
                                                "<input type='password' minlength='8' maxlength='16' id='contrasena' class='form-control' required>" +
                                            "</div>" +
                                            "<div class='form-group'>" +
                                                "<label for='correo'>Correo</label>" +
                                                "<input type='email' id='correo' class='form-control' required >" +
                                            "</div>" +
                                            "<div class='form-group'>" +
                                                "<label for='telefono'>Teléfono</label>" +
                                                "<input type='tel' minlength='10' maxlength='10' id='telefono' class='form-control' required>" +                                       	
                                            "</div>" +
                                            "<div class='form-group'>" +
                                                "<label for='tipousuario' class='form-label'>TipoUsuario</label>" +
                                                "<select id='tipousuario' class='form-select' required>" +
                                                "<option value='Admin'>Admin</option>" +
                                                "<option value='Usuario'>Usuario</option>" +
                                                "</select>" +
                                            "</div>" +
                                            "<div class='modal-footer'>" +
                                                "<input type='button' class='btn btn-danger' data-bs-dismiss='modal' value='Cancel'>" +
                                                "<input type='button' class='btn btn-success btn-edit' value='Editar'>" +
                                            "</div>" +
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

    cargarDatosUsuarios();
      // Llamamos a la función cuando la página se carga inicialmente

    $(document).ready(function () {
    //Añadido de Usuarios
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
    //Espera a que el documento esté completamente cargado
    $(document).ready(function () {
        // Encuentra todos los botones de eliminar y agrega un manejador de eventos
        $('#tablaUsuarios').on('click', '.delete', function (event) {
            event.preventDefault();
            // Muestra la alerta
            const confirmacionBorrado = confirm("¿Estás seguro que quieres eliminar este usuario?");

            // Obtén el id del botón clicado
            var idUsuario = $(this).attr('data-id');
            
            console.log("ID de usuario:", idUsuario);
    
    if (confirmacionBorrado) {
            $.ajax({
                url: 'BorrarUsuario.php',
                method: 'GET',
                data: { id_usuario: idUsuario },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        // Recargar los datos de la tabla
                        cargarDatosUsuarios();
                        // Mostrar una alerta de "Usuario eliminado correctamente"
                        setTimeout(function () {
                            alert("Usuario eliminado correctamente");
                        }, 500);
                    } else {
                        alert("Error al eliminar usuario: " + response.message);
                        console.log("ID de usuario en caso de error:", idUsuario);
                    }
                },
                error: function (error) {
                    console.log('Error en la solicitud AJAX:', error);
                    }
                });
    } else {
    alert("No se ha eliminado el usuario");
    }
    });
});     
    //Botón de Editar
    //Espera a que el documento esté completamente cargado
    $(document).ready(function () {
        // Evento para abrir el modal de edición
        $('#tablaUsuarios').on('click', '.edit', function () {
            var userId = $(this).data('id');
            var editModalId = 'editEmployeeModal_' + userId;
    
            console.log("El ID del Usuario es: " + userId);
    
            // Recupera los datos del usuario haciendo una nueva solicitud AJAX
            $.ajax({
                url: 'editarUsuarioConsulta.php',
                type: 'GET',
                data: { id_usuario: userId },
                dataType: 'json',
                success: function (userData) {
                    // Abre el modal de edición usando Bootstrap
                    $('#' + editModalId).modal('toggle');
    
                    // Llena los campos del modal con los datos del usuario
                    $('#' + editModalId + ' #usuario').val(userData.Usuario);
                    $('#' + editModalId + ' #contrasena').val(userData.contrasena);
                    $('#' + editModalId + ' #correo').val(userData.correo);
                    $('#' + editModalId + ' #telefono').val(userData.numerotel);
                    $('#' + editModalId + ' #tipousuario').val(userData.tipo_usuario);
                },
                error: function (error) {
                    console.log('Error al obtener los datos del usuario:', error);
                }
            });
        });
    
    
        // Evento para enviar el formulario de edición
        $(document).on('click', '.btn-edit', function () {
            var form = $(this).closest('form.editUserForm');
            
            // Construye un objeto con los datos del formulario
            var formData = {
                id_usuario: form.data('id'),
                usuario: form.find('#usuario').val(),
                contrasena: form.find('#contrasena').val(),
                correo: form.find('#correo').val(),
                telefono: form.find('#telefono').val(),
                tipousuario: form.find('#tipousuario').val()
            };

            // Realiza una solicitud AJAX al archivo EditarUsuario.php
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: formData, // Envía los datos del formulario como objeto
                success: function (response) {
                    console.log('Respuesta del servidor:', response);

                    // Cierra el modal de edición si la actualización fue exitosa
                    if (response.success) {
                        form.closest('.modal').modal('hide');
                        // Recargar los datos de la tabla
                        cargarDatosUsuarios();
                        // Mostrar una alerta de "Usuario editado correctamente"
                        setTimeout(function () {
                            alert("Usuario editado correctamente");
                        }, 500);
                    }
                },
                error: function (error) {
                    console.log('Error en la solicitud AJAX:', error);
                }
            });
        });
    });  

        // Filtrar tabla según el buscador
        $(document).ready(function(){
            // Almacena la referencia al mensaje de "No se ha encontrado nada"
            var noResultsMessage = $("#noResultsMessage");
        
            // Filtra la tabla en función del texto ingresado en el input
            $("#tableSearch").on("keyup", function() {
              var value = $(this).val().toLowerCase();
              var tableRows = $("#myTable tr");
        
              // Filtra las filas de la tabla
              tableRows.filter(function() {
                var rowText = $(this).text().toLowerCase();
                var isVisible = rowText.indexOf(value) > -1;
                $(this).toggle(isVisible);
              });
        
              // Muestra o oculta el mensaje de "No se ha encontrado nada"
              noResultsMessage.toggle(tableRows.filter(":visible").length === 0);
            });
          });  


        //Selección de checks para eliminar todos a la vez
        $(document).ready(function () {
            // Activate tooltip
            $('[data-bs-toggle="tooltip"]').tooltip();
        
            // Evento change para el checkbox principal (seleccionar todo)
            $(document).on('change', '#selectAll', function () {
                var isChecked = $(this).prop("checked");
                $('table tbody input[type="checkbox"]').prop("checked", isChecked);
            });
        
            // Evento change para las casillas de verificación individuales
            $(document).on('change', 'table tbody input[type="checkbox"]', function () {
            // Encuentra la fila más cercana
            var closestRow = $(this).closest('tr');

            // Obtén el valor del atributo data-id
            var elementId = closestRow.find('.edit').data('id');

            // Realiza acciones con el ID obtenido
            console.log('ID del elemento:', elementId);


            var allChecked = $('table tbody input[type="checkbox"]:checked').length === $('table tbody input[type="checkbox"]').length;
            $("#selectAll").prop("checked", allChecked);       
        });

        // Evento click para el botón de eliminar
        $(document).on('click', '#borrarVariosUsuarios', function () {
            // Declaración de la confirmación de borrado
            const confirmacionBorradoUsuarios = confirm("¿Estás seguro que quieres eliminar este usuario?");
            var selectedIds = [];

            // Recorre las filas de la tabla
            $('table tbody tr').each(function () {
                var checkbox = $(this).find('input[type="checkbox"]');
                if (checkbox.prop('checked')) {
                    // Obtiene el ID de la fila y lo agrega al array
                    var elementId = $(this).find('.edit').data('id');
                    selectedIds.push(elementId);
                }
            });

            // Realiza acciones con los IDs obtenidos (por ejemplo, eliminar de la base de datos)
            console.log('IDs seleccionados para eliminar:', selectedIds);

            if (confirmacionBorradoUsuarios) {
            // Verifica si hay elementos seleccionados antes de enviar la solicitud AJAX
            if (selectedIds.length > 0) {
                // Lógica AJAX para eliminar usuarios
                $.ajax({
                    url: 'BorradoVariosUsuarios.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { id_usuario: selectedIds },
                    success: function (response) {
                        // Maneja la respuesta del servidor
                        if (response.success) {
                            // Éxito: Puedes mostrar un mensaje de éxito o actualizar la tabla
                            console.log(response.message);
                            // Aquí puedes recargar la tabla o actualizar la interfaz según sea necesario
                        } else {
                            // Error: Muestra un mensaje de error
                            console.log("Usuario borrado: " + selectedIds);
                            cargarDatosUsuarios();
                        }
                    },
                    error: function (error) {
                        // Maneja errores de la solicitud AJAX
                        console.error('Error en la solicitud AJAX:', error);
                    }
                });
                } 
            } else {
                // Recorre las filas de la tabla
                $('table tbody tr').each(function () {
                var checkbox = $(this).find('input[type="checkbox"]');
                if (checkbox.prop('checked')) {
                    checkbox.prop('checked', false);
                }
                });
                alert("No se ha eliminado el usuario");
            }
            // Desmarcar el checkbox de "Seleccionar todo"
            $("#selectAll").prop("checked", false);
            });
        });
});
