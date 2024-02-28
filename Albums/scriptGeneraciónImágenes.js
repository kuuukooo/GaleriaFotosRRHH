// Version Prueba
$(document).ready(function() {
    //Event Listener que controla el click para cerrar el dialog 
    const dialog = document.querySelector('dialog');
    const wrapper = document.querySelector('.wrapper');

    dialog.addEventListener("click", (e) => !wrapper.contains(e.target) && dialog.close());

    console.log("Corriendo prueba.");


    jQuery(document).ready(function () {
        // Crear el div de my_nanogallery2 con los atributos data necesarios
        var galleryDiv = jQuery('<div id="my_nanogallery2" data-nanogallery2=\'{}\'></div>');
        var galeriaContainer = document.getElementById('galeriaContainer');
        // Agregar el div al cuerpo del documento
        jQuery(galeriaContainer).append(galleryDiv);

        // Realizar petición AJAX para obtener los datos de los álbumes e imágenes
        jQuery.ajax({
          url: 'datosImagenes.php',
          dataType: 'json',
          success: function(data) {
            var items = [];
      
            // Iterar sobre los datos recibidos y construir los objetos de la galería
            jQuery.each(data, function(index, album) {
              // Añadir el álbum
              items.push({
                src: "fotos/" + album.miniatura,
                srct: "fotos/" + album.miniatura, // Podrías ajustar esto según la miniatura del álbum si es necesario
                title: album.descripcion,
                ID: album.id_album,
                kind: 'album'
              });
      
              // Añadir las imágenes del álbum
              jQuery.each(album.imagenes, function(index, imagen) {
                items.push({
                  src: "fotos/" + imagen.imagen,
                  srct: "fotos/" + imagen.imagen, // Podrías ajustar esto según la miniatura de la imagen si es necesario
                  title: imagen.descripcion,
                  ID: imagen.id_img_alb,
                  albumID: album.id_album
                });
              });
            });
      
            // Inicializar la galería nanogallery2 con los items obtenidos
            jQuery("#my_nanogallery2").nanogallery2({
              items: items,
              thumbnailWidth: 300,
              thumbnailHeight: 300,
              thumbnailAlignment: 'center',
              thumbnailGutterWidth: 70,
              thumbnailGutterHeight: 50,
              galleryMaxRows: 3,
              locationHash: false
            });
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error al obtener los datos de los álbumes:', textStatus, errorThrown);
          }
        });
      });
      


    // Función para crear un álbum
    const crearAlbum = () => {
        // Obtener los datos del formulario
        let descripcion = $('#imagenInput').val(); 
        let imagen = $('#imagenInputDialogAlbum').prop('files')[0]; 
        
        // Crear un objeto FormData para enviar los datos al servidor
        let formData = new FormData();
        formData.append('description', descripcion); 
        formData.append('files[]', imagen); 
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
