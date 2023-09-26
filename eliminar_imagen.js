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
                // Eliminación exitosa, actualizar la vista aquí si es necesario
                console.log("Imagen eliminada con éxito.");
                console.log(response.success);
            } else {
                // Error al eliminar, manejar el error aquí si es necesario
                console.error("Error al eliminar la imagen.");
                console.error(response.error);
            }
        },
        error: function(xhr, status, error) {
            // Manejar errores de la solicitud AJAX aquí
            console.error("Error en la solicitud AJAX.");
            console.error(error);
        }
    });
});
