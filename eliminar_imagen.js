$(".delete-button").click(function() {
    const imageId = $(this).data("image-id");

    console.log("Intentando eliminar la imagen con ID: " + imageId);

    $.ajax({
        url: "eliminar-imagen.php",
        method: "POST",
        data: { id_imagen: imageId },
        dataType: "json", // Esperar una respuesta JSON
        success: function(response) {
            if (response.success) {
                // Eliminación exitosa, actualizar la vista aquí
                console.log("Imagen eliminada con éxito.");

                // Encuentra y elimina visualmente el contenedor de la imagen
                $(`#col${imageId}`).remove();

                // Puedes llamar a la función que maneja la paginación o recarga dinámica
                // para asegurarte de que la galería se actualice correctamente
                cargarImagenesYActivarEdicion(1); // Ajusta el número de página si es necesario

                // Muestra un mensaje de éxito para el usuario
                alert("Imagen eliminada exitosamente.");
            } else {
                // Manejar errores de eliminación, si es necesario
                console.error("Error al eliminar la imagen.");
                console.error(response.error);
                alert("Error al eliminar la imagen: " + response.error);
            }
        },
        error: function(xhr, status, error) {
            // Manejar errores de la solicitud AJAX aquí
            console.error("Error en la solicitud AJAX.");
            console.error(error);
            alert("Error en la solicitud AJAX. Por favor, inténtalo de nuevo.");
        }
    });
});