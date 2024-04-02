$(document).ready(function() {
    console.log("Script de edición de Cargado de Imagenes cargado.");

    const generarListadoImagenes = (data) => {
        data.imagenes.forEach(function(imagen) {
            // Crea un nuevo elemento .col y .card con jQuery
            let colCardContainer = $('<div>');
            colCardContainer.addClass('col');
            colCardContainer.attr('id', 'col' + imagen.id_imagen);

            let cardElement = $('<div>');
            cardElement.addClass('card');

            // Crea un nuevo elemento div con jQuery
            let carouselElement = $('<div>');

            // Asigna la ID dinámicamente al elemento carrusel
            carouselElement.attr('id', 'carousel' + imagen.id_imagen);
            carouselElement.addClass('carousel slide');
            carouselElement.attr('data-bs-ride', 'carousel');

            // Crea el contenedor del carrusel fuera del bucle
            let carouselInner = $('<div class="carousel-inner"></div>');

            // Itera sobre las imágenes y crea los elementos carousel-item
            $.each(imagen.imagenes, function(j, imageName) {
                let isActive = j === 0 ? 'active' : '';

                // Crea el item carrusel
                let carouselItem = $('<div>');
                carouselItem.addClass('carousel-item ' + isActive);

                // Añade el item carrusel al carrusel interior
                carouselInner.append(carouselItem);

                // Crea el modal dinámicamente
                let modal = $('<div class="modal fade" id="modal' + imagen.id_imagen + '-' + j + '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">');
                let modalDialog = $('<div class="modal-dialog modal-dialog-centered modal-lg">');
                let modalContent = $('<div class="modal-content">');
                let modalBody = $('<div class="modal-body">');

                // Crea el elemento del carrusel
                let carouselElement = $('<div>');
                carouselElement.addClass('carousel slide');
                carouselElement.attr('id', 'carousel' + imagen.id_imagen + '-' + j);
                carouselElement.attr('data-bs-ride', 'carousel');

                // Crea el elemento carrusel interior
                let modalCarouselInner = $('<div>');
                modalCarouselInner.addClass('carousel-inner');

                 // Crea el link y el contenedor de la imagen
                 let link = $('<a>');
                 link.attr('href', '#');
                 link.attr('data-bs-toggle', 'modal');
                 link.attr('data-bs-target', '#modal' + imagen.id_imagen + '-' + j);
             
                 let imageContainer = $('<div>');
                 imageContainer.addClass('image-container');
                 imageContainer.attr('id', 'image-' + imagen.id_imagen + '-' + j);
                 imageContainer.attr('data-description', imagen.descripcion[j]);
                 imageContainer.css('background-image', 'url(\'./assets/images/posts/' + imageName + '\')');
             
                 link.append(imageContainer);
                 carouselItem.append(link);

                // Itera sobre las imagenes y crea los elementos carousel-item
                $.each(imagen.imagenes, function(k, modalImageName) {
                    let modalIsActive = k === j ? 'active' : '';

                    // Crea el item carrusel
                    let modalCarouselItem = $('<div>');
                    modalCarouselItem.addClass('carousel-item ' + modalIsActive);

                    // Crea el elemento de la imagen
                    let modalImageElement = $('<img>');
                    modalImageElement.attr('src', './assets/images/posts/' + modalImageName);
                    modalImageElement.addClass('d-block w-100');

                    // Añade la imagen al item del carrusel
                    modalCarouselItem.append(modalImageElement);

                    // Añade el item del carrusel al carrusel interior
                    modalCarouselInner.append(modalCarouselItem);
                });

                // Añade el carousel inner al elemento carousel
                carouselElement.append(modalCarouselInner);

                // Crea botones de navegación para el carrusel
                let prevButton = $('<button class="carousel-control-prev" data-bs-target="#carousel' + imagen.id_imagen + '-' + j + '" data-bs-slide="prev">');
                prevButton.html('<span class="carousel-control-prev-icon" aria-hidden="true"></span>');
                let nextButton = $('<button class="carousel-control-next" data-bs-target="#carousel' + imagen.id_imagen + '-' + j + '" data-bs-slide="next">');
                nextButton.html('<span class="carousel-control-next-icon" aria-hidden="true"></span>');

                // Añade los botones de navegación al carrusel
                carouselElement.append(prevButton);
                carouselElement.append(nextButton);

                // Añade el carrusel al cuerpo del modal
                modalBody.append(carouselElement);

                //Añade CSS al cuerpo del modal para que llene todo el modal
                modalBody.css('justify-content', 'center');
                modalBody.css('align-items', 'center');

                modalContent.append(modalBody);
                modalDialog.append(modalContent);
                modal.append(modalDialog);

                $('body').append(modal);
            });

            carouselElement.append(carouselInner);

            cardElement.append(carouselElement);

            colCardContainer.append(cardElement);

            $('#row').append(colCardContainer);

                // Crear el elemento card-body dentro de la .card
                let cardBodyElement = $('<div>').addClass('card-body');
                cardBodyElement.attr('id', 'card-body-' + imagen.id_imagen);
                cardBodyElement.append('<div class="original-description" id="DescriptionID-' + imagen.id_imagen + '">' + imagen.descripcion + '</div>');

                
                // Crear el contenedor para botones-utilidades y agregar botones
                let botonesUtilidadesContainer = $('<div>').addClass('botones-utilidades');
                botonesUtilidadesContainer.append('<button class="delete-button" data-image-id="' + imagen.id_imagen + '"><i class="bi bi-trash3 fa-6x"></i></button>');
                botonesUtilidadesContainer.append('<button class="btn-edit-description" data-image-id="' + imagen.id_imagen +'"><i class="bi bi-pencil-square"></i></button>');
                botonesUtilidadesContainer.append('<a class="download-button" href="#" data-images="' + imagen.imagenes.join(',') + '" data-description="' + imagen.descripcion + '" data-descriptions="' + imagen.descripcion + '"><i class="bi bi-download"></i></a>');
                
                // Agregar el contenedor de botones-utilidades al card-body
                cardBodyElement.append(botonesUtilidadesContainer);
        

                carouselElement.append(carouselInner);
                cardElement.append(carouselElement, cardBodyElement);
                colCardContainer.append(cardElement);
                

                // Crea el elemento .description-edit-container
                let descriptionEditContainer = $("<div>").addClass("description-edit-container").attr("id", "description-edit-" + imagen.id_imagen).css("display", "none");

                // Crea el formulario dentro del contenedor
                let formElement = $("<form>").attr("action", "editar-descripcion.php").attr("method", "POST").attr("id", "edit-form-" + imagen.id_imagen);

                // Crea el textarea dentro del formulario
                let textareaElement = $("<textarea>").attr("maxlength", "25").attr("name", "new-description").addClass("form-control").text(imagen.descripcion);

                // Crea los elementos input ocultos dentro del formulario
                let hiddenInputId = $("<input>").attr("type", "hidden").attr("name", "id_imagen").val(imagen.id_imagen);
                let hiddenInputEditDescription = $("<input>").attr("type", "hidden").attr("name", "edit-description").val(imagen.descripcion || ''); // Cambia esto según tu necesidad
                let hiddenInputPaginaActual = $("<input>").attr("type", "hidden").attr("name", "pagina_actual").val(imagen.pagina_actual); // Cambia esto según tu necesidad

                // Crea el botón "Guardar" dentro del formulario
                let saveButton = $("<button>").attr("type", "submit").addClass("btn btn-primary").attr("id","guardar-btn").text("Guardar");

                // Crea el enlace "Cancelar" dentro del formulario
                let cancelButton = $("<a>").attr("href", "#").addClass("btn btn-secondary cancel-edit").attr("data-image-id", imagen.id_imagen).text("Cancelar"); 

                // Agrega los elementos al formulario
                formElement.append(textareaElement, hiddenInputId, hiddenInputEditDescription, hiddenInputPaginaActual, saveButton, cancelButton);

                // Agrega el formulario al contenedor .description-edit-container
                descriptionEditContainer.append(formElement);

                // Agrega el contenedor al lugar adecuado en tu página (por ejemplo, a un div con un ID específico)
                colCardContainer.find('.card-body').append(descriptionEditContainer); // Cambia ".card-body" al selector adecuado dentro del contexto de colCardContainer

                // Agregar el elemento carousel al DOM (por ejemplo, a un contenedor con clase "container")
                $('#image-container').append(colCardContainer);
            });

        //Descargar Imágenes
        downloadimage();

        //activar el botón de eliminar
        BotonEliminar();
        console.log("Botón de eliminar correctamente activado");
        
        //Activa y desactiva el modo oscuro en la página
        ModoOscuro();
    }

    // Función para cargar las imágenes y luego activar la edición
    function cargarImagenesYActivarEdicion(pagina) {
        let container = $("#image-container"); // El contenedor de imágenes
        container.empty(); // Vacía el contenedor antes de cargar nuevas imágenes
    
        $.ajax({
            type: "GET",
            url: "cargar_imagenes.php?pagina=" + pagina,
            dataType: "json",
            success: function (data) {
                container.empty();

                generarBotonesPaginacion(data.totalPaginas, pagina);
                generarListadoImagenes(data);
                
            },
                error: function(error) {
                    console.error("Error en la solicitud AJAX:", error);
                    // Manejar errores de la solicitud AJAX aquí
                }
        });
    }
    //Paginación
    const generarBotonesPaginacion = (totalPaginas, paginaActual) => {
        let paginationContainer = $("#pagination-container");
        let paginationList = paginationContainer.find("ul.pagination");
        paginationList.empty();
    
        let prevButton = $("<li class='page-item'><a class='page-link' href='#'><</a></li>");
        prevButton.click(function (event) {
            event.preventDefault();
            if (paginaActual > 1) {
                cargarImagenesYActivarEdicion(paginaActual - 1);
            }
        });
    
        let nextButton = $("<li class='page-item'><a class='page-link' href='#'>></a></li>");
        nextButton.click(function (event) {
            event.preventDefault();
            if (paginaActual < totalPaginas) {
                cargarImagenesYActivarEdicion(paginaActual + 1);
            }
        });
    
        paginationList.append(prevButton);
    
        // Calcula el rango de páginas a mostrar (máximo 5 páginas)
        let startPage = Math.max(1, paginaActual - 2);
        let endPage = Math.min(startPage + 4, totalPaginas);
    
        for (let i = startPage; i <= endPage; i++) {
            let pageButton = $("<li class='page-item'><a class='page-link' href='#'>" + i + "</a></li>");
    
            if (i === paginaActual) {
                pageButton.addClass("active");
            }
    
            pageButton.click(function (event) {
                event.preventDefault();
                let newPage = parseInt($(this).text());
                cargarImagenesYActivarEdicion(newPage);
                history.pushState({}, "", "index2.php?page=" + newPage);
            });
    
            paginationList.append(pageButton);
        }
    
        paginationList.append(nextButton);
    }
    

    // Llamada inicial para generar la paginación
    cargarImagenesYActivarEdicion(1);  

    // Llama a la función para adjuntar el manejador de eventos
     GuardarDescripcionAJAX(); 


//Función para el modo oscuro de la página
function ModoOscuro() {
            $(document).ready(function() {
                // Función para guardar el estado del modo en una cookie
                function saveDarkModeStateToCookie(isDarkMode) {
                    document.cookie = `darkMode=${isDarkMode}; expires=Fri, 31 Dec 2024 23:59:59 GMT; path=/`;
                }
            
                // Función para cargar el estado del modo desde la cookie
                function loadDarkModeStateFromCookie() {
                    const cookies = document.cookie.split(';');
                    for (const cookie of cookies) {
                        const [name, value] = cookie.trim().split('=');
                        if (name === 'darkMode') {
                            return value === 'true';
                        }
                    }
                    return false;
                }
            
                // Función para habilitar el modo oscuro
                function enableDarkMode() {
                    console.log("Modo oscuro activado");
                    $("body").addClass("dark");
                    $(".card-body").addClass("dark");
                    $(".modal-body").addClass("dark");
                    $(".mode-text").text("Modo Oscuro");
                    $(".LogoVierciBlanco").css("display", "block");
                    $(".LogoVierciAzul").css("display", "none");
                    $(".page-link").css("background-color", "#18191a");
                    $(".page-link").css("color", "white");
                    $(".page-link").hover(function() {
                        $(this).css("background-color", "#0D6EFD");
                    }, function() {
                        $(this).css("background-color", "#18191a");
                    });
                }
            
                // Función para deshabilitar el modo oscuro
                function disableDarkMode() {
                    console.log("Modo oscuro desactivado");
                    $("body").removeClass("dark");
                    $(".card-body").removeClass("dark");
                    $(".modal-body").removeClass("dark");
                    $(".mode-text").text("Modo Claro");
                    $(".LogoVierciBlanco").css("display", "none");
                    $(".LogoVierciAzul").css("display", "block");
                    $(".page-link").css("background-color", "white");
                    $(".page-link").css("color", "#0D6EFD");
                    $(".page-link").hover(function() {
                        $(this).css("background-color", "#0D6EFD");
                        $(this).css("color", "white");
                    }, function() {
                        $(this).css("background-color", "#FFF");
                        $(this).css("color", "#0D6EFD");
                    });
                }
            
                // Cargar el estado del modo al cargar la página
                const isDarkMode = loadDarkModeStateFromCookie();
                if (isDarkMode) {
                    enableDarkMode();
                } else {
                    disableDarkMode();
                }
            
                // Manejar el cambio de modo
                $(document).on('click', '#darkModeSwitch', function() {
                    const isDarkMode = $("body").hasClass("dark");
                    if (isDarkMode) {
                        disableDarkMode();
                    } else {
                        enableDarkMode();
                    }
                    // Guardar el estado del modo en una cookie
                    saveDarkModeStateToCookie(!isDarkMode);
                });
                $(document).off('click', '#darkModeSwitch').on('click', '#darkModeSwitch', function() {
                    const isDarkMode = $("body").hasClass("dark");
                    if (isDarkMode) {
                        disableDarkMode();
                    } else {
                        enableDarkMode();
                    }
                    saveDarkModeStateToCookie(!isDarkMode);
                });
            });
}
// Controlador de la carga de imágenes
$(document).ready(function () {
    //la función se ejecuta cuando se hace click en el botón de cargar imágenes
    $("#btn-new-post-photo").click(function () {
        let formData = new FormData($("#uploadForm")[0]);

        $(this).prop('disabled', true);

        console.log("Iniciando carga de imágenes...");

        $.ajax({
            type: "POST",
            url: "controllers/new-post-photo.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log("Respuesta exitosa de la carga de imágenes:", response);

                if (response.success) {
                    // Elimina todas las imágenes existentes antes de agregar las nuevas
                    console.log("Eliminando imágenes existentes...");
                    $("#image-container").empty();

                    //Entonces carga las imágenes cuando se haya eliminado las anteriores
                    //para despues volver a cargar las imágenes
                    console.log("Cargando imágenes...");
                    cargarImagenesYActivarEdicion(1);

                    // Mostrar mensaje de éxito
                    console.log("Mensaje de éxito:", response.success);
                    alert(response.success); 
                    $("#btn-new-post-photo").prop('disabled', false);
                } else if (response.error) {
                    // Mostrar mensaje de error
                    console.log("Mensaje de error:", response.error);
                    alert(response.error);
                    $("#btn-new-post-photo").prop('disabled', false);
                }
            },
            error: function (error) {
                // Maneja errores aquí.
                console.log("Error al cargar imágenes:", error);
                alert("Hubo un error al subir las imágenes." + error);
            }
        });
    });
});


$("#search-form").on("submit", function(e) {
  e.preventDefault(); // Evita que el formulario se envíe de manera tradicional

  let searchTerm = $("#search-input").val();

  $.ajax({
      url: "buscar_img.php",
      method: "POST",
      data: { search: searchTerm },
      dataType: "json",
      success: function(data) {
        if (data.error === 'no_images_found') {
            alert('Ninguna imagen fue encontrada');
        } else if (data.error === 'empty_search') {
            alert('Ingresa algo en la consulta por favor');
        } else {
              // Manejar el caso de que se encontraron imágenes
              console.log("Imágenes encontradas:", data);
      
              // Limpiar la galería antes de agregar nuevas imágenes
              let container = $("#image-container"); // El contenedor de imágenes
              container.empty();
              

              generarBotonesPaginacion(data.totalPaginas, pagina=1);
              generarListadoImagenes(data);
          }
      },
      
      error: function(xhr, status, error) {
          console.log('Error en la solicitud AJAX:');
          console.log('Status:', status);
          console.log('Error:', error);
      }
  });
}); 


 //Función BotonEliminar
 function BotonEliminar() {
    $(".delete-button").click(function(event) {
        event.preventDefault();

        const paginaActual = parseInt($('.page-item.active .page-link').text()); // Captura la página actual

        const imageId = $(this).data("image-id");
        const confirmacion = confirm("¿Quieres eliminar la imagen?");

        if (confirmacion) {
            $.ajax({
                url: "eliminar-imagen.php",
                method: "POST",
                data: { id_imagen: imageId },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $(`#col${imageId}`).remove(); // Elimina la imagen

                        // Recalcular la página actual después de eliminar

                        const newPage = paginaActual;

                        // Volver a cargar la página correspondiente a la imagen eliminada
                        cargarImagenesYActivarEdicion(newPage);

                        setTimeout(function () {
                            alert("Imagen Eliminada Exitosamente.");
                        }, 100);
                    } else {
                        alert("Error al eliminar la imagen: " + response.error);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    });
}
});

//Cambios revisados con el repo de Lucas.


function GuardarDescripcionAJAX() {
    $(document).ready(function() {
        // Agregar el controlador de eventos una vez en el documento listo
        $('body').on('click', '#guardar-btn', function(event) {
            event.preventDefault(); // Evitar que el formulario se envíe automáticamente
    
            // Obtener los valores del formulario
            let form = $(this).closest('form');
            let newDescription = form.find('textarea[name="new-description"]').val();
            let imageId = form.find('input[name="id_imagen"]').val();
            let paginaActual = form.find('input[name="pagina_actual"]').val();
    
            console.log('newDescription:', newDescription);
            console.log('imageId:', imageId);
            console.log('paginaActual:', paginaActual);
    
            // Realizar la solicitud AJAX
            $.ajax({
                url: 'editar-descripcion.php',
                type: 'POST',
                data: {
                    'new-description': newDescription,
                    'id_imagen': imageId,
                    'pagina_actual': paginaActual
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        // Actualiza la descripción en la página sin recargarla
                        let originalDescription = form.closest('.card-body').find('.original-description');
                        originalDescription.text(newDescription);
    
                        // Restaura la visibilidad de la descripción original y los botones
                        form.closest('.description-edit-container').hide();
                        originalDescription.show();
                        form.closest('.card-body').find('.botones-utilidades').show();
    
                        // Muestra un mensaje de éxito
                        alert(response.message);
                    } else {
                        // Muestra un mensaje de error
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.log('Error en la solicitud AJAX:');
                    console.log('xhr:', xhr);
                    console.log('Status:', status);
                    console.log('Error:', error);
                }
            });
        });
    });    
//Eventos para la edicición de la descripción
$(document).ready(function() {
    $(document).on("click", ".btn-edit-description", function() {
        console.log("Botón de edición de descripción clickeado");

        let imageId = $(this).data("image-id");
        let cardBody = $(this).closest('.card-body');

        cardBody.find('.botones-utilidades, .original-description').hide();

        let descriptionEditContainer = $(`#description-edit-${imageId}`);

        descriptionEditContainer.addClass('visible');
        descriptionEditContainer.css('display', 'block');
    });

    $(document).on("click", ".cancel-edit", function(event) {
        console.log("Botón de cancelación de descripción clickeado");
        event.preventDefault()
        let imageId = $(this).data("image-id");
        let cardBody = $(this).closest('.card-body');

        $(`#description-edit-${imageId}`).hide();
        cardBody.find('.botones-utilidades, .original-description').show();

    });
});

}