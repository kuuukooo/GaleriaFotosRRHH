const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#contrasena');

    //Cambiado de modo de contraseña a texto para la visualización
    $(document).on('click', '#togglePassword', function () {
        console.log("click");
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        // toggle the eye / eye slash icon
        this.classList.toggle('bi-eye');
    
        // prevent form submit
        const form = document.querySelector("form");
        form.addEventListener('submit', function (e) {
            e.preventDefault();
        });
    });
