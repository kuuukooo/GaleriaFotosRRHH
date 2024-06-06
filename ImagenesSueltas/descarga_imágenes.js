/**
 * La función `downloadimage` descarga imágenes y sus descripciones, 
 * las comprime en un archivo zip e inicia la descarga del archivo zip cuando se hace clic en un botón de descarga.
 */
function downloadimage() {
    // Selecciona todos los elementos con la clase "download-button"
    let buttons = document.querySelectorAll(".download-button");

    // Itera sobre cada botón
    buttons.forEach(function (button) {
        // Añade un evento 'click' a cada botón
        button.addEventListener("click", async function (event) {
            // Previene el comportamiento por defecto del evento
            event.preventDefault();

            // Obtiene la descripción del atributo 'data-description'
            let description = this.getAttribute("data-description");
            console.log("Descripción:", description);

            // Obtiene la lista de imágenes del atributo 'data-images'
            let images = this.getAttribute("data-images").split(",");

            // Crea un objeto FormData para almacenar descripciones de imágenes
            let formData = new FormData();

            // Crea un nuevo archivo ZIP usando JSZip
            let zip = new JSZip();

            // Contador para las imágenes
            let imageCounter = 1;

            // Mapea las imágenes a promesas que se resolverán al descargar cada imagen
            let imagePromises = images.map(async function (image) {
                // Nuevo nombre para la imagen usando la descripción y el contador
                let newImageName = `${description}_Imagen ${imageCounter}.${image.split(".").pop()}`;
                console.log("Nuevo nombre de la imagen:", newImageName);

                // Incrementa el contador de imágenes
                imageCounter++;

                // Carga la imagen desde la ruta especificada
                console.log(`Cargando imagen: ${image}`);
                let imageBlob = await fetch(`./assets/images/posts/${image}`).then((response) => {
                    console.log(`Imagen cargada: ${image}`);
                    return response.blob();
                });

                // Añade la imagen al archivo ZIP
                zip.file(newImageName, imageBlob);
                console.log(`Imagen agregada al ZIP: ${newImageName}`);

                // Añade la descripción de la imagen al FormData
                formData.append("image_descriptions[]", description);
                console.log(`Descripción de la imagen agregada al FormData: ${description}`);
            });

            // Espera a que todas las imágenes se hayan descargado y añadido al ZIP
            await Promise.all(imagePromises);

            // Genera el archivo ZIP y lo descarga
            zip.generateAsync({ type: "blob" }).then(function (zipBlob) {
                // Crea un enlace temporal para descargar el archivo ZIP
                let tempLink = document.createElement("a");
                let zipUrl = window.URL.createObjectURL(zipBlob);

                tempLink.href = zipUrl;
                tempLink.download = `${description}.zip`;
                console.log("Nombre de archivo descargado:", `${description}.zip`);

                tempLink.click();

                // Revoca el objeto URL temporal
                window.URL.revokeObjectURL(zipUrl);
            });
        });
    });
}
