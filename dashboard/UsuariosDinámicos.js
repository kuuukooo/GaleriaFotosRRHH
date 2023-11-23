$(document).ready(function(){
    function cargarDatosUsuarios() {
        // Llamada AJAX para obtener los datos de usuarios
        $.ajax({
            url: 'consultadeUsuarios.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // Limpiar el contenido actual del tbody
                $('#tablaUsuarios tbody').empty();
    
                // Iterar sobre los datos y agregar filas a la tabla
                $.each(data, function (index, row) {
                    // Crear un ID único para cada modal
                    var editModalId = row.id_usuario;
                    var deleteModalId = row.id_usuario;
    
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
                        "<a href='#editEmployeeModal' class='edit' data-bs-toggle='modal'>" +
                        "<i class='material-icons' data-toggle='tooltip' title='Edit' id='" + editModalId + "'>&#xE254;</i>" +
                        "</a>" +
                        /* Borrar id */
                        "<a href='#deleteEmployeeModal' class='delete' data-bs-toggle='modal'>" +
                        "<i class='material-icons' data-toggle='tooltip' title='Delete' id='" + deleteModalId + "'>&#xE872;</i>" +
                        "</a>" +
                        "</td>" +
                        "</tr>"
                    );
                });
            },
            error: function (error) {
                console.log('Error al obtener los datos de usuarios:', error);
            }
        });
    }
    

    // Llamar a la función para cargar datos al cargar la página
    cargarDatosUsuarios();

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
    //Botón de Eliminar
    // Espera a que el documento esté completamente cargado
    $(document).ready(function () {
        // Encuentra todos los botones de eliminar y agrega un manejador de eventos
        $('.delete').click(function () {
            // Obtén el id del botón clicado
            var idUsuario = $(this).attr('id');
    
            // Actualiza el modal con el id_usuario correcto
            var modal = $('#deleteEmployeeModal');
            var modalBody = modal.find('.modal-body');
            modalBody.html('<p>¿Estás seguro que quieres eliminar al usuario con ID ' + idUsuario + '?</p>');
    
            // Actualiza el formulario con el id_usuario correcto
            var form = modal.find('form');
            form.attr('action', 'BorrarUsuario.php?id_usuario=' + idUsuario);
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
                    }
                },
                error: function (error) {
                    console.log('Error en la solicitud AJAX:', error);
                }
            });
        });
    });    
});
