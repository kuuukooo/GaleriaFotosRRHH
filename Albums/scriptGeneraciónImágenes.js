// Version Prueba
$(document).ready(function() {
    //Event Listener que controla el click para cerrar el dialog 
    const dialog = document.querySelector('dialog');
    const wrapper = document.querySelector('.wrapper');

    dialog.addEventListener("click", (e) => !wrapper.contains(e.target) && dialog.close());

    console.log("Corriendo prueba.");

    const generarListadoImagenes = (data) => {
        data.albumes.forEach(function(album) {
            let galleryPhotoContainer = $('<a href="" data-ngkind="album" data-ngid="' + album.id_album + '" data-ngthumb="fotos/' + album.miniatura + '">' + album.descripcion + '</a>');
            let galleryPhoto = $('<a href="" data-ngid="' + album.id_photo + '" data-ngalbumid="' + album.id_album + '" data-ngthumb="' + album.miniatura + '">' + album.descripcion + '</a>');

            $('body').append(galleryPhotoContainer);
        });
    }
});

//Función para mostrar el dialog
function showDialog(show) {
    const dialog = document.querySelector('dialog');
    show ? dialog.showModal() : dialog.close();
}
