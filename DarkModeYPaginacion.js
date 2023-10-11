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
        }
    }

    // Cargar el estado del modo al cargar la página
    loadDarkModeState();

    // Manejar el cambio de modo
    $("#darkModeSwitch").click(function() {
        const isDarkMode = $("body").hasClass("dark");
        $("body").toggleClass("dark");
        $("#darkModeSwitch").prop("checked", !isDarkMode);
        // Guardar el estado del modo en el almacenamiento local
        saveDarkModeState(!isDarkMode);
        // Redirigir a la misma página con el estado del modo como parámetro en la URL
        const currentPage = window.location.href;
        const newUrl = currentPage + (currentPage.includes("?") ? "&" : "?") + "darkMode=" + (!isDarkMode ? "1" : "0");
        window.location.href = newUrl;
    });


// Manejar la paginación
$(".pagination a").click(function(e) {
    e.preventDefault(); // Evitar que el enlace navegue a otra página

    var pagina = $(this).text(); // Obtener el número de página
    $.ajax({
        url: "./cargar_imagenes.php",
        method: "GET",
        data: { pagina: pagina },
        success: function(data) {
            // Actualiza el contenido del contenedor de imágenes con el nuevo contenido
            $("#image-container").html(data);
        }
    });
});
});