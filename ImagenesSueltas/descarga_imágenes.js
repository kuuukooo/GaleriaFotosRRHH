/**
La función `downloadimage` descarga imágenes y sus descripciones, 
las comprime en un archivo zip e inicia la descarga del archivo zip cuando se hace clic en un botón de descarga.
 */
function downloadimage() {
    let e = document.querySelectorAll(".download-button");
    e.forEach(function (e) {
        e.addEventListener("click", async function (e) {
            e.preventDefault();
            let a = this.getAttribute("data-description");
            console.log("Descripci\xf3n:", a);
            let t = this.getAttribute("data-images"),
                n = t.split(","),
                i = new FormData(),
                o = new JSZip(),
                l = 1,
                c = n.map(async function (e) {
                    let t = a,
                        n = `Imagen ${l}`,
                        c = `${t}_${n}.${e.split(".").pop()}`;
                    console.log("Nuevo nombre de la im\xe1gen: ", c), l++, console.log(`Cargando imagen: ${e}`);
                    let g = await fetch(`./assets/images/posts/${e}`).then((a) => (console.log(`Imagen cargada: ${e}`), a.blob()));
                    o.file(c, g), console.log(`Imagen agregada al ZIP: ${c}`), i.append("image_descriptions[]", t), console.log(`Descripci\xf3n de la imagen agregada al FormData: ${t}`);
                });
            await Promise.all(c),
                o.generateAsync({ type: "blob" }).then(function (e) {
                    let t = document.createElement("a"),
                        n = window.URL.createObjectURL(e);
                    (t.href = n), (t.download = `${a}.zip`), console.log("Nombre de archivo descargado:", `${a}.zip`), t.click(), window.URL.revokeObjectURL(n);
                });
        });
    });
}
