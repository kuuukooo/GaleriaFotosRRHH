//Event Listener que controla el click para cerrar el dialog 
/**
/**

La función CargadeImagenes carga imágenes y datos de álbumes desde un servidor utilizando AJAX y

los muestra en una galería utilizando la biblioteca nanogallery2 con acciones personalizadas para cada elemento.

@param show - El parámetro show en la función showDialog es un valor booleano que

determina si el diálogo debe mostrarse o cerrarse. Si show es true, el diálogo se mostrará utilizando dialog.showModal(),

y si show es false, el diálogo se cerrará.
*/


const dialog = document.querySelector('dialog');
const wrapper = document.querySelector('.wrapper');

dialog.addEventListener("click", (e) => !wrapper.contains(e.target) && dialog.close());

console.log("Corriendo prueba.");

//Función para mostrar el dialog
function showDialog(show) {
const dialog = document.querySelector('dialog');
show ? dialog.showModal() : dialog.close(), limpiar();
}

const CargaDeImagenes = () => {
    let galleryDiv = $('<div id="my_nanogallery2" data-nanogallery2=\'{}\'></div>');
    let galeriaContainer = document.getElementById('galeriaContainer');
    $(galeriaContainer).append(galleryDiv);

    $.ajax({
        url: 'datosImagenes.php',
        dataType: 'json',
        success: function(data) {
            var dataArray = Object.values(data);
            dataArray.reverse();
            let items = [];

            $.each(dataArray, function(index, album) {
                let iconPublico = album.es_publico ? 'bi bi-eye' : 'bi bi-eye-slash';

                items.push({
                    src: "fotos/" + album.miniatura,
                    srct: "fotos/" + album.miniatura,
                    title: album.descripcion,
                    ID: album.id_album,
                    kind: 'album',
                    customData: {
                        date: album.fecha_creacion,
                        AlbumID: album.id_album,
                        es_publico: album.es_publico
                    },
                    thumbnailCustomTool4: `<i class="${iconPublico}"></i>`
                });

                $.each(album.imagenes, function(index, imagen) {
                    items.push({
                        src: "fotos/" + imagen.imagen,
                        albumID: album.id_album
                    });
                });
            });

            $("#my_nanogallery2").nanogallery2({
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
                thumbnailToolbarAlbum: { topLeft: 'custom1, custom2, custom3, custom4' },
                thumbnailToolbarImage: { topLeft: 'download' },
                icons: {
                    thumbnailCustomTool1: '<i class="bi bi-trash" style="color: white"></i>',
                    thumbnailCustomTool2: '<i class="bi bi-pencil-square"></i>',
                    thumbnailCustomTool3: '<i class="bi bi-download" style="color: white"></i>',
                    thumbnailCustomTool4: '<i class="bi bi-globe" style="color: white"></i>'
                },
                fnThumbnailToolCustAction: myTnTool
            });
            function myTnTool(action, item) {
                switch (action) {
                    case 'custom1':
                        BorrarAlbum(item);
                        break;
                    case 'custom2':
                        EditarDescripcionAlbum(item);
                        break;
                    case 'custom3':
                        descargarAlbum(item); 
                        break;
                    case 'custom4':
                        PublicarAlbum(item);
                        break;
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error al obtener los datos de los álbumes:', textStatus, errorThrown);
        }
    });
};





const CargaParaSeleccion = () => {
    // Crear el div de my_nanogallery2 con los atributos data necesarios
    let galleryDiv = $('<div id="my_nanogallery2" data-nanogallery2=\'{}\'></div>');
    let galeriaContainer = document.getElementById('galeriaContainer');
    // Agregar el div al cuerpo del documento
    $(galeriaContainer).append(galleryDiv);

    // Realizar petición AJAX para obtener los datos de los álbumes e imágenes
    $.ajax({
        url: 'datosImagenes.php',
        dataType: 'json',
        success: function(data) {
            // Convertir el objeto en un array
            var dataArray = Object.values(data);

            // Toda la fé puesta.
            dataArray.reverse();

            let items = [];

            console.log(dataArray);

            // Iterar sobre los datos y construir los objetos de la galería
            $.each(dataArray, function(index, album) {
                // Añadir el álbum
                items.push({
                    src: "fotos/" + album.miniatura,
                    srct: "fotos/" + album.miniatura,
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
                        src: "fotos/" + imagen.imagen,
                        albumID: album.id_album
                    });
                });

            });

            // Inicializar la galería nanogallery2 con los items obtenidos
            $("#my_nanogallery2").nanogallery2({
                thumbnailSelectable: true, // Habilitar selección
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

            // Para eliminar álbumes
            let BotonEliminarAlbumes = document.querySelector("#btnEliminar");

            let eventAttachedEliminar = false;

            $("#my_nanogallery2").on('itemSelected.nanogallery2 itemUnSelected.nanogallery2', function() {
                var ngy2data = $("#my_nanogallery2").nanogallery2('data');

                if (!eventAttachedEliminar) {
                    BotonEliminarAlbumes.addEventListener("click", () => {
                        let albumIDs = [];
                        ngy2data.items.forEach(function(item) {
                            if (item.selected) {
                                albumIDs.push(item.GetID());
                                console.log("Selected Albums", albumIDs);
                            }
                        });
                        $.ajax({
                            url: 'EliminarMultiplesAlbums.php',
                            type: 'POST',
                            data: { albumIDs: albumIDs },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    alert('Álbumes eliminados correctamente');
                                    $(galeriaContainer).empty();
                                    CargaParaSeleccion();
                                } else if (response.error) {
                                    alert('Error: ' + response.error);
                                } else {
                                    alert('Respuesta inesperada del servidor');
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                alert('Error en la solicitud AJAX: ' + textStatus);
                                console.log("Hola", jqXHR, textStatus, errorThrown);
                            }
                        });
                    });

                    eventAttachedEliminar = true;
                }
            });

            // Manejador para publicar álbumes
            let BotonPublicarAlbumes = document.querySelector("#btnPublicar");
            let eventAttachedPublicar = false;

            $("#my_nanogallery2").on('itemSelected.nanogallery2 itemUnSelected.nanogallery2', function() {
                var ngy2data = $("#my_nanogallery2").nanogallery2('data');

                if (!eventAttachedPublicar) {
                    BotonPublicarAlbumes.addEventListener("click", () => {
                        let albumIDs = [];
                        let nuevoEstado = null;
                        ngy2data.items.forEach(function(item) {
                            if (item.selected) {
                                albumIDs.push(item.GetID());
                                if (nuevoEstado === null) {
                                    nuevoEstado = item.customData.es_publico ? 0 : 1;
                                }
                            }
                        });

                        if (albumIDs.length > 0 && nuevoEstado !== null) {
                            $.ajax({
                                url: 'PublicarMultiplesAlbumes.php',
                                type: 'POST',
                                data: {
                                    albumIDs: albumIDs,
                                    es_publico: nuevoEstado
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        alert('Álbumes actualizados correctamente');
                                        $(galeriaContainer).empty();
                                        CargaDeImagenes();
                                        console.log("Albumes actualizados: ", albumIDs);
                                        console.log("Estado: ", nuevoEstado);
                                    } else if (response.error) {
                                        alert('Error: ' + response.error);
                                    } else {
                                        alert('Respuesta inesperada del servidor');
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    alert('Error en la solicitud AJAX: ' + textStatus);
                                    console.log("Error en la solicitud AJAX: ", jqXHR, textStatus, errorThrown);
                                }
                            });
                        }
                    });

                    eventAttachedPublicar = true;
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error al obtener los datos de los álbumes:', textStatus, errorThrown);
        }
    });
};



let isCargaParaSeleccion = true;

const BotonSeleccion = document.getElementById('BotonSelector');
BotonSeleccion.addEventListener("click", () => {
    BotonSeleccion.classList.toggle('btn-pressed');
    const galeriaContainer = document.getElementById('galeriaContainer');
    $(galeriaContainer).empty();
    
    // Alternar entre las funciones dependiendo del estado
    if (isCargaParaSeleccion) {
        CargaParaSeleccion();
    } else {
        CargaDeImagenes();
    }

    // Invertir el estado para el próximo clic
    isCargaParaSeleccion = !isCargaParaSeleccion;
});


// Función para manejar acciones personalizadas en la herramienta de miniaturas
/**

La función myTnTool toma una acción y un elemento como parámetros, luego realiza diferentes acciones

basadas en la acción especificada.

@param action - El parámetro action en la función myTnTool representa la acción específica

que debe realizarse. Podría ser 'custom1', 'custom2' o 'custom3' basado en los casos de switch

en la función.

@param item - El parámetro item en la función myTnTool es el objeto que contiene

información sobre un álbum. Podría incluir detalles como el nombre del álbum, el creador del álbum, la fecha de lanzamiento,

lista de pistas, etc.
*/


/**
La función crearAlbum se utiliza para crear un álbum mediante la carga de imágenes y descripciones a través de una

solicitud AJAX, con validación para límites de imágenes y manejo de datos del formulario.

@returns La función crearAlbum devuelve un mensaje de alerta si hay errores de validación

o si se excede el límite de imágenes, o realiza una solicitud AJAX para cargar los datos del álbum

en el servidor. Las respuestas de éxito y error de la solicitud AJAX también se manejan dentro de la

función.
*/
//Función para crear el álbum
const crearAlbum = () => {
let descripcion = $('#imagenInput').val();
let imagenes = $('#imagenInputDialogAlbum').prop('files');

 // Validar si el formulario está vacío
if(descripcion.trim() === '' && imagenes.length === 0) {
    alert("Por favor, ingresa al menos una descripción o selecciona al menos una imagen.");
    return; 
}

// Crear un objeto FormData para enviar los datos al servidor
let formData = new FormData();
formData.append('description', descripcion); 

 let imagenesExcedidas = false; // Bandera para verificar si se superó el límite de imágenes

 // Agregar cada archivo al objeto FormData
for (let i = 0; i < imagenes.length; i++) {
    if(imagenes.length <= 50){
        formData.append('files[]', imagenes[i]);
    } else {
        imagenesExcedidas = true;
        break; 
    }
}

// Verificar si se superó el límite de imágenes
if(imagenesExcedidas) {
    alert("No se pueden subir más de 50 imágenes");
    return;
}

 // Si no hay errores, enviar la solicitud AJAX
$.ajax({
    url: 'upload.php', 
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
        console.log("respuesta exitosa:", response);
        console.log(formData)
        if(response.success) {
        $(galeriaContainer).empty();
        CargaDeImagenes();
        limpiar();
        alert(response.success); 
        } else {
            alert(response.error); 
        }
    },
    error: function(xhr, status, error) {
        console.error(xhr.responseText);
        console.log(formData);
        alert("Error en la solicitud AJAX. Consulta la consola para más detalles.");
    }
});
}

/**
The function "limpiar" clears the values of two input fields with the IDs "imagenInput" and
"imagenInputDialogAlbum" using jQuery.
*/
const limpiar = () => {
$('#imagenInput').val(''); // Borrar el valor del input de descripción
$('#imagenInputDialogAlbum').val(''); // Borrar el valor del input de imágenes
}
/**

La función BuscarImg se utiliza para buscar imágenes dentro de álbumes y mostrarlas en una galería

utilizando solicitudes AJAX en JavaScript.
*/
//Función para Buscar Imágenes dentro de los álbumes
function BuscarImg() {
$("#search-form").on("submit", function(e) {
e.preventDefault(); // Evita que el formulario se envíe de manera tradicional

    let searchTerm = $("#search-input").val();

    $.ajax({
        url: "buscarAlbum.php",
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
                $("#my_nanogallery2").nanogallery2("destroy");

                // Lógica para construir los items de la galería basados en los resultados de la búsqueda
                let items = [];

                // Iterar sobre los resultados de la búsqueda y construir los objetos de la galería
                $.each(data, function(index, album) {
                    // Añadir el álbum
                    items.push({
                        src: "fotos/" + album.miniatura,
                        srct: "fotos/" + album.miniatura,
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
                            src: "fotos/" + imagen.imagen,
                            albumID: album.id_album
                        });
                    });
                });

                // Inicializar la galería nanogallery2 con los nuevos items
                $("#my_nanogallery2").nanogallery2({
                    items: items,
                    thumbnailWidth: 300,
                    thumbnailHeight: 300,
                    thumbnailAlignment: 'center',
                    thumbnailGutterWidth: 70,
                    thumbnailGutterHeight: 50,
                    galleryMaxRows: 30,
                    galleryDisplayMode: 'pagination',
                    galleryPaginationMode: 'numbers',
                    locationHash: false
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

/**

La función BorrarAlbum se utiliza para eliminar un álbum enviando el ID del álbum al backend mediante

una solicitud AJAX y manejando la respuesta en consecuencia.

@param item - El parámetro item en la función BorrarAlbum representa un objeto que contiene

información sobre un álbum. La función extrae el AlbumID de la propiedad customData de

este objeto para identificar el álbum que se debe eliminar.
*/
function BorrarAlbum(item) {
// Obtener el AlbumID del customData del objeto item
var albumID = item.customData.AlbumID;

 // Crear un FormData para enviar el AlbumID al backend
    var formData = new FormData();
    formData.append('albumID', albumID);

    const confirmacion = confirm("¿Quieres eliminar este álbum?");

    if (confirmacion) {
 // Realizar la solicitud AJAX para eliminar el álbum
    $.ajax({
        url: 'eliminarAlbum.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
            success: function(response) {
                console.log("respuesta exitosa:", response);
                if (response.success === true) {
                    $(galeriaContainer).empty();
                    CargaDeImagenes();
                    alert("Imagen borrada correctamente.");
                } else if (response.success === false) {
                    alert(response.error);
                } else {
                    alert("Error inesperado en la respuesta del servidor.");
                }          
                },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("Error en la solicitud AJAX. Consulta la consola para más detalles.");
        }
        });
    }
}

/**

La función descargarAlbum descarga de forma asíncrona imágenes de un álbum, crea un archivo ZIP

que contiene las imágenes e inicia la descarga del archivo ZIP.

@param item - La función descargarAlbum es una función asíncrona que descarga imágenes de un

álbum utilizando AJAX y crea un archivo ZIP que contiene esas imágenes junto con sus descripciones.

Permíteme explicar los parámetros utilizados en la función:

@returns La función descargarAlbum no devuelve explícitamente ningún valor. Es una función asíncrona

que maneja la descarga de imágenes de un álbum y la creación de un archivo ZIP para su descarga. La

función realiza varias operaciones asíncronas utilizando promesas y await, pero no tiene una

declaración de retorno que devolvería un valor específico al completarse.
*/
async function descargarAlbum(item) {
console.log("Item:", item);

$.ajax({
    url: 'descargarAlbum.php',
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

/**

La función EditarDescripcionAlbum solicita al usuario ingresar una nueva descripción para un álbum,

envía los datos al backend utilizando AJAX y maneja la respuesta correspondientemente.

@param item - La función EditarDescripcionAlbum está diseñada para editar la descripción de un

álbum. Toma un objeto item como parámetro, asumiendo que el item contiene el ID del

álbum en su propiedad customData.
*/
const EditarDescripcionAlbum = (item) => {
// Obtener el ID del álbum del objeto item (suponiendo que item contiene el ID del álbum)
let albumID = item.customData.AlbumID;
console.log(albumID);

 // Solicitar al usuario que ingrese la nueva descripción del álbum
var nuevaDescripcion = prompt("Ingrese la nueva descripción del álbum:");

if (nuevaDescripcion !== null && nuevaDescripcion.trim() !== '') {
     // Crear un objeto FormData para enviar los datos al backend
        let formData = new FormData();
        formData.append('albumId', albumID);
        formData.append('newDescription', nuevaDescripcion);

     // Realizar la solicitud AJAX para actualizar la descripción del álbum
        $.ajax({
            url: 'editarDescripcion.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log("Respuesta exitosa:", response);
                if (response.success) {
                    alert("Descripción del álbum actualizada correctamente.");
                    $(galeriaContainer).empty();
                    CargaDeImagenes();
                } else if (response.error) {
                    alert("Error al actualizar la descripción del álbum: " + response.error);
                } else {
                    alert("Error inesperado en la respuesta del servidor.");
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("Error en la solicitud AJAX. Consulta la consola para más detalles.");
            }
        });
    } else {
        alert("La descripción no puede estar vacía.");
    }
}

const PublicarAlbum = (item) => {
    const esPublico = item.customData.es_publico;
    const nuevoEstado = esPublico ? 0 : 1;

    $.ajax({
        url: 'publicarAlbum.php',
        method: 'POST',
        data: {
            id_album: item.customData.AlbumID,
            es_publico: nuevoEstado
        },
        success: function(response) {
            if (response.success) {
                item.customData.es_publico = nuevoEstado;
                const newIcon = nuevoEstado ? 'bi bi-eye' : 'bi bi-eye-slash';
                const iconElement = item.$elt.find("[data-ngy2action='custom4'] i");
                iconElement.attr('class', newIcon);
                console.log('Estado del álbum actualizado con éxito.');
                console.log("Valor del álbum: ", nuevoEstado);
                alert("El álbum se ha publicado.")
            } else {
                console.error('Error al actualizar el estado del álbum:', response.error);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
        }
    });
};



BuscarImg();

$(document).ready(function () {
    CargaDeImagenes();
});

$('.cancelFooterDialogAlbum', '.spanHeader').on('click', function() {
    limpiar();
});

// Llamar a la función para crear un álbum cuando se hace clic en el botón "Subir Imagen"
$('.saveFooterDialogAlbum').on('click', function() {
    crearAlbum();
});

$("#my_nanogallery2").on("click", ".ngy2ThumbnailCustomTool4", function() {
    // Obtener el elemento del álbum
    const itemID = $(this).closest('.ngy2Item').data('ngy2ItemID');
    const item = $("#my_nanogallery2").nanogallery2('getItem', itemID);

    // Llamar a la función para publicar el álbum
    PublicarAlbum(item);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const fabButton = document.getElementById('fab');
        const fabMenu = document.getElementById('fab-menu');
    
        fabButton.addEventListener('click', function() {
            fabMenu.classList.toggle('show');
        });
    
        // Crear el elemento tooltip
        const tooltip = document.createElement('div');
        tooltip.classList.add('tooltip');
        document.body.appendChild(tooltip);
    
        // Función para mostrar el tooltip
        function showTooltip(event) {
            const tooltipText = event.target.closest('.fab-menu-btn').getAttribute('data-tooltip');
            tooltip.textContent = tooltipText;
            tooltip.style.left = `${event.pageX - tooltip.offsetWidth - 10}px`;
            tooltip.style.top = `${event.pageY + 10}px`;
            tooltip.classList.add('visible');
        }
    
        // Función para ocultar el tooltip
        function hideTooltip() {
            tooltip.classList.remove('visible');
        }
    
        // Función para mover el tooltip con el mouse
        function moveTooltip(event) {
            tooltip.style.left = `${event.pageX - tooltip.offsetWidth - 10}px`;
            tooltip.style.top = `${event.pageY + 10}px`;
        }
    
        // Seleccionar todos los botones con tooltips
        const buttons = document.querySelectorAll('.fab-menu-btn[data-tooltip]');
    
        // Añadir eventos de mouseover, mouseout y mousemove a cada botón
        buttons.forEach(button => {
            button.addEventListener('mouseover', showTooltip);
            button.addEventListener('mouseout', hideTooltip);
            button.addEventListener('mousemove', moveTooltip);
        });
    });

    
    async function descargarAlbumesSeleccionados() {
        var ngy2data = $("#my_nanogallery2").nanogallery2('data');
        let albumIDs = [];
        let selectedItems = [];
    
        // Filtrar los álbumes seleccionados
        ngy2data.items.forEach(function(item) {
            if (item.selected && item.kind === 'album') {
                albumIDs.push(item.GetID());
                selectedItems.push(item);
            }
        });
    
        if (albumIDs.length === 0) {
            alert("Selecciona al menos un álbum para descargar.");
            return;
        }
    
        var zip = new JSZip();
    
        // Función auxiliar para procesar un álbum
        async function procesarAlbum(index) {
            if (index < selectedItems.length) {
                const item = selectedItems[index];
                try {
                    // Realizar la petición AJAX para obtener las imágenes del álbum
                    const response = await $.ajax({
                        url: 'descargarAlbum.php',
                        type: 'POST',
                        data: { albumID: item.customData.AlbumID },
                        dataType: 'json'
                    });
    
                    console.log("Respuesta de obtener imágenes del álbum:", response);
    
                    if (response.error) {
                        alert("Error al obtener las imágenes del álbum: " + response.error);
                        return;
                    }
    
                    const imagenes = response.imagenes[0].imagen.split(',');
    
                    // Procesar cada imagen del álbum y añadirla al ZIP
                    await Promise.all(imagenes.map(async function(imagenNombre) {
                        try {
                            const trimmedNombre = imagenNombre.trim();
                            const imageBlob = await fetch(`/Galeria5-AJAX/Albums/fotos/${trimmedNombre}`).then(res => res.blob());
                            zip.file(`${response.descripcion}/${trimmedNombre}`, imageBlob);
                            console.log(`Imagen agregada al ZIP en la carpeta ${response.descripcion}: ${trimmedNombre}`);
                        } catch (error) {
                            console.error(`Error al descargar la imagen: ${imagenNombre.trim()}`, error);
                        }
                    }));
    
                    // Pasar al siguiente álbum
                    await procesarAlbum(index + 1);
    
                } catch (error) {
                    console.error("Error al procesar álbum:", error);
                }
            } else {
                // Todos los álbumes han sido procesados, generar el archivo ZIP
                try {
                    const content = await zip.generateAsync({ type: "blob" });
                    const anchor = document.createElement("a");
                    const url = window.URL.createObjectURL(content);
                    anchor.href = url;
                    anchor.download = `Albums.zip`;
                    anchor.click();
                    window.URL.revokeObjectURL(url);
                    console.log('Descarga completada: Albums.zip');
                } catch (error) {
                    console.error('Error al generar el archivo ZIP:', error);
                }
            }
        }
    
        // Iniciar el proceso con el primer álbum
        await procesarAlbum(0);
    }
    

    
    
    // Añadir el manejador de eventos para el botón de descarga de álbumes seleccionados
    document.querySelector("#btnDescargar").addEventListener("click", descargarAlbumesSeleccionados);
    