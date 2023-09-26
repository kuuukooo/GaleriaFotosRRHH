 function downloadimage() {
    // Obtener todos los botones de descarga
    const downloadButtons = document.querySelectorAll(".download-button");

    // Agregar un controlador de eventos a cada botón de descarga
    downloadButtons.forEach(function(button) {
        button.addEventListener("click", async function(event) {
            event.preventDefault();

            // Obtener la descripción del artículo
            const description = this.getAttribute("data-description");
            console.log("Descripción:", description);

            // Obtener la lista de nombres de imágenes del atributo data-images
            const imageList = this.getAttribute("data-images");
            const imagesArray = imageList.split(',');

            // Crear un objeto FormData para enviar nombres de archivo y descripciones al servidor (PHP)
            const formData = new FormData();

            // Crear un archivo ZIP en el cliente
            const zip = new JSZip();

            // Contador para identificar las imágenes
            let imageCounter = 1;

            // Promesas para cargar todas las imágenes
            const imagePromises = imagesArray.map(async function(imageName) {
                // Renombrar la imagen con la descripción y un contador
                const imageDescription = description;
                const uniqueIdentifier = `Imagen ${imageCounter}`;
                const newImageName = `${imageDescription}_${uniqueIdentifier}.${imageName.split('.').pop()}`;
                console.log("Nuevo nombre de la imágen: ", newImageName);

                // Incrementar el contador para la siguiente imagen
                imageCounter++;

                // Obtener la imagen
                console.log(`Cargando imagen: ${imageName}`);
                const imageBlob = await fetch(`./assets/images/posts/${imageName}`).then(res => {
                    console.log(`Imagen cargada: ${imageName}`);    
                    return res.blob();
                });

                // Agregar la imagen renombrada al archivo ZIP en el cliente
                zip.file(newImageName, imageBlob);
                console.log(`Imagen agregada al ZIP: ${newImageName}`);

                // Agregar la descripción de la imagen al FormData
                formData.append("image_descriptions[]", imageDescription);
                console.log(`Descripción de la imagen agregada al FormData: ${imageDescription}`);
            });

            // Esperar a que todas las promesas se resuelvan
            await Promise.all(imagePromises);

            // Generar el archivo ZIP en el cliente y descargarlo
            zip.generateAsync({ type: "blob" }).then(function(content) {
                // Configurar el elemento de anclaje para descargar el archivo ZIP
                const anchor = document.createElement("a");
                const url = window.URL.createObjectURL(content);
                anchor.href = url;
                anchor.download = `${description}.zip`;
                console.log("Nombre de archivo descargado:", `${description}.zip`);

                // Simular el clic en el elemento de anclaje para iniciar la descarga
                anchor.click();

                // Liberar el objeto URL
                window.URL.revokeObjectURL(url);
            });
        });
    });
};
