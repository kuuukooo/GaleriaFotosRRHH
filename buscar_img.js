$(document).ready(function() {
    $("#search-form").on("submit", function(e) {
        e.preventDefault(); // Evita que el formulario se envíe de manera tradicional

        var searchTerm = $("#search-input").val();

        $.ajax({
            url: "buscar_img.php",
            method: "POST",
            data: { search: searchTerm },
            dataType: "json",
            success: function(data) {
                if (data.error) {
                    // Manejar el caso de que no se encontraron resultados
                        console.log(data.error);
                } else {
                    // Manejar el caso de que se encontró una imagen
                    console.log("Imagen encontrada:", data);

                    // Aquí puedes mostrar la imagen en el lugar adecuado en tu página
                    // Puedes usar el ID de la imagen para identificarla en el DOM y mostrarla
                    var imageId = data.id_imagen;
                    var imageContainer = $("#col" + imageId); // Asegúrate de que este selector sea correcto

                    // Luego, puedes manipular imageContainer para mostrar la imagen
                    // Por ejemplo, puedes cambiar la fuente de una etiqueta <img>
                }
            },
            error: function(xhr, status, error) {
                console.log('Error en la solicitud AJAX:');
                console.log('Status:', status);
                console.log('Error:', error);
            }
        });
    });
});
