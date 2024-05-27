// Define la URL del archivo PHP que manejará la consulta de imágenes
const CargaImagenesSueltas = () => {
    const url = 'DatosImgSueltas.php';

    $.ajax({
        url: url, // URL actualizada
        dataType: 'json',
        success: function(data) {
            let dataArr = data.imagenes; // Extrae las imágenes del objeto de respuesta
            dataArr.forEach((IMG) => console.log("Imagenes LOL", IMG));
            let itemES = [];
            
            dataArr.forEach((contenedorSueltas) => {
                if (contenedorSueltas.imagenes.length > 1) {
                    itemES.push({
                        src: "../assets/images/posts/" + contenedorSueltas.imagenes[0],
                        srct: "../assets/images/posts/" + contenedorSueltas.imagenes[1],
                        title: contenedorSueltas.descripcion,
                        ID: contenedorSueltas.id_imagen,
                        kind: 'album'
                    });
                } else {
                    itemES.push({
                        src: "../assets/images/posts/" + contenedorSueltas.imagenes[0],
                        title: contenedorSueltas.descripcion
                    });
                }

                if (contenedorSueltas.imagenes.length > 1) {
                    $.each(contenedorSueltas.imagenes, function(index, imagen) {
                        itemES.push({
                            src: "../assets/images/posts/" + imagen,
                            albumID: contenedorSueltas.id_imagen 
                        });
                    });
                }
            });

            console.log("LOL2", itemES);

            $("#my_nanogalleryImagenes").nanogallery2({
                items: itemES,
                thumbnailHeight: 250,
                thumbnailWidth: 250,
                thumbnailBorderVertical: 0,
                thumbnailBorderHorizontal: 0,
                thumbnailGutterWidth: 6,
                thumbnailGutterHeight: 6,
                thumbnailHoverEffect: 'label_slideUp',
                thumbnailHoverEffect2: "labelAppear75|scale120",
                thumbnailAlignment: "center",
                thumbnailLabel: {
                    display: true,
                    position: 'overImageOnBottom',
                    description: 'title',
                },
                galleryTheme: {
                    thumbnail: { borderRadius: '10px' }
                },
            });

        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error al cargar las imágenes:', textStatus, errorThrown);
        }
    });
}

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
                            itemES.push({
                                src: "../assets/images/posts/" + contenedorSueltas.imagenes[0],
                                srct: "../assets/images/posts/" + contenedorSueltas.imagenes[1],
                                title: contenedorSueltas.descripcion,
                                ID: contenedorSueltas.id_imagen,
                                kind: 'album'
                            });
                        } else {
                            itemES.push({
                                src: "../assets/images/posts/" + contenedorSueltas.imagenes[0],
                                title: contenedorSueltas.descripcion
                            });
                        }
    
                        if (contenedorSueltas.imagenes.length > 1) {
                            $.each(contenedorSueltas.imagenes, function(index, imagen) {
                                itemES.push({
                                    src: "../assets/images/posts/" + imagen,
                                    albumID: contenedorSueltas.id_imagen 
                                });
                            });
                        }
                    });
    
                    // Inicializar la galería nanogallery2 con los nuevos items
                    $("#my_nanogalleryImagenes").nanogallery2({
                        items: itemES,
                        thumbnailHeight: 250,
                        thumbnailWidth: 250,
                        thumbnailBorderVertical: 0,
                        thumbnailBorderHorizontal: 0,
                        thumbnailGutterWidth: 6,
                        thumbnailGutterHeight: 6,
                        thumbnailHoverEffect: 'label_slideUp',
                        thumbnailHoverEffect2: "labelAppear75|scale120",
                        thumbnailAlignment: "center",
                        thumbnailLabel: {
                            display: true,
                            position: 'overImageOnBottom',
                            description: 'title',
                        },
                        galleryTheme: {
                            thumbnail: { borderRadius: '10px' }
                        },
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

$(document).ready(function() {
    CargaImagenesSueltas();
});