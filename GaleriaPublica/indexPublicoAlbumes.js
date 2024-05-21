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
            thumbnailGutterWidth: 10,
            thumbnailGutterHeight: 10,
            thumbnailHoverEffect: 'label_slideUp',
            thumbnailHoverEffect2: "labelAppear75|imageScaleIn80",
            thumbnailAlignment: "center",
            thumbnailDisplayTransitionDuration: 50,
            thumbnailLabel: {
            position: "overImageOnBottom"
            },
        });
        
    } catch (e) {
        console.error("Error al inicializar nanogallery2:", e);
    }
});
