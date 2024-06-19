
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
                galleryTheme: {
                    navigationPagination :  { background: '#20327e', color: '#fff', colorHover: '#ccc', borderRadius: '4px' },
                    navigationBreadcrumb :  { background: '#20327e', color: '#fff', colorHover: '#ccc', borderRadius: '4px' }
                }
            });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error al obtener los datos de los álbumes:', textStatus, errorThrown);
            }
        }); 
    }

BuscarImg();

//Función para Buscar Imágenes dentro de los álbumes
function BuscarImg() {
    $("#search-form").on("submit", function(e) {
    e.preventDefault(); // Evita que el formulario se envíe de manera tradicional
    
        let searchTerm = $("#search-input").val();
    
        $.ajax({
            url: "buscarAlbumPublico.php",
            method: "POST",
            data: { search: searchTerm },
            dataType: "json",
            success: function(data) {
                if (data.error === 'no_images_found') {
                    alert('Ninguna imagen fue encontrada');
                } else if (data.error === 'empty_search') {
                    alert('Ingresa algo en la consulta por favor');
                } else {
                    // Limpiar la galería antes de agregar nuevos elementos
                    $("#my_nanogalleryAlbumes").nanogallery2("destroy");
    
                    // Lógica para construir los items de la galería basados en los resultados de la búsqueda
                    let items = [];
    
                    // Iterar sobre los resultados de la búsqueda y construir los objetos de la galería
                    $.each(data, function(index, album) {
                        // Añadir el álbum
                        items.push({
                            src: "../Albums/fotos/" + album.miniatura,
                            srct: "../Albums/fotos/" + album.miniatura,
                            title: album.descripcion,
                            ID: album.id_album,
                            kind: 'album',
                            customData: {
                                date: album.fecha_creacion // Asegúrate de que esta propiedad está asignada correctamente
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
    
                    // Inicializar la galería nanogallery2 con los nuevos items
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
                }
            },
    
            error: function(xhr, status, error) {
                console.log('Error en la solicitud AJAX:');
                console.log('Status:', status);
                console.log('Error:', error);
            }
        });
    });        
    }

    $(document).ready(function () {
        CargadeImagenes();
    }); 