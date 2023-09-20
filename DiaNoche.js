/**
 *  Light Switch @version v0.1.4
 */

(function () {
    let lightSwitch = document.getElementById('lightSwitch');
    if (!lightSwitch) {
      return;
    } 

    /**
     * @function darkmode
     * @summary: changes the theme to 'dark mode' and save settings to local stroage.
     * Basically, replaces/toggles every CSS class that has '-light' class with '-dark'
     */
    function darkMode() {
      document.querySelectorAll('.bg-light').forEach((element) => {
        element.className = element.className.replace(/-light/g, '-dark');
      });
  
      document.querySelectorAll('.link-dark').forEach((element) => {
        element.className = element.className.replace(/link-dark/, 'text-white');
      });

      document.body.classList.add('bg-dark');
  
      if (document.body.classList.contains('text-dark')) {
        document.body.classList.replace('text-dark', 'text-light');
      } else {
        document.body.classList.add('text-light');
      }
  
      // Tables
      var tables = document.querySelectorAll('table');
      for (var i = 0; i < tables.length; i++) {
        // add table-dark class to each table
        tables[i].classList.add('table-dark');
      }
  
      // set light switch input to true
      if (!lightSwitch.checked) {
        lightSwitch.checked = true;
      }
      localStorage.setItem('lightSwitch', 'dark');
    }
  
    /**
     * @function lightmode
     * @summary: changes the theme to 'light mode' and save settings to local storage.
     */
    function lightMode() {
      document.querySelectorAll('.bg-dark').forEach((element) => {
        element.className = element.className.replace(/-dark/g, '-light');
      });
  
      document.querySelectorAll('.text-white').forEach((element) => {
        element.className = element.className.replace(/text-white/, 'link-dark');
      });
  
      document.body.classList.add('bg-light');
  
      if (document.body.classList.contains('text-light')) {
        document.body.classList.replace('text-light', 'text-dark');
      } else {
        document.body.classList.add('text-dark');
      }
  
      // Tables
      var tables = document.querySelectorAll('table');
      for (var i = 0; i < tables.length; i++) {
        if (tables[i].classList.contains('table-dark')) {
          tables[i].classList.remove('table-dark');
        }
      }
  
      if (lightSwitch.checked) {
        lightSwitch.checked = false;
      }
      localStorage.setItem('lightSwitch', 'light');
    }
  /**
   * @function applyModalStyles
   * @summary: básicamente cambia los estilos de las cajas de los modales al poner en light mode o dark mode. 
   * 
   */
    function applyModalStyles(isDarkMode) {
      const cardBodyElements = document.querySelectorAll('.card-body');
    
      cardBodyElements.forEach(element => {
        if (isDarkMode) {
          element.classList.remove('bg-light');    // Remove light background class
          element.classList.add('bg-dark');        // Add dark background class
          element.classList.add('text-white');     // Add text white class
        } else {
          element.classList.remove('bg-dark');     // Remove dark background class
          element.classList.remove('text-white');  // Remove text white class
          element.classList.add('bg-light');       // Add light background class
        }
      });
    }
    
    /**
     * @function applyModalBodyStyles
     * @summary: cambia los estilos del borde de las cajas al abrir las imágenes en pantalla completa al poner en light mode o dark mode.
     *  
     */
    function applyModalBodyStyles(isDarkMode) {
      const modalBodyElements = document.querySelectorAll('.modal-body')

      modalBodyElements.forEach(element => {
        if (isDarkMode) {
          element.classList.remove('bg-light');
          element.classList.add('bg-dark');
        } else {
          element.classList.remove('bg-dark');
          element.classList.add('bg-light');
        }
      });
    }

    /**
     * @function onToggleMode
     * @summary: the event handler attached to the switch. calling @darkMode or @lightMode depending on the checked state.
     */
    function onToggleMode() {
      if (lightSwitch.checked) {
        darkMode();
        applyModalStyles(true);
        applyModalBodyStyles(true);
      } else {
        lightMode();
        applyModalStyles(false);
        applyModalBodyStyles(false);
      }
    }
  
    /**
     * @function getSystemDefaultTheme
     * @summary: get system default theme by media query
     */
    function getSystemDefaultTheme() {
      const darkThemeMq = window.matchMedia('(prefers-color-scheme: dark)');
      if (darkThemeMq.matches) {
        return 'dark';
      }
      return 'light';
    }

    function setup() {
      var settings = localStorage.getItem('lightSwitch');
      if (settings == null) {
        settings = getSystemDefaultTheme();
      }
  
      if (settings == 'dark') {
        lightSwitch.checked = true;
      }
  
      lightSwitch.addEventListener('change', onToggleMode);
      onToggleMode();
    }

    setup();
  })();