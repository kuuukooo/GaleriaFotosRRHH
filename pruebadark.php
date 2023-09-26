<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagination with Dark Mode</title>
    <style>
        body {
            padding: 2% 3% 10% 3%;
            text-align: center;
        }

        h1 {
            color: #000;
            margin-top: 30px;
        }

        .dark {
            background-color: #222;
            color: #e6e6e6;
        }

        .dark h1 {
            color: #fff;
        }

        .theme-switch-wrapper {
            display: flex;
            align-items: center;
            float: right;
        }

        .theme-switch {
            display: inline-block;
            height: 34px;
            position: relative;
            width: 60px;
            text-align: right;
        }

        .theme-switch input {
            display: none;
        }

        .slider {
            background-color: #ccc;
            bottom: 0;
            cursor: pointer;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            transition: .4s;
        }

        .slider:before {
            background-color: #fff;
            bottom: 4px;
            content: "";
            height: 26px;
            left: 4px;
            position: absolute;
            transition: .4s;
            width: 26px;
        }

        input:checked + .slider {
            background-color: #66bb6a;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .pagination {
            margin-top: 20px;
        }

        .page {
            display: none;
        }

        .page.active {
            display: block;
        }
    </style>
</head>
<body>
<div class="theme-switch-wrapper">
    <label class="theme-switch" for="checkbox">
        <input type="checkbox" id="checkbox" />
        <div class="slider round"></div>
    </label>
    <label style="margin-left: 10px;">Select Mode</label>
</div><br>
<h1><i>Techsolutionstuff<i></h1>
<p><i>We Give Best Tech Stuff for You</i></p>
<h3>Light Mode and Dark Mode</h3>

<!-- Botón de cambio de página -->
<button id="nextPage">Next Page</button>

<!-- Contenido de la página -->
<div class="pagination">
    <div class="page page1 active">
        <h2>Page 1</h2>
        <ul id="content1">
        </ul>
    </div>
    <div class="page page2">
        <h2>Page 2</h2>
        <ul id="content2">
        </ul>
    </div>
</div>

<script>
    // Función para guardar el estado del modo en el almacenamiento local del navegador
    function saveModeState(isDarkMode) {
        localStorage.setItem('darkMode', isDarkMode);
    }

    // Función para cargar el estado del modo desde el almacenamiento local del navegador
    function loadModeState() {
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        if (isDarkMode) {
            $('body').addClass('dark');
            $('#checkbox').prop('checked', true);
        }
    }

    $(document).ready(function () {
        // Cargar el estado del modo al cargar la página
        loadModeState();

        $('#checkbox').click(function () {
            var element = document.body;
            element.classList.toggle("dark");
            // Guardar el estado del modo al hacer clic
            saveModeState(element.classList.contains("dark"));
        });

        // Llenar la lista de contenido para las dos páginas
        const content1 = $('#content1');
        const content2 = $('#content2');
        for (let i = 1; i <= 20; i++) {
            const listItem = `<li>Item ${i}</li>`;
            if (i <= 10) {
                content1.append(listItem);
            } else {
                content2.append(listItem);
            }
        }

        // Control de la paginación
        let currentPage = 1;
        $('#nextPage').click(function () {
            $('.page').removeClass('active');
            currentPage = currentPage === 1 ? 2 : 1;
            $(`.page${currentPage}`).addClass('active');
        });
    });
</script>
</body>
</html>
