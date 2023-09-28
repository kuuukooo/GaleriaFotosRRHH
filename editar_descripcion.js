// Manejar el clic en el botón "Guardar" por ID
$('body').on('click', '#guardar-btn', function() {
    var newDescription = $(this).closest('form').find('textarea[name="edit-description"]').val();
    var imageId = $(this).data('image-id');
    var paginaActual = $(this).siblings('input[name="pagina_actual"]').val();


    console.log('newDescription:', newDescription);
    console.log('imageId:', imageId);
    console.log('paginaActual:', paginaActual);


    $.ajax({
        url: 'editar-descripcion.php', 
        type: 'POST',
        data: {
            'new-description': newDescription,
            'id_imagen': imageId,
            'pagina_actual': paginaActual
        },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                // Actualización exitosa, muestra un mensaje o realiza alguna acción
                alert(response.message);
                // Puedes redirigir a otra página o realizar otras acciones según tu necesidad
                window.location.href = 'index2.php?pagina=' + paginaActual;
            } else {
                // Ocurrió un error, muestra un mensaje de error
                alert(response.message);
            }
        },
        error: function() {
            // Ocurrió un error en la solicitud AJAX
            alert('Error en la solicitud AJAX');
        }
    });
});
