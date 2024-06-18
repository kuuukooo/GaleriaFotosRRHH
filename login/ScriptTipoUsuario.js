//Get the cookie "tipo_usuario" from the browser, based on the info given by the cookie
//either "Admin" or "Usuario", if the given value is "Usuario" then the code gets the
//document class .delete-button and removes it by setting its display to none
//and also gets the document class .download-button and also removes it by setting its 
//display to none, if the given value is "Admin" then the code does nothing.
function getCookie(name) {
    return new Promise((resolve, reject) => {
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length == 2) resolve(parts.pop().split(";").shift());
        else reject('Cookie not found');
    });
}

async function checkCookie() {
    try {
        var tipo_usuario = await getCookie("tipo_usuario");
        if (tipo_usuario == "Usuario") {
            var deleteButton = document.getElementsByClassName("delete-button");

            for (var i = 0; i < deleteButton.length; i++) {
                deleteButton[i].style.display = "none";
            }
            var downloadButton = document.getElementsByClassName("btn-edit-description");
            for (var i = 0; i < downloadButton.length; i++) {
                downloadButton[i].style.display = "none";
            }
            var publicButton = document.getElementsByClassName("btn-publicar");
            for (var i = 0; i < publicButton.length; i++) {
                publicButton[i].style.display = "none";
            }
            let deleteButtonAlbum = document.querySelectorAll('[data-ngy2action="custom1"]');
            for (let i = 0; i < deleteButtonAlbum.length; i++) {
                deleteButtonAlbum[i].style.display = "none";
            }
            let editButtonAlbum = document.querySelectorAll('[data-ngy2action="custom2"]');
            for (let i = 0; i < editButtonAlbum.length; i++){
                editButtonAlbum[i].style.display = "none";
            }
            let publicButtonAlbum = document.querySelectorAll('[data-ngy2action="custom4"]');
            for (let i = 0; i < publicButtonAlbum.length; i++){
                publicButtonAlbum[i].style.display = "none";
            }
            let herramientasAdmin = document.getElementsByClassName('fab')
            for (let i = 0; i < herramientasAdmin.length; i++){
                herramientasAdmin[i].style.display = "none";
            }
            let herramientasAdminMenu = document.getElementsByClassName('fab-menu')
            for (let i = 0; i < herramientasAdminMenu.length; i++){
                herramientasAdminMenu[i].style.display = "none";
            }
            let herramientasAdminCheckbox = document.getElementsByClassName('checkboxHerramienta')
            for (let i = 0; i < herramientasAdminCheckbox.length; i++){
                herramientasAdminCheckbox[i].style.display = "none";
            }

            let CrearAlbumBtn = document.getElementsByClassName('CrearAlbum')
            for (let i = 0; i < CrearAlbumBtn.length; i++){
                CrearAlbumBtn[i].style.display = "none";

            }

            let SubirImagenDiv = document.getElementsByClassName('FormularioSubirImg')
            for (let i = 0; i < SubirImagenDiv.length; i++){
                SubirImagenDiv[i].style.display = "none";
            }

        } else {
            $("#DashboardMenu").show();
            $("#GaleriaPublica").show();
        }

        
    } catch (error) {
        console.error(error);
    }
}

function observeDOM() {
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' || mutation.type === 'subtree') {
                // Call checkCookie when the DOM changes
                checkCookie();
            }
        });
    });

    var config = { childList: true, subtree: true };
    observer.observe(document.body, config);
}

window.onload = function() {
    // Call checkCookie immediately
    checkCookie();

    // Start observing the DOM for changes
    observeDOM();
}
