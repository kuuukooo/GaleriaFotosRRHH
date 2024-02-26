<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nanogallery2/3.0.5/css/nanogallery2.min.css" integrity="sha512-6sOT9zKSKq1CYgNMqtcY84tFPDnG1yX5mxwdGQiAVpAomVr2kUKJ//pFeU/KfaZDVCOru5iFOVswpT4RWWF2dQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nanogallery2/3.0.5/jquery.nanogallery2.min.js" integrity="sha512-tvpLVnZrWnnNzV2921XEMx4xkFTUF8xg3s+Mi6cvC/R7A6X1FkpBUXPJFa3Xh5uD9BvOZ2tHeYq/5ZqrweW86Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <style>
        .dialogPrueba {
            border-radius: 1rem;   
            border: none;   
            padding: 0;  
            transition: opacity .15s linear, transform .15s linear;
        }
        .dialogPrueba::backdrop {
            backdrop-filter: blur(4px);
        }
        .dialogPrueba.open {
            opacity: 1;
            transition: opacity .15s linear, transform .15s linear;
        }
        .headerPrueba {
            padding-top: 1.5rem;
            padding-left: 2rem;
            padding-right: 2rem;
            background-color: rgb(255, 255, 255);
            position: relative;
        }
        .headerTexto {
            font-weight: 700;
            font-size: 1.5rem;
            line-height: 2rem;
        }
        .botonHeader {
            border: none;
            font-size: 1.25rem;
            line-height: 1.75rem;  
            background-color: rgb(243, 244, 246);
            border-radius: 0.375rem;
            justify-content: center;
            align-items: center;
            width: 2rem;
            height: 2rem;
            top: 1.75rem;
            right: 1rem;
            position: absolute;
        }
        .spanHeader {
            color: red;
        }
        .Main {
            padding: 4rem 2rem 4rem 2rem;
            background-color: rgb(255, 255, 255); 
        }
        .imagenDiv{
            align-items: center;
            display: flex;
            margin-top: calc(0.75rem * calc(1 - 0));
            margin-bottom: calc(0.75rem * calc(1 - 0));
        }
        .imagenLabel {
            margin-right: auto;
            width: 60%;
            color: rgb(156, 163, 175);
        }
        .imagenInput, select {
            padding: 0.5rem 0.75rem 0.5rem 0.75rem;
            width: 100%;
            border-radius: 0.5rem;
            border-width: 1px;    
        }
        .footerDialog {
            padding: 1rem 2rem 1rem 2rem;
            background-color: rgb(243, 244, 246);
            gap: 1.5rem;
            border-bottom-left-radius: 1rem;
            border-bottom-right-radius: 1rem;
            justify-content: flex-end;
            display: flex;
        }
        .cancelFooterDialog {
            color: rgb(156, 163, 175);
            cursor: pointer;
            border: none;       
        }
        .saveFooterDialog {
            padding: 0.75rem 1.25rem 0.75rem 1.25rem;
            color: rgb(255, 255, 255);
            background-color: rgb(59, 130, 246);
            border-radius: 0.75rem;
            border: none;
        }
        .saveFooterDialog:hover {
            background-color: rgb(49, 106, 202);
        }
        .cancelFooterDialog:hover {
            color: rgb(107, 114, 128);
        }
        .botonHeader:hover {
            background-color: rgb(235, 238, 242);
        }
        .inputFileMask {
            display: flex;
            padding: 10px 20px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            border: none;
            width: 100%;
            justify-content: space-between;
        }
        input[type="file"] {
            display: none;
        }
    </style>
</head>
<body>
<h3>
  Prueba con NanoGallery2
</h3>

<!-- Formulario para cargar imágenes -->
<button onclick=showDialog(true)>Prueba</button>

<dialog class="dialogPrueba">
    <div class="wrapper">
        <form>
          <header class="headerPrueba">
            <h1 class="headerTexto">Elige las imágenes</h1>
            <button onclick=showDialog(false) type="button" class="botonHeader">
              <span class="spanHeader"><i class="bi bi-x-lg"></i></span>
            </button>
          </header>
          <main class="Main">
            <div class="imagenDiv">
              <label class="imagenLabel">Imágen</label>
              <label for="imagenInput" class="inputFileMask">Agregar Imágen <i class="bi bi-images fa-lg"></i></label>
              <input
                class="imagenInput"
                id="imagenInput"
                type="file"
                accept=".jpg, .jpeg, .png, .gif"/>
            </div>
            <div class="imagenDiv">
              <label class="imagenLabel">Descripción</label>
              <input class="imagenInput" id="imagenInput" type="text">
            </div>
          </main>
          <footer class="footerDialog">
            <button class="cancelFooterDialog" formMethod="dialog" value="cancel">
              Cancelar
            </button>
            <button class="saveFooterDialog" formMethod="dialog" value="submit">
              Subir Imágen
            </button>
          </footer>
        </form>
    </div>  
</dialog>

<div id="nanogallery2" 
data-nanogallery2='{
    "thumbnailWidth":   200,
  	  "thumbnailHeight":  200,
      "galleryTheme":    {
        "navigationBreadcrumb": { "background": "#008" }
      } 
}'>
    <a href="" data-ngkind="album" data-ngid="1" data-ngthumb="fotos/prueba.png">Album A</a>
    <a href="fotos/prueba.png" data-ngid="10" data-ngalbumid="1" data-ngthumb="fotos/prueba.png">Image 1 / album A</a>
    <a href="fotos/super cap.jpg" data-ngid="11" data-ngalbumid="1" data-ngthumb="fotos/super cap.jpg">Image 2 / album A</a>

    <a href="" data-ngkind="album" data-ngid="2" data-ngthumb="fotos/ingeniero.jpg">Album B</a>
    <a href="fotos/ingeniero.jpg" data-ngid="3" data-ngalbumid="2" data-ngthumb="fotos/ingeniero.jpg">Image 1 / album B</a>
    
    <a href="" data-ngkind="album" data-ngid="3" data-ngthumb="fotos/Untitled.png">Album C</a>
    <a href="fotos/Untitled.png" data-ngid="4" data-ngalbumid="3" data-ngthumb="fotos/Untitled.png">Image 1 / album B</a>

    <a href="" data-ngkind="album" data-ngid="6" data-ngthumb="fotos/michi kung fu.jpg">Album D</a>
    <a href="fotos/michi kung fu.jpg" data-ngid="5" data-ngalbumid="6" data-ngthumb="fotos/michi kung fu.jpg">Image 1 / album B</a>
</div>

<script src="pruebaJS.js"></script>
</body>
</html>