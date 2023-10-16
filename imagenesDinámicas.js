$(document).ready(function() {
    console.log("Script de edición de Cargado de Imagenes cargado.");

    // Función para cargar las imágenes y luego activar la edición
    function cargarImagenesYActivarEdicion(pagina) {
        console.log("Solicitando imágenes para la página: " + pagina);
        $.ajax({
            url: "cargar_imagenes.php",
            method: "GET",
            data: {
                pagina: pagina
            },
            dataType: "json",
            success: function(data) {
                console.log("Respuesta exitosa de la solicitud AJAX:", data);

                    // Iterar sobre los datos de las imágenes y agregarlas al contenedor
                    data.forEach(function(imagen, index) {
                                                         // Crear un nuevo elemento .col y .card con jQuery
                    var colCardContainer = $('<div>');
                    colCardContainer.addClass('col');
                    colCardContainer.attr('id', 'col' + imagen.id_imagen);

                    var cardElement = $('<div>');
                    cardElement.addClass('card');

                    // Crear un nuevo elemento div con jQuery
                    var carouselElement = $('<div>');

                    // Asignar el ID dinámicamente al elemento carousel
                    carouselElement.attr('id', 'carousel' + imagen.id_imagen);
                    carouselElement.addClass('carousel slide');
                    carouselElement.attr('data-bs-ride', 'carousel');

                    // Crear el contenedor del carrusel fuera del bucle
                    var carouselInner = $('<div class="carousel-inner"></div>');

                        // Recorrer las imágenes y crear los elementos de carousel-item
                        $.each(imagen.imagenes, function(j, imageName) {
                            var isActive = j === 0 ? 'active' : '';
                            
                            // Create the carousel item
                            var carouselItem = $('<div>');
                            carouselItem.addClass('carousel-item ' + isActive);
                        
                            // Create the link and the image container
                            var link = $('<a>');
                            link.attr('href', '#');
                            link.attr('data-bs-toggle', 'modal');
                            link.attr('data-bs-target', '#modal' + imagen.id_imagen + '-' + j);
                        
                            var imageContainer = $('<div>');
                            imageContainer.addClass('image-container');
                            imageContainer.attr('id', 'image-' + imagen.id_imagen + '-' + j);
                            imageContainer.attr('data-description', imagen.descripcion[j]);
                            imageContainer.css('background-image', 'url(\'./assets/images/posts/' + imageName + '\')');
                        
                            link.append(imageContainer);
                            carouselItem.append(link);
                        
                            carouselInner.append(carouselItem);
                        
                            // Create the modal dynamically
                            var modal = $('<div class="modal fade" id="modal' + imagen.id_imagen + '-' + j + '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">');
                            var modalDialog = $('<div class="modal-dialog modal-dialog-centered modal-lg">');
                            var modalContent = $('<div class="modal-content">');
                            var modalBody = $('<div class="modal-body">');

                            // Create the element <img> inside the modal
                            var imgElement = $('<img>');
                            imgElement.attr('src', './assets/images/posts/' + imageName); // Set the source of the image
                            imgElement.addClass('img-fluid');

                            // Create navigation buttons
                            var prevButton = $('<button class="carousel-control-prev" data-bs-slide="prev">');
                            prevButton.html('<span class="carousel-control-prev-icon" aria-hidden="true"></span>');
                            var nextButton = $('<button class="carousel-control-next" data-bs-slide="next">');
                            nextButton.html('<span class="carousel-control-next-icon" aria-hidden="true"></span>');
                            

                            // Add the image to the modal body
                            modalBody.append(imgElement);
                            modalBody.append(prevButton);
                            modalBody.append(nextButton);

                            // Add CSS to the modal body to make it fill the entire modal
                            modalBody.css('display', 'flex');
                            modalBody.css('justify-content', 'center');
                            modalBody.css('align-items', 'center');

                            modalContent.append(modalBody);
                            modalDialog.append(modalContent);
                            modal.append(modalDialog);
 
                            // Add the modal to the end of the document
                            $('body').append(modal);    
                        });  
                                    

                        // Crear el elemento card-body dentro de la .card
                        var cardBodyElement = $('<div>').addClass('card-body');
                        cardBodyElement.attr('id', 'card-body-' + imagen.id_imagen);
                        cardBodyElement.append('<div class="original-description" id="DescriptionID-' + imagen.id_imagen + '">' + imagen.descripcion + '</div>');

                        
                        // Crear el contenedor para botones-utilidades y agregar botones
                        var botonesUtilidadesContainer = $('<div>').addClass('botones-utilidades');
                        botonesUtilidadesContainer.append('<button class="delete-button" data-image-id="' + imagen.id_imagen + '"><i class="bi bi-trash3 fa-6x"></i></button>');
                        botonesUtilidadesContainer.append('<button class="btn-edit-description" data-image-id="' + imagen.id_imagen +'"><i class="bi bi-pencil-square"></i></button>');
                        botonesUtilidadesContainer.append('<a class="download-button" href="#" data-images="' + imagen.imagenes.join(',') + '" data-description="' + imagen.descripcion + '" data-descriptions="' + imagen.descripcion + '"><i class="bi bi-download"></i></a>');
                        
                        // Agregar el contenedor de botones-utilidades al card-body
                        cardBodyElement.append(botonesUtilidadesContainer);
                

                        carouselElement.append(carouselInner);
                        cardElement.append(carouselElement, cardBodyElement);
                        colCardContainer.append(cardElement);
                        

                        // Crea el elemento .description-edit-container
                        var descriptionEditContainer = $("<div>").addClass("description-edit-container").attr("id", "description-edit-" + imagen.id_imagen).css("display", "none");

                        // Crea el formulario dentro del contenedor
                        var formElement = $("<form>").attr("action", "editar-descripcion.php").attr("method", "POST").attr("id", "edit-form-" + imagen.id_imagen);

                        // Crea el textarea dentro del formulario
                        var textareaElement = $("<textarea>").attr("maxlength", "25").attr("name", "new-description").addClass("form-control").text(imagen.descripcion);

                        // Crea los elementos input ocultos dentro del formulario
                        var hiddenInputId = $("<input>").attr("type", "hidden").attr("name", "id_imagen").val(imagen.id_imagen);
                        var hiddenInputEditDescription = $("<input>").attr("type", "hidden").attr("name", "edit-description").val(imagen.descripcion || ''); // Cambia esto según tu necesidad
                        var hiddenInputPaginaActual = $("<input>").attr("type", "hidden").attr("name", "pagina_actual").val(imagen.pagina_actual); // Cambia esto según tu necesidad

                        // Crea el botón "Guardar" dentro del formulario
                        var saveButton = $("<button>").attr("type", "submit").addClass("btn btn-primary").attr("id","guardar-btn").text("Guardar");

                        // Crea el enlace "Cancelar" dentro del formulario
                        var cancelButton = $("<a>").attr("href", "#").addClass("btn btn-secondary cancel-edit").attr("data-image-id", imagen.id_imagen).text("Cancelar"); 

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

                // Llama a la función para adjuntar el manejador de eventos
                GuardarAJAX(); 
            },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud AJAX:", error);
                    // Manejar errores de la solicitud AJAX aquí
                }
        });
    }

    // Cargar imágenes y activar edición en el documento listo
    cargarImagenesYActivarEdicion(1);  

// Controlador de la carga de imágenes
$(document).ready(function () {
    $("#btn-new-post-photo").click(function () {
        var formData = new FormData($("#uploadForm")[0]);

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

                    //Carga las imágenes que se quieran cargar
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
});


        // Manejar la paginación cuando se hace clic en los enlaces de paginación
        $(".pagination a").click(function(e) {
            e.preventDefault();
            var pagina = $(this).text();
            cargarImagenesYActivarEdicion(pagina);
        });

        // Manejar la búsqueda
        $("#search-form").on("submit", function(e) {
            e.preventDefault(); // Evita que el formulario se envíe de manera tradicional
            var searchTerm = $("#search-input").val().toLowerCase();

            // Itera sobre todas las imágenes y muestra u oculta según la búsqueda
            $(".col").each(function() {
                var descripcion = $(this).find('.original-description').text().toLowerCase();
                if (descripcion.includes(searchTerm)) {
                    $(this).show(); // Mostrar imágenes que coinciden con la búsqueda
                } else {
                    $(this).hide(); // Ocultar imágenes que no coinciden con la búsqueda
                }
            });
        });

    //Función BotonEliminar
    function BotonEliminar() {
    $(".delete-button").click(function(event) {
        event.preventDefault(); // Evitar que el enlace navegue a otra página

        const imageId = $(this).data("image-id");

        // Mostrar una alerta de confirmación
        const confirmacion = confirm("¿Quieres eliminar la imagen?");

        if (confirmacion) {
            $.ajax({
                url: "eliminar-imagen.php",
                method: "POST",
                data: { id_imagen: imageId },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // Ocultar tanto el image-container como el card-body específicos
                        $(`#col${imageId}`).remove();
                        
                        // Eliminación exitosa, puedes mostrar un mensaje de éxito en la página
                        //Añadido un delay pequeño para que deje mostrar que se eliminó la foto
                        setTimeout(function () {
                            alert("Imágen Eliminada Exitosamente.");
                        }, 100);
                    } else {
                        // Error al eliminar, puedes mostrar un mensaje de error en la página
                        alert("Error al eliminar la imagen: " + response.error);
                    }
                },
                error: function(error) {
                    // Manejar errores de la solicitud AJAX aquí
                    console.log(error);
                }
            });
        }
    });
}
function GuardarAJAX() {
    $('body').on('click', '#guardar-btn', function(event) {
    event.preventDefault(); // Evitar que el formulario se envíe automáticamente

    // Obtener los valores del formulario
    var form = $(this).closest('form');
    var newDescription = form.find('textarea[name="new-description"]').val();
    var imageId = form.find('input[name="id_imagen"]').val();
    var paginaActual = form.find('input[name="pagina_actual"]').val();

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
                var originalDescription = form.closest('.card-body').find('.original-description');
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
            console.log('Status:', status);
            console.log('Error:', error);
        }
    });
});
    // Función para activar la edición
    // Eventos click para edición y cancelación
    $(document).ready(function() {
        $(document).on("click", ".btn-edit-description", function() {
            console.log("Botón de edición de descripción clickeado");

            var imageId = $(this).data("image-id");
            var cardBody = $(this).closest('.card-body'); // Define cardBody aquí

            var descriptionEditContainer = $(`#description-edit-${imageId}`);

            // Agregar la clase 'visible' al description-edit-container
            descriptionEditContainer.addClass('visible');


            // Ocultar descripción original y botones
            cardBody.find('.botones-utilidades').css("display", "none");
            cardBody.find('.original-description').css("display","none");  

            // Mover el elemento al div.card-body correspondiente
            cardBody.append(descriptionEditContainer);

            descriptionEditContainer.toggle();
        });

        $(document).on("click", ".cancel-edit", function(e) {
            e.preventDefault(); // Evitar que el enlace navegue a otra página
            var imageId = $(this).data("image-id");
            var descriptionEditContainer = $(`#description-edit-${imageId}`);
            var cardBody = descriptionEditContainer.closest('.card-body');

            // Ocultar el formulario de edición y mostrar la descripcións original y botones
            descriptionEditContainer.hide();
            cardBody.find('.original-description').show();
            cardBody.find('.botones-utilidades').show();
        });
    });
}