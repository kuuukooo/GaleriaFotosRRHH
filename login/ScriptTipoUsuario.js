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
