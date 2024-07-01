// Define la URL del archivo PHP que manejará la consulta de imágenes
const CargaImagenesSueltas = () => {
    const url = 'DatosImgSueltas.php';

    $.ajax({
        url: url,
        dataType: 'json',
        success: function(data) {
            let dataArr = data.imagenes; // Extrae las imágenes del objeto de respuesta
            let itemES = [];

            dataArr.forEach((contenedorSueltas) => {
                if (contenedorSueltas.imagenes.length > 1) {
                    // Crear el objeto del álbum
                    const albumItem = {
                        src: "./assets/images/posts/" + contenedorSueltas.imagenes[0],
                        srct: "./assets/images/posts/" + contenedorSueltas.imagenes[0],
                        title: contenedorSueltas.titulo,
                        ID: contenedorSueltas.id_imagen,
                        kind: 'album',
                        customData: {
                            id_imagen: contenedorSueltas.id_imagen
                        },
                        description: `Subido por: ${contenedorSueltas.descripcion}`
                    };
            
                    // Pushear el álbum al array de ítems
                    itemES.push(albumItem);
            
                    // Agregar todas las imágenes al álbum
                    contenedorSueltas.imagenes.forEach((imagen) => {
                        itemES.push({
                            src: "./assets/images/posts/" + imagen,
                            customData: {
                                id_imagen: contenedorSueltas.id_imagen
                            },
                            albumID: contenedorSueltas.id_imagen 
                        });
                    });
                } else {
                    // Agregar imagen suelta si no hay más de una imagen
                    itemES.push({
                        src: "./assets/images/posts/" + contenedorSueltas.imagenes[0],
                        title: contenedorSueltas.titulo,
                        customData: {
                            id_imagen: contenedorSueltas.id_imagen
                        },
                        description: `Subido por: ${contenedorSueltas.descripcion}`
                    });
                }
            });
            

            console.log("Processed Images:", itemES);

            $("#my_nanogalleryImagenes").nanogallery2({
                items: itemES,
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
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error al cargar las imágenes:', textStatus, errorThrown);
        }
    });
};


BuscarImg();

// Función para buscar imágenes sueltas:
function BuscarImg() {
    $("#search-form").on("submit", function(e) {
        e.preventDefault(); // Evita que el formulario se envíe de manera tradicional
        
        let searchTerm = $("#search-input").val();
    
        $.ajax({
            url: "buscarImgSuelta.php",
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
                    $("#my_nanogalleryImagenes").nanogallery2("destroy");
    
                    let dataArr = data.imagenes; // Extrae las imágenes del objeto de respuesta
                    let itemES = [];
            
                    dataArr.forEach((contenedorSueltas) => {
                        if (contenedorSueltas.imagenes.length > 1) {
                            // Crear el objeto del álbum
                            const albumItem = {
                                src: "./assets/images/posts/" + contenedorSueltas.imagenes[0],
                                srct: "./assets/images/posts/" + contenedorSueltas.imagenes[0],
                                title: contenedorSueltas.titulo,
                                description: `Subido por: ${contenedorSueltas.descripcion}`,
                                ID: contenedorSueltas.id_imagen,
                                kind: 'album',
                                customData: {
                                    id_imagen: contenedorSueltas.id_imagen
                                },
                                description: `Subido por: ${contenedorSueltas.descripcion}`
                            };
                    
                            // Pushear el álbum al array de ítems
                            itemES.push(albumItem);
                    
                            // Agregar todas las imágenes al álbum
                            contenedorSueltas.imagenes.forEach((imagen) => {
                                itemES.push({
                                    src: "./assets/images/posts/" + imagen,
                                    title: contenedorSueltas.titulo,
                                    description: `Subido por: ${contenedorSueltas.descripcion}`,
                                    customData: {
                                        id_imagen: contenedorSueltas.id_imagen
                                    },
                                    albumID: contenedorSueltas.id_imagen // Asociar la imagen al álbum por el ID
                                });
                            });
                        } else {
                            // Agregar imagen suelta si no hay más de una imagen
                            itemES.push({
                                src: "./assets/images/posts/" + contenedorSueltas.imagenes[0],
                                title: contenedorSueltas.titulo,
                                description: `Subido por: ${contenedorSueltas.descripcion}`,
                                customData: {
                                    id_imagen: contenedorSueltas.id_imagen
                                }
                            });
                        }
                    });
    
                    // Inicializar la galería nanogallery2 con los nuevos items
                    $("#my_nanogalleryImagenes").nanogallery2({
                        items: itemES,
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

function descargarAlbum(item) {
    console.log("Item:", item);

    if (!item.customData || !item.customData.id_imagen) {
        console.error("No se ha encontrado id_imagen en customData del item.");
        return;
    }

    $.ajax({
        url: 'descargarPublico.php',
        type: 'POST',
        data: { id_imagen: item.customData.id_imagen },
        dataType: 'json',
        success: async function(response) {
            console.log("Respuesta de obtener imágenes de la imagen suelta:", response);

            if (response.error) {
                alert("Error al obtener las imágenes: " + response.error);
                return;
            }

            const zip = new JSZip();
            const formData = new FormData();

            // Promesas para cargar todas las imágenes
            const imagePromises = response.imagenes.map(async function(imagen) {
                const imageBlob = await fetch(`./assets/images/posts/${imagen.imagen}`).then(res => {
                    console.log(`Imagen cargada: ${imagen.imagen}`);    
                    return res.blob();
                });

                // Agregar la imagen al archivo ZIP en el cliente
                zip.file(imagen.imagen, imageBlob);
                console.log(`Imagen agregada al ZIP: ${imagen.imagen}`);

                // Agregar la descripción de la imagen al FormData
                formData.append("image_descriptions[]", imagen.descripcion);
                console.log(`Descripción de la imagen agregada al FormData: ${imagen.descripcion}`);
            });

            // Esperar a que todas las promesas se resuelvan
            await Promise.all(imagePromises);

            // Generar el archivo ZIP en el cliente y descargarlo
            zip.generateAsync({ type: "blob" }).then(function(content) {
                // Configurar el elemento de anclaje para descargar el archivo ZIP
                const anchor = document.createElement("a");
                const url = window.URL.createObjectURL(content);
                anchor.href = url;
                anchor.download = `Imagen_${item.title}.zip`;
                console.log("Nombre de archivo descargado:", `Imagen_${item.customData.id_imagen}.zip`);

                // Simular el clic en el elemento de anclaje para iniciar la descarga
                anchor.click();

                // Liberar el objeto URL
                window.URL.revokeObjectURL(url);
            });
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert("Error en la solicitud AJAX para obtener las imágenes. Consulta la consola para más detalles.");
        }
    });
}



    
$(document).ready(function() {
    CargaImagenesSueltas();
});