$(document).ready(function() {
    // Función para guardar el estado del modo en el almacenamiento local
    function saveDarkModeState(isDarkMode) {
        localStorage.setItem('darkMode', isDarkMode);
    }

    // Función para cargar el estado del modo desde el almacenamiento local
    function loadDarkModeState() {
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode) {
            $("body").addClass("dark");
            $("#darkModeSwitch").prop("checked", true);
            // Cambiar el color de los card-body y otras clases
            $(".card-body, .modal-body").addClass("dark");
        }
    }

    // Cargar el estado del modo al cargar la página
    loadDarkModeState();

    // Manejar el cambio de modo
    $("#darkModeSwitch").click(function() {
        const isDarkMode = $("body").hasClass("dark");
        $("body").toggleClass("dark");
        $("#darkModeSwitch").prop("checked", !isDarkMode);

        // Cambiar el color de los card-body y otras clases
        $(".card-body, .modal-body").toggleClass("dark");
        // Cambiar el texto dentro del span con la clase mode-text que dice "Modo Oscuro"
        $(".mode-text").text(isDarkMode ? "Modo Oscuro" : "Modo Claro");
        // Guardar el estado del modo en el almacenamiento local
        saveDarkModeState(!isDarkMode);

        // // Redirigir a la misma página con el estado del modo como parámetro en la URL
        // const currentPage = window.location.href;
        // const newUrl = currentPage + (currentPage.includes("?") ? "&" : "?") + "darkMode=" + (!isDarkMode ? "1" : "0");
        // window.location.href = newUrl;
    });
});
