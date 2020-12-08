function comprobarUsuario(elemento) {
    var usuario = elemento.value;
    var regex = new RegExp("[a-zA-Z0-9]{3,15}");
    if (!regex.test(usuario)) {
        elemento.className = "mal";
    } else {
        elemento.className = "bien";
    }
}

function comprobarDescripcion(elemento) {
    var descripcion = elemento.value;
    var regex = new RegExp("[a-zA-Z0-9]{3,25}");
    if (!regex.test(descripcion)) {
        elemento.className = "mal";
    } else {
        elemento.className = "bien";
    }
}
function comprobarPassword(elemento) {
    var password = elemento.value;
    var regex = new RegExp("[a-zA-Z0-9]{4,20}");
    if (!regex.test(password)) {
        elemento.className = "mal";
    } else {
        elemento.className = "bien";
    }
}

function comprobarPassword2(elemento) {
    var password2 = elemento.value;
    var password = document.getElementById("contrasena").value;
    if (password !== password2) {
        elemento.className = "mal";
    } else if (password2 !== "") {
        elemento.className = "bien";
    }
}

function comprobarFichero(elemento) {
    var fichero = elemento.value;
    if (fichero.length) {
        var extension = fichero.split(".").pop();
        var regex = new RegExp("(png|jpeg|jpg)");
        var buton = document.getElementById("subir");
        if (!regex.test(extension)) {
            buton.className = "boton mal";
        } else {
            buton.className = "boton bien";
        }
    }
}

function comprobarFichero2(elemento){
    
}

function borrarImagen(elemento){
    elemento.className = "boton";
    document.getElementById("fichero").value = "";
}
