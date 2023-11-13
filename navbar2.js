document.addEventListener("DOMContentLoaded", function() {
  const body = document.querySelector('body'),
    sidebar = body.querySelector('nav'),
    toggle = body.querySelector(".toggle"),
    searchBtn = body.querySelector(".search-box"),
    modeSwitch = document.querySelector("#darkModeSwitch"),
    mainContent = document.querySelector(".main-content");

  function updateMargin() {
    if (sidebar.classList.contains("close")) {
      mainContent.style.marginLeft = '0';
    } else {
      mainContent.style.marginLeft = '250px';
    }
  }

  function applySidebarState() {
    const sidebarState = getCookie("sidebarState");
    if (sidebarState === "open") {
      sidebar.classList.remove("close");
      updateMargin(); // Actualizar la posición de main-content
    } else {
      sidebar.classList.add("close");
      updateMargin(); // Actualizar la posición de main-content
    }
  }

  // Función para establecer una cookie con un nombre, valor y duración
  function setCookie(name, value, days) {
    const expires = new Date();
    expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
  }
  
  // Función para guardar el estado de la sidebar en cookies
  function saveSidebarState() {
    const sidebarState = sidebar.classList.contains("close") ? "closed" : "open";
    setCookie("sidebarState", sidebarState, 365);
  }

  function getCookie(name) {
    const cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
      const cookie = cookies[i].trim();
      const [cookieName, cookieValue] = cookie.split('=');
  
      if (cookieName === name) {
        return cookieValue;
      }
    }
    return null;
  }

  // Llama a applyDarkMode() y applySidebarState() después de cargar la página para establecer el modo y estado de la sidebar correctos.
  applySidebarState();

  toggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
    updateMargin(); // Actualizar la posición de main-content
    saveSidebarState();
  });
  
  searchBtn.addEventListener("click", () => {
    sidebar.classList.remove("close");
    updateMargin(); // Actualizar la posición de main-content
    saveSidebarState();
  });

  // Función para observar cambios en el atributo class de la barra lateral
  function observeSidebarChanges() {
    const observer = new MutationObserver(() => {
      saveSidebarState();
    });

    const config = { attributes: true, attributeFilter: ['class'] };
    observer.observe(sidebar, config);
  }

  // Observar cambios en la barra lateral y actualizar la cookie en tiempo real
  observeSidebarChanges();
});
//Código comparado con el de Lucas.