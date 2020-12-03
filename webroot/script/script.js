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
    if (password != password2) {
        elemento.className = "mal";
    } else {
        elemento.className = "bien";
    }
}