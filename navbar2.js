document.addEventListener("DOMContentLoaded", function() {
  const body = document.querySelector('body'),
    sidebar = body.querySelector('nav'),
    toggle = body.querySelector(".toggle"),
    searchBtn = body.querySelector(".search-box"),
    modeSwitch = document.querySelector("#darkModeSwitch"),
    modeText = document.querySelector(".mode-text"),
    mainContent = document.querySelector(".main-content");
  // Agregar un event listener al interruptor de modo oscuro
  modeSwitch.addEventListener("click", () => {
    toggleDarkMode();
  });

  function updateMargin() {
    if (sidebar.classList.contains("close")) {
      mainContent.style.marginLeft = '0';
    } else {
      mainContent.style.marginLeft = '250px';
    }
  }
  
  function updateModalBackground() {
    const cardBodies = document.querySelectorAll(".card-body");
  
    cardBodies.forEach((cardBody) => {
      if (body.classList.contains("dark")) {
        cardBody.style.backgroundColor = '#18191a'; // Color oscuro
      } else {
        cardBody.style.backgroundColor = '#fff'; // Color claro (ajusta según sea necesario)
      }
    });
  }

  function applyDarkMode() {
    const darkMode = getCookie("darkMode");
    if (darkMode === "enabled") {
      body.classList.add("dark");
      updateModalBackground();
      modeText.innerText = "Modo Claro";
    } else {
      body.classList.remove("dark");
      updateModalBackground();
      modeText.innerText = "Modo Oscuro";
    }
  }

  function applySidebarState() {
    const sidebarState = getCookie("sidebarState");
    if (sidebarState === "open") {
      sidebar.classList.remove("close");
    } else {
      sidebar.classList.add("close");
    }
  }

  // Llama a applyDarkMode() y applySidebarState() después de cargar la página para establecer el modo y estado de la sidebar correctos.
  applyDarkMode();
  applySidebarState();

  toggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
    updateMargin();
    saveSidebarState();
  });
  
  searchBtn.addEventListener("click", () => {
    sidebar.classList.remove("close");
    updateMargin();
    saveSidebarState();
  });



  // Función para establecer una cookie con un nombre, valor y duración
  function setCookie(name, value, days) {
    const expires = new Date();
    expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
  }
  
    // Función para cambiar el modo y guardar en cookies
    function toggleDarkMode() {
      const currentMode = body.classList.contains("dark") ? "enabled" : "disabled";
      setCookie("darkMode", currentMode, 365);
    
      // Si la clase "initial-dark-mode" está presente, elimínala después de 2 segundos
      if (body.classList.contains("initial-dark-mode")) {
        setTimeout(() => {
          body.classList.remove("initial-dark-mode");
        }, 2000); // Espera 2 segundos antes de eliminar la clase
    
        // Actualiza la cookie para indicar que la animación de transición ya ocurrió
        setCookie("darkModeAnimationCompleted", "true", 365);
      }
    
      applyDarkMode();
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
  });    
