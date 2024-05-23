$(document).ready(function() {
    // Función para guardar el estado del modo en una cookie
    function saveDarkModeStateToCookie(isDarkMode) {
        console.log("Saving dark mode state to cookie: ", isDarkMode);
        document.cookie = `darkMode=${isDarkMode}; expires=Fri, 31 Dec 2024 23:59:59 GMT; path=/`;
    }

    // Función para cargar el estado del modo desde la cookie
    function loadDarkModeStateFromCookie() {
        console.log("Loading dark mode state from cookie");
        const cookies = document.cookie.split(';');
        for (const cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === 'darkMode') {
                console.log("Found dark mode cookie: ", value);
                return value === 'true';
            }
        }
        console.log("No dark mode cookie found");
        return false;
    }

        // Función para habilitar el modo oscuro
        function enableDarkMode() {
            console.log("Enabling dark mode");
            $("body").addClass("dark");
            $(".mode-text").text("Modo Oscuro");
            $(".LogoVierciBlanco").css("display", "block");
            $(".LogoVierciAzul").css("display", "none");
            $("label").css("color", "rgb(89, 106, 137)");
            $(".modal-header").css("background-color", "#18191A");
            $(".modal-body").css("background-color", "#18191A");
            $(".modal-footer").css("background-color", "#373838");
            $(".btn-close").css("background-color", "red");
        }

    // Función para deshabilitar el modo oscuro
    function disableDarkMode() {
        console.log("Disabling dark mode");
        $("body").removeClass("dark");
        $(".modal-body").removeClass("dark");
        $(".mode-text").text("Modo Claro");
        $(".LogoVierciBlanco").css("display", "none");
        $(".LogoVierciAzul").css("display", "block");
        $("label").css("color", "rgb(89, 106, 137)")
        $(".modal-header").css("background-color", "#ffffff");
        $(".modal-body").css("background-color", "#ffffff");
        $(".modal-footer").css("background-color", "#ecf0f1");
    }

    // Cargar el estado del modo al cargar la página
    const isDarkMode = loadDarkModeStateFromCookie();
    console.log("Initial dark mode state: ", isDarkMode);
    if (isDarkMode) {
        enableDarkMode();
    } else {
        disableDarkMode();
    }

    // Manejar el cambio de modo
    $(document).on('click', '#darkModeSwitch', function() {
        const isDarkMode = $("body").hasClass("dark");
        console.log("Dark mode switch clicked. Current state: ", isDarkMode);
        if (isDarkMode) {
            disableDarkMode();
        } else {
            enableDarkMode();
        }
        saveDarkModeStateToCookie(!isDarkMode);
    });
    $(document).off('click', '#darkModeSwitch').on('click', '#darkModeSwitch', function() {
        const isDarkMode = $("body").hasClass("dark");
        console.log("Dark mode switch clicked. Current state: ", isDarkMode);
        if (isDarkMode) {
            disableDarkMode();
        } else {
            enableDarkMode();
        }
        saveDarkModeStateToCookie(!isDarkMode);
    });
});