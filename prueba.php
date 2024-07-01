<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nanogallery2/3.0.5/css/nanogallery2.min.css" integrity="sha512-6sOT9zKSKq1CYgNMqtcY84tFPDnG1yX5mxwdGQiAVpAomVr2kUKJ//pFeU/KfaZDVCOru5iFOVswpT4RWWF2dQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->    
    <!-- Librerias Utilizadas: JSzip y Nanogallery2 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nanogallery2/3.0.5/jquery.nanogallery2.min.js" integrity="sha512-tvpLVnZrWnnNzV2921XEMx4xkFTUF8xg3s+Mi6cvC/R7A6X1FkpBUXPJFa3Xh5uD9BvOZ2tHeYq/5ZqrweW86Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>

<div id="my_nanogalleryAlbumes">

</div>

<script>
$(document).ready(function() {
  try {
    $('#my_nanogalleryAlbumes').nanogallery2({
      items: [
            { src: '../assets/images/posts/prueba1.jpg', title: 'album A',  ID: 1,  kind:'album' },
            { src: '../assets/images/posts/prueba1.jpg', srct: '../assets/images/posts/prueba1.jpg', title: 'image A1', ID: 10, albumID: 1 },
            { src: '../assets/images/posts/prueba2.jpg', srct: '../assets/images/posts/prueba2.jpg', title: 'image A2', ID: 11, albumID: 1 },
            { src: '../assets/images/posts/prueba3.jpg', srct: '../assets/images/posts/prueba3.jpg', title: 'image A3', ID: 12, albumID: 1 },
            
            { src: '../assets/images/posts/prueba4.jpg', title: 'album B',  ID: 2,  kind:'album' },
            { src: '../assets/images/posts/prueba5.jpg', title: 'image B1', ID: 20, albumID: 2 },
            { src: '../assets/images/posts/prueba6.jpg', title: 'image B2', ID: 21, albumID: 2 },
            { src: '../assets/images/posts/prueba7.jpg', title: 'image B3', ID: 22, albumID: 2 },

            { src: '../assets/images/posts/prueba4.jpg', title: 'album B',  ID: 3,  kind:'album' },
            { src: '../assets/images/posts/prueba5.jpg', title: 'image B1', ID: 23, albumID: 3 },
            { src: '../assets/images/posts/prueba6.jpg', title: 'image B2', ID: 24, albumID: 3 },
            { src: '../assets/images/posts/prueba7.jpg', title: 'image B3', ID: 25, albumID: 3 },

            { src: '../assets/images/posts/prueba4.jpg', title: 'album B',  ID: 4,  kind:'album' },
            { src: '../assets/images/posts/prueba5.jpg', title: 'image B1', ID: 26, albumID: 4 },
            { src: '../assets/images/posts/prueba6.jpg', title: 'image B2', ID: 27, albumID: 4 },
            { src: '../assets/images/posts/prueba7.jpg', title: 'image B3', ID: 28, albumID: 4 },

            { src: '../assets/images/posts/prueba4.jpg', title: 'album B',  ID: 5,  kind:'album' },
            { src: '../assets/images/posts/prueba5.jpg', title: 'image B1', ID: 29, albumID: 5 },
            { src: '../assets/images/posts/prueba6.jpg', title: 'image B2', ID: 30, albumID: 5 },
            { src: '../assets/images/posts/prueba7.jpg', title: 'image B3', ID: 31, albumID: 5 },

            { src: '../assets/images/posts/prueba4.jpg', title: 'album B',  ID: 6,  kind:'album' },
            { src: '../assets/images/posts/prueba5.jpg', title: 'image B1', ID: 32, albumID: 6 },
            { src: '../assets/images/posts/prueba6.jpg', title: 'image B2', ID: 33, albumID: 6 },
            { src: '../assets/images/posts/prueba7.jpg', title: 'image B3', ID: 34, albumID: 6 },

            { src: '../assets/images/posts/prueba4.jpg', title: 'album B',  ID: 7,  kind:'album' },
            { src: '../assets/images/posts/prueba5.jpg', title: 'image B1', ID: 35, albumID: 7 },
            { src: '../assets/images/posts/prueba6.jpg', title: 'image B2', ID: 36, albumID: 7 },
            { src: '../assets/images/posts/prueba7.jpg', title: 'image B3', ID: 37, albumID: 7 },

            { src: '../assets/images/posts/prueba4.jpg', title: 'album B',  ID: 8,  kind:'album' },
            { src: '../assets/images/posts/prueba5.jpg', title: 'image B1', ID: 38, albumID: 8 },
            { src: '../assets/images/posts/prueba6.jpg', title: 'image B2', ID: 39, albumID: 8 },
            { src: '../assets/images/posts/prueba7.jpg', title: 'image B3', ID: 40, albumID: 8 },

            { src: '../assets/images/posts/prueba4.jpg', title: 'album B',  ID: 9,  kind:'album' },
            { src: '../assets/images/posts/prueba5.jpg', title: 'image B1', ID: 41, albumID: 9 },
            { src: '../assets/images/posts/prueba6.jpg', title: 'image B2', ID: 42, albumID: 9 },
            { src: '../assets/images/posts/prueba7.jpg', title: 'image B3', ID: 43, albumID: 9 },

            { src: '../assets/images/posts/prueba4.jpg', title: 'album B',  ID: 10,  kind:'album' },
            { src: '../assets/images/posts/prueba5.jpg', title: 'image B1', ID: 44, albumID: 10 },
            { src: '../assets/images/posts/prueba6.jpg', title: 'image B2', ID: 45, albumID: 10 },
            { src: '../assets/images/posts/prueba7.jpg', title: 'image B3', ID: 46, albumID: 10 }
        ],
        thumbnailHeight: 300,
        thumbnailWidth: 250,
        thumbnailBorderVertical: 0,
        thumbnailBorderHorizontal: 0,
        thumbnailGutterWidth: 20,
        thumbnailGutterHeight: 10,
        thumbnailHoverEffect: 'label_slideUp',
        thumbnailHoverEffect2: "labelAppear75|scale120",
        thumbnailAlignment: "center"
    });
  } catch (e) {
    console.error("Error al inicializar nanogallery2:", e);
  }
});

</script>
</body>
</html>