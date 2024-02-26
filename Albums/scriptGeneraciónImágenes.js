// Version Prueba
$(document).ready(function() {
    //Event Listener que controla el click para cerrar el dialog 
    const dialog = document.querySelector('dialog');
    const wrapper = document.querySelector('.wrapper');

    dialog.addEventListener("click", (e) => !wrapper.contains(e.target) && dialog.close());

    console.log("Corriendo prueba.");

    const generarListadoImagenes = (data) => {
        data.albumes.forEach(function(album) {
            let galleryPhotoContainer = $('<a href="" data-ngkind="album" data-ngid="' + album.id_album + '" data-ngthumb="fotos/' + album.miniatura + '">' + album.descripcion + '</a>');
            let galleryPhoto = $('<a href="" data-ngid="' + album.id_photo + '" data-ngalbumid="' + album.id_album + '" data-ngthumb="' + album.miniatura + '">' + album.descripcion + '</a>');

            $('body').append(galleryPhotoContainer);
        });
    }
    // Función para crear un álbum
    const crearAlbum = () => {
        // Obtener los datos del formulario
        let descripcion = $('#imagenInput').val(); // Obtener la descripción del formulario
        let imagen = $('#imagenInputDialogAlbum').prop('files')[0]; // Obtener la imagen del formulario (solo la primera imagen)
        
        // Crear un objeto FormData para enviar los datos al servidor
        let formData = new FormData();
        formData.append('description', descripcion); 
        formData.append('files[]', imagen); 

        // Hacer la petición AJAX para enviar los datos al servidor y guardar en la base de datos
        $.ajax({
            url: 'upload.php', 
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log(response);
                if(response.success) {
                    alert(response.success); 
                } else {
                    alert(response.error); 
                }
            },
            error: function(xhr, status, error) {
                // Manejar errores
                console.error(xhr.responseText);
                alert("Error en la solicitud AJAX. Consulta la consola para más detalles.");
            }
        });
    }
    

    // Llamar a la función para crear un álbum cuando se hace clic en el botón "Subir Imagen"
    $('.saveFooterDialogAlbum').on('click', function() {
        crearAlbum();
    });
});

//Función para mostrar el dialog
function showDialog(show) {
    const dialog = document.querySelector('dialog');
    show ? dialog.showModal() : dialog.close();
}
