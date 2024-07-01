
const CargadeImagenes = () => {
    let galleryDiv = $('<div id="my_nanogallery2" data-nanogallery2=\'{}\'></div>');
    let galeriaContainer = document.getElementById('galeriaContainer');
    $(galeriaContainer).append(galleryDiv);

    $.ajax({
        url: 'DatosAlbumPublicos.php',
        dataType: 'json',
        success: function(data) {
            var dataArray = Object.values(data);
            dataArray.reverse();

            let items = [];

            console.log(dataArray);

            $.each(dataArray, function(index, album) {
                items.push({
                    src: "./Albums/fotos/" + album.miniatura,
                    srct: "./Albums/fotos/" + album.miniatura,
                    title: album.descripcion,
                    description: `Subido por: ${album.usuario}`, // Añadir descripción con el usuario
                    ID: album.id_album,
                    kind: 'album',
                    customData: {
                        date: album.fecha_creacion,
                        AlbumID: album.id_album,
                    }
                });

                $.each(album.imagenes, function(index, imagen) {
                    items.push({
                        src: "./Albums/fotos/" + imagen.imagen,
                        albumID: album.id_album
                    });
                });
            });
    
         // Inicializar la galería nanogallery2 con los items obtenidos
        $("#my_nanogalleryAlbumes").nanogallery2({
                items: items,
                thumbnailWidth: 'auto',
                thumbnailHeight: 300,
                thumbnailAlignment: 'center',
                thumbnailGutterWidth: 5,
                thumbnailGutterHeight: 5,
                //Configuración para dentro de la galería
                thumbnailL1Height:  350,              
                thumbnailL1Width:   350,  
                galleryL1DisplayTransition: 'slideUp',
                thumbnailL1GutterWidth: 10,
                thumbnailL1GutterHeight: 30 ,
                galleryRenderDelay: 50,
                galleryMaxRows: 2,
                galleryDisplayMode: 'pagination',
                galleryPaginationMode: 'numbers',
                locationHash: false,
                thumbnailToolbarAlbum: { topLeft: 'custom1' },
                thumbnailToolbarImage: { topLeft: 'download' },
                thumbnailLabel:     { valign: "center", position: 'overImage', displayDescription: true },
                galleryTheme: {
                    thumbnail: {
                        borderColor: '#e4e9f7',
                        borderRadius: '10px',
                        titleBgColor: 'transparent',
                        descriptionBgColor: 'transparent'
                    },
                    navigationPagination: {
                        background: '#20327e',
                        color: '#fff',
                        colorHover: '#ccc',
                        borderRadius: '4px'
                    },
                    navigationBreadcrumb: {
                        background: '#20327e',
                        color: '#fff',
                        colorHover: '#ccc',
                        borderRadius: '4px'
                    }
                },
                displayDescription: true,
                display: true,
                icons: {
                    thumbnailCustomTool1: '<i class="bi bi-download" style="color: white"></i>',
                },
                fnThumbnailToolCustAction: myTnTool
            });
            function myTnTool(action, item) {
                switch (action) {
                    case 'custom1':
                        descargarAlbum(item);
                        break;
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error al obtener los datos de los álbumes:', textStatus, errorThrown);
            }
        }); 
    }

BuscarImg();

/**
 * Función para buscar imágenes dentro de los álbumes.
 * 
 * La función se asocia al evento de envío del formulario de búsqueda. Cuando el formulario se envía, se evita el envío tradicional y en su lugar se realiza una solicitud AJAX para buscar imágenes basadas en el término de búsqueda ingresado.
 * 
 * Flujo de la función:
 * 1. Se evita el envío tradicional del formulario utilizando `e.preventDefault()`.
 * 2. Se obtiene el término de búsqueda ingresado por el usuario.
 * 3. Se realiza una solicitud AJAX a `buscarAlbumPublico.php` con el término de búsqueda.
 * 4. Se manejan las respuestas de la solicitud AJAX:
 *    - Si no se encuentran imágenes, se muestra una alerta indicando que no se encontraron imágenes.
 *    - Si la búsqueda está vacía, se muestra una alerta indicando que se ingrese algo en la consulta.
 *    - Si se encuentran resultados, se destruye la galería existente y se construyen los nuevos elementos de la galería basados en los resultados de la búsqueda.
 */
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
                            src: "./Albums/fotos/" + album.miniatura,
                            srct: "./Albums/fotos/" + album.miniatura,
                            title: album.descripcion,
                            description: `Subido por: ${album.usuario}`,
                            ID: album.id_album,
                            kind: 'album',
                            customData: {
                                date: album.fecha_creacion // Asegúrate de que esta propiedad está asignada correctamente
                            }
                        });
    
                        // Añadir las imágenes del álbum
                        $.each(album.imagenes, function(index, imagen) {
                            items.push({
                                src: "./Albums/fotos/" + imagen.imagen,
                                albumID: album.id_album
                            });
                        });
                    });
    
                    // Inicializar la galería nanogallery2 con los nuevos items
                    $("#my_nanogalleryAlbumes").nanogallery2({
                        items: items,
                        thumbnailWidth: 'auto',
                        thumbnailHeight: 300,
                        thumbnailAlignment: 'center',
                        thumbnailGutterWidth: 5,
                        thumbnailGutterHeight: 5,
                        //Configuración para dentro de la galería
                        thumbnailL1Height: 350,
                        thumbnailL1Width: 350,
                        galleryL1DisplayTransition: 'slideUp',
                        thumbnailL1GutterWidth: 10,
                        thumbnailL1GutterHeight: 30,
                        thumbnailHoverEffect: 'label_slideUp',
                        thumbnailHoverEffect2: "scale120",
                        thumbnailAlignment: "center",
                        //Paginación
                        galleryRenderDelay: 50,
                        galleryMaxRows: 2,
                        galleryDisplayMode: 'pagination',
                        galleryPaginationMode: 'numbers',
                        thumbnailLabel: {
                            display: true,
                            position: 'overImageOnBottom',
                            displayDescription: true
                        },
                        thumbnailToolbarAlbum: { topLeft: 'custom1' },
                        thumbnailToolbarImage: { topLeft: 'download' },
                        displayDescription: true,
                        display: true,
                        galleryTheme: {
                            thumbnail: {
                                borderColor: '#e4e9f7',
                                borderRadius: '10px',
                                titleBgColor: 'transparent',
                                descriptionBgColor: 'transparent'
                            },
                            navigationPagination: {
                                background: '#20327e',
                                color: '#fff',
                                colorHover: '#ccc',
                                borderRadius: '4px'
                            },
                            navigationBreadcrumb: {
                                background: '#20327e',
                                color: '#fff',
                                colorHover: '#ccc',
                                borderRadius: '4px'
                            }
                        },
                        // Mueve los íconos personalizados fuera del objeto galleryTheme
                        icons: {
                            thumbnailCustomTool1: '<i class="bi bi-download" style="color: white"></i>'
                        },
                        fnThumbnailToolCustAction: myTnTool
                    });
                    function myTnTool(action, item) {
                        switch (action) {
                            case 'custom1':
                                descargarAlbum(item);
                                break;
                        }
                    }
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

/**
 * Función asincrónica para descargar todas las imágenes de un álbum específico y empaquetarlas en un archivo ZIP.
 * 
 * @param {Object} item - Objeto que contiene información sobre el álbum, incluyendo el AlbumID y el título del álbum.
 * 
 * El flujo de la función es el siguiente:
 * 1. Registro de Información Inicial: La función comienza registrando el objeto `item` en la consola.
 * 2. Solicitud AJAX:
 *    - Realiza una solicitud AJAX a './Albums/descargarAlbum.php' para obtener las imágenes del álbum especificado por `albumID`.
 *    - Si la solicitud tiene éxito y no hay errores en la respuesta, procede a procesar las imágenes.
 *    - Si hay un error en la solicitud, muestra una alerta con el mensaje de error.
 * 3. Procesamiento de Imágenes:
 *    - Crea una instancia de `JSZip` para manejar el archivo ZIP.
 *    - Crea un `FormData` para almacenar las descripciones de las imágenes.
 *    - Mapea las imágenes obtenidas y las carga usando `fetch`, agregándolas al archivo ZIP y almacenando sus descripciones en `FormData`.
 * 4. Generación y Descarga del Archivo ZIP:
 *    - Espera a que todas las imágenes se hayan cargado y agregado al ZIP.
 *    - Genera el archivo ZIP y crea un objeto URL para el contenido del ZIP.
 *    - Crea un elemento de anclaje (`<a>`) y lo configura para descargar el archivo ZIP con el nombre `Album_[title].zip`.
 *    - Simula un clic en el anclaje para iniciar la descarga y luego libera el objeto URL.
 * 
 * Manejo de Errores:
 * Si la solicitud AJAX falla, registra el error en la consola y muestra una alerta al usuario indicando que hubo un error en la solicitud.
 */
async function descargarAlbum(item) {
    console.log("Item:", item);
    
    $.ajax({
        url: './Albums/descargarAlbum.php',
        type: 'POST',
        data: { albumID: item.customData.AlbumID },
        dataType: 'json',
        success: async function(response) {
            console.log("Respuesta de obtener imágenes del álbum:", response);
    
            if (response.error) {
                alert("Error al obtener las imágenes del álbum: " + response.error);
                return;
            }
    
            const zip = new JSZip();
            const formData = new FormData();
    
            // Promesas para cargar todas las imágenes
            const imagePromises = response.imagenes.map(async function(imagen) {
                // Separar las imágenes por comas
                const imagenes = imagen.imagen.split(',');
    
                // Iterar sobre cada imagen y agregarla al ZIP
                await Promise.all(imagenes.map(async function(imagenNombre, index) {
                    const imageBlob = await fetch(`/Galeria5-AJAX/Albums/fotos/${imagenNombre}`).then(res => {
                        console.log(`Imagen cargada: ${imagenNombre}`);    
                        return res.blob();
                    });
    
                    // Agregar la imagen al archivo ZIP en el cliente
                    zip.file(imagenNombre, imageBlob);
                    console.log(`Imagen agregada al ZIP: ${imagenNombre}`);
    
                    // Agregar la descripción de la imagen al FormData
                    formData.append("image_descriptions[]", imagen.descripcion);
                    console.log(`Descripción de la imagen agregada al FormData: ${imagen.descripcion}`);
                }));
            }); 
    
            // Esperar a que todas las promesas se resuelvan
            await Promise.all(imagePromises);
    
            // Generar el archivo ZIP en el cliente y descargarlo
            zip.generateAsync({ type: "blob" }).then(function(content) {
                // Configurar el elemento de anclaje para descargar el archivo ZIP
                const anchor = document.createElement("a");
                const url = window.URL.createObjectURL(content);
                anchor.href = url;
                anchor.download = `Album_${item.title}.zip`;
                console.log("Nombre de archivo descargado:", `album_${item.customData.AlbumID}.zip`);
    
                // Simular el clic en el elemento de anclaje para iniciar la descarga
                anchor.click();
    
                // Liberar el objeto URL
                window.URL.revokeObjectURL(url);
            });
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert("Error en la solicitud AJAX para obtener las imágenes del álbum. Consulta la consola para más detalles.");
        }
    });
}


    $(document).ready(function () {
        CargadeImagenes();
    }); 