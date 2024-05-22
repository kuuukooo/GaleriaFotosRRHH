
const CargadeImagenes = () => {
    // Crear el div de my_nanogallery2 con los atributos data necesarios
    let galleryDiv = $('<div id="my_nanogallery2" data-nanogallery2=\'{}\'></div>');
    let galeriaContainer = document.getElementById('galeriaContainer');
    // Agregar el div al cuerpo del documento
    $(galeriaContainer).append(galleryDiv);
    
    // Realizar petición AJAX para obtener los datos de los álbumes e imágenes
    $.ajax({
        url: 'DatosAlbumPublicos.php',
        dataType: 'json',
        success: function(data) {
            // Convertir el objeto en un array
            var dataArray = Object.values(data);
    
            // Toda la fé puesta.
            dataArray.reverse();
    
            let items = [];
    
            console.log(dataArray)
    
             // Iterar sobre los datos y construir los objetos de la galería
            $.each(dataArray, function(index, album) {
                // Añadir el álbum
                items.push({
                src: "../Albums/fotos/" + album.miniatura,
                    srct: "../Albums/fotos/" + album.miniatura,
                    title: album.descripcion,
                    ID: album.id_album,
                    kind: 'album',
                    customData: {
                        date: album.fecha_creacion,
                        AlbumID: album.id_album,
                    }
                });
    
                // Añadir las imágenes del álbum
                $.each(album.imagenes, function(index, imagen) {
                    items.push({
                        src: "../Albums/fotos/" + imagen.imagen,
                        albumID: album.id_album
                    });
                });
    
            });
    
         // Inicializar la galería nanogallery2 con los items obtenidos
        $("#my_nanogalleryAlbumes").nanogallery2({
                items: items,
                thumbnailWidth: 300,
                thumbnailHeight: 300,
                thumbnailAlignment: 'center',
                thumbnailGutterWidth: 70,
                thumbnailGutterHeight: 50,
                galleryMaxRows: 30,
                galleryDisplayMode: 'pagination',
                galleryPaginationMode: 'numbers',
                locationHash: false,
            });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error al obtener los datos de los álbumes:', textStatus, errorThrown);
            }
        }); 
    }

    $(document).ready(function () {
        CargadeImagenes();
    }); 
    