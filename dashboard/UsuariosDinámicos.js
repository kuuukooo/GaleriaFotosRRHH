$(document).ready(function () {
    // Función para obtener y mostrar los usuarios en la tabla
    function obtenerUsuarios() {
        $.ajax({
            url: "consultadeUsuarios.php",
            type: "GET",
            dataType: "json",
            success: function (data) {
                $("#tablaUsuarios tbody").empty();
                $.each(data, function (index, usuario) {
                    var editModalId = "editEmployeeModal_" + usuario.id_usuario;
                    var deleteModalId = "deleteEmployeeModal_" + usuario.id_usuario;
                    
                    // Añadir una fila para cada usuario
                    $("#tablaUsuarios tbody").append(
                        `<tr>
                            <td>
                                <span class='custom-checkbox'>
                                    <input type='checkbox' id='checkbox${usuario.id_usuario}' name='options[]' value='${usuario.id_usuario}'>
                                    <label for='checkbox${usuario.id_usuario}'></label>
                                </span>
                            </td>
                            <td>${usuario.id_usuario}</td>
                            <td>${usuario.Usuario}</td>
                            <td>${usuario.tipo_usuario}</td>
                            <td>
                                <a href='#' class='edit' data-id='${usuario.id_usuario}'>
                                    <i class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i>
                                </a>
                                <a href='#' class='delete' data-id='${usuario.id_usuario}'>
                                    <i class='material-icons' title='Delete' id='${deleteModalId}'>&#xE872;</i>
                                </a>
                            </td>
                        </tr>`
                    );

                    // Añadir modales para editar usuarios
                    $("body").append(
                        `<div id='${editModalId}' class='modal fade'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                    <form method='post' action='editarUsuario.php' class='editUserForm' data-id='${usuario.id_usuario}'>
                                        <div class='modal-header'>
                                            <h4 class='modal-title'>Editar Usuario</h4>
                                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                        </div>
                                        <div class='modal-body'>
                                            <div class='form-group'>
                                                <label for='usuario'>Usuario</label>
                                                <input type='text' minlength='4' maxlength='10' id='usuario' class='form-control' required>
                                                <input type='hidden' id='id_usuario' name='id_usuario' value=''>
                                            </div>
                                            <div class='form-group'>
                                                <label for='contrasena'>Contraseña</label>
                                                <input type='password' minlength='8' maxlength='16' id='contrasena' class='form-control' required>
                                            </div>
                                            <div class='form-group'>
                                                <label for='tipousuario' class='form-label'>Tipo Usuario</label>
                                                <select id='tipousuario' class='form-select' required>
                                                    <option value='Admin'>Admin</option>
                                                    <option value='Publisher'>Publisher</option>
                                                </select>
                                            </div>
                                            <div class='modal-footer'>
                                                <input type='button' class='btn btn-danger' data-bs-dismiss='modal' value='Cancel' id='BotonCancelarEditar'>
                                                <input type='button' class='btn btn-success btn-edit' value='Editar' id='BotonEditar'>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>`
                    );
                });
            },
            error: function (error) {
                console.log("Error al obtener los datos de usuarios:", error);
            },
        });
    }

    obtenerUsuarios();

    // Función para añadir un nuevo usuario
    $("#addUserForm").submit(function (e) {
        e.preventDefault();
        var usuario = $("#usuario").val();
        var contrasena = $("#contrasena").val();
        var tipoUsuario = $("#tipousuario").val();
        
        $.ajax({
            url: "AñadirUsuario.php",
            type: "POST",
            data: { usuario: usuario, contrasena: contrasena, tipousuario: tipoUsuario },
            success: function (response) {
                if (response.success) {
                    obtenerUsuarios();
                    $("#addEmployeeModal").modal("hide");
                    setTimeout(function () {
                        alert("Usuario agregado correctamente");
                    }, 500);
                    $("#addUserForm")[0].reset();
                } else {
                    $("#error-message-tipousuario").text(response.message);
                }
            },
            error: function (error) {
                console.log("Error en la solicitud AJAX:", error);
            },
        });
    });

    // Función para eliminar un usuario
    $("#tablaUsuarios").on("click", ".delete", function (e) {
        e.preventDefault();
        let confirmacion = confirm("¿Estás seguro que quieres eliminar este usuario?");
        var idUsuario = $(this).attr("data-id");
        console.log("ID de usuario:", idUsuario);
        
        if (confirmacion) {
            $.ajax({
                url: "BorrarUsuario.php",
                method: "GET",
                data: { id_usuario: idUsuario },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        obtenerUsuarios();
                        setTimeout(function () {
                            alert("Usuario eliminado correctamente");
                        }, 500);
                    } else {
                        alert("Error al eliminar usuario: " + response.message);
                        console.log("ID de usuario en caso de error:", idUsuario);
                    }
                },
                error: function (error) {
                    console.log("Error en la solicitud AJAX:", error);
                },
            });
        } else {
            alert("No se ha eliminado el usuario");
        }
    });

    // Función para abrir el modal de edición y cargar datos del usuario
    $("#tablaUsuarios").on("click", ".edit", function () {
        var idUsuario = $(this).data("id");
        var editModalId = "editEmployeeModal_" + idUsuario;
        console.log("El ID del Usuario es: " + idUsuario);
        
        $.ajax({
            url: "editarUsuarioConsulta.php",
            type: "GET",
            data: { id_usuario: idUsuario },
            dataType: "json",
            success: function (data) {
                $("#" + editModalId).modal("toggle");
                $("#" + editModalId + " #usuario").val(data.Usuario);
                $("#" + editModalId + " #contrasena").val(data.contrasena);
                $("#" + editModalId + " #correo").val(data.correo);
                $("#" + editModalId + " #telefono").val(data.numerotel);
                $("#" + editModalId + " #tipousuario").val(data.tipo_usuario);
            },
            error: function (error) {
                console.log("Error al obtener los datos del usuario:", error);
            },
        });
    });

    // Función para editar un usuario
    $(document).on("click", ".btn-edit", function () {
        var form = $(this).closest("form.editUserForm");
        var datosUsuario = {
            id_usuario: form.data("id"),
            usuario: form.find("#usuario").val(),
            contrasena: form.find("#contrasena").val(),
            correo: form.find("#correo").val(),
            telefono: form.find("#telefono").val(),
            tipousuario: form.find("#tipousuario").val(),
        };
        
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: datosUsuario,
            success: function (response) {
                console.log("Respuesta del servidor:", response);
                if (response.success) {
                    form.closest(".modal").modal("hide");
                    obtenerUsuarios();
                    setTimeout(function () {
                        alert("Usuario editado correctamente");
                    }, 500);
                }
            },
            error: function (error) {
                console.log("Error en la solicitud AJAX:", error);
            },
        });
    });

    // Búsqueda de usuarios en la tabla
    var noResultsMessage = $("#noResultsMessage");
    $("#tableSearch").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        var filas = $("#myTable tr");
        filas.filter(function () {
            var visible = $(this).text().toLowerCase().indexOf(value) > -1;
            $(this).toggle(visible);
        });
        noResultsMessage.toggle(filas.filter(":visible").length === 0);
    });

    // Funciones para seleccionar y borrar múltiples usuarios
    $('[data-bs-toggle="tooltip"]').tooltip();
    $(document).on("change", "#selectAll", function () {
        var checked = $(this).prop("checked");
        $('table tbody input[type="checkbox"]').prop("checked", checked);
    });

    $(document).on("change", 'table tbody input[type="checkbox"]', function () {
        var totalCheckboxes = $('table tbody input[type="checkbox"]').length;
        var checkedCheckboxes = $('table tbody input[type="checkbox"]:checked').length;
        $("#selectAll").prop("checked", totalCheckboxes === checkedCheckboxes);
    });

    $(document).on("click", "#borrarVariosUsuarios", function () {
        let confirmacion = confirm("¿Estás seguro que quieres eliminar estos usuarios?");
        var idsUsuarios = [];
        
        $("table tbody tr").each(function () {
            if ($(this).find('input[type="checkbox"]').prop("checked")) {
                var idUsuario = $(this).find(".edit").data("id");
                idsUsuarios.push(idUsuario);
            }
        });
        
        console.log("IDs seleccionados para eliminar:", idsUsuarios);
        
        if (confirmacion && idsUsuarios.length > 0) {
            $.ajax({
                url: "BorradoVariosUsuarios.php",
                type: "POST",
                dataType: "json",
                data: { id_usuario: idsUsuarios },
                success: function (response) {
                    if (response.success) {
                        console.log(response.message);
                    } else {
                        console.log("Usuario borrado: " + idsUsuarios);
                        obtenerUsuarios();
                    }
                },
                error: function (error) {
                    console.error("Error en la solicitud AJAX:", error);
                },
            });
        } else {
            $("table tbody tr").each(function () {
                var checkbox = $(this).find('input[type="checkbox"]');
                if (checkbox.prop("checked")) {
                    checkbox.prop("checked", false);
                }
            });
            alert("No se ha eliminado el usuario");
        }
        $("#selectAll").prop("checked", false);
    });
});
