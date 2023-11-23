$(document).ready(function(){
    function cargarDatosUsuarios() {
        // Llamada AJAX para obtener los datos de usuarios
        $.ajax({
            url: 'consultadeUsuarios.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Limpiar el contenido actual del tbody
                $('#tablaUsuarios tbody').empty();

                // Iterar sobre los datos y agregar filas a la tabla
                $.each(data, function(index, row) {
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
                        "<a href='#editEmployeeModal' class='edit' data-bs-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></a>" +
                        "<a href='#deleteEmployeeModal' class='delete' data-bs-toggle='modal'><i class='material-icons' data-toggle='tooltip' title='Delete'>&#xE872;</i></a>" +
                        "</td>" +
                        "</tr>"
                    );
                });
            },
            error: function(error) {
                console.log('Error al obtener los datos de usuarios:', error);
            }
        });
    }

    // Llamar a la función para cargar datos al cargar la página
    cargarDatosUsuarios();

    // Manejar el evento de envío del formulario
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
