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
        var regex = new RegExp("(png|jpeg|jpg)", "i");
        var boton = document.getElementById("subir");
        if (!regex.test(extension)) {
            boton.className = "boton mal";
        } else {
            boton.className = "boton bien";
        }
    }
}

function comprobarFichero2(elemento){
     var fichero = elemento.value;
    if (fichero.length) {
        var extension = fichero.split(".").pop();
        var regex = new RegExp("(png|jpeg|jpg)", "i");
        var marco = document.getElementById("subirImg");
        if (regex.test(extension)) {
            marco.src = window.URL.createObjectURL(elemento.files[0]);
        } else {
            marco.src = "../webroot/images/perfil.jpg";
        }
    }
    
}

function borrarImagen(elemento){
    elemento.className = "boton";
    document.getElementById("fichero").value = "";
}

function borrar(){
    var archivo = document.getElementById("imgPerfil");
    var marco = document.getElementById("subirImg");
    archivo.value = "";
    marco.src = "../webroot/images/perfil.jpg";
}

function borrarU(elemento){
    var usuario = document.getElementById("tUsuario").innerHTML;
    var boton = document.getElementById("bBorrar");
    if (elemento.value !== usuario){
        boton.disabled = true;
    } else{
        boton.disabled = false;
    }
}