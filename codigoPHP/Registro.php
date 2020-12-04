<?php
if (isset($_REQUEST['cancelar'])) {
    header("Location: ../login.php");
}

require_once '../config/confDBPDO.php';
require_once '../core/libreriaValidacion.php';
$errores = array(
    "usuario" => null,
    "descripcion" => null,
    "contrasena" => null,
    "contrasena2" => null,
);

$formulario = array(
    "usuario" => null,
    "descripcion" => null,
    "contrasena" => null
);

$entradaOk = true;

if (isset($_REQUEST["aceptar"])) {

    $miDB = new PDO(DSN, USER, PASSWORD);
    try {
        $errores['descripcion'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['descripcion'], 25, 3, 1);
        $errores['contrasena'] = validacionFormularios::validarPassword($_REQUEST['contrasena'], 20, 4, 2);


        if ($_REQUEST['contrasena'] != $_REQUEST["contrasena2"]) {
            $errores['contrasena2'] = "Error las contraseñas no coinciden";
        }

        $consula = $miDB->prepare("Select CodUsuario from Usuario");
        $ejecucion = $consula->execute();

        if ($ejecucion) {
            $usuarios = $consula->fetchObject();
            while ($usuarios && is_null($errores['usuario'])) {
                if ($_REQUEST['usuario'] == $usuarios->CodUsuario) {
                    $errores['usuario'] = "Error el usuario " . $_REQUEST['usuario'] . " ya existe.";
                }
                $usuarios = $consula->fetchObject();
            }
        } else {
            throw new Exception("Error al recuperar los usuarios \"" . $departamentos->errorInfo()[2] . "\"", $departamentos->errorInfo()[1]);
        }

        $errores['usuario'] .= validacionFormularios::comprobarAlfaNumerico($_REQUEST['usuario'], 15, 3, 1);

        foreach ($errores as $clave => $error) {
            if (!is_null($error)) {
                $_REQUEST[$clave] = "";
                $entradaOk = false;
            }
        }
    } catch (Exception $e) {
        echo "<p class=\"error\" >Se ha producido un error al conectar con la base de datos( " . $e->getMessage() . ", " . $e->getCode() . ")</p>";
        $entradaOk = false;
    }
} else {
    $entradaOk = false;
}

if ($entradaOk) {
    $formulario['usuario'] = $_REQUEST['usuario'];
    $formulario['descripcion'] = $_REQUEST['descripcion'];
    $formulario['contrasena'] = hash(sha256, $_REQUEST['usuario'] . $_REQUEST['contrasena']);
    $consula = $miDB->prepare("Insert into Usuario values(:codigo, :descripcion, :contrasena, NULL, NULL)");
} else {
    unset($miDB);
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Registro</title>
            <script src="../webroot/script/script.js"></script>
            <link type="text/css" href="../webroot/css/estilos.css" rel="stylesheet">
        </head>
        <body>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <input type="text" name="usuario" placeholder="Usuario" onblur="comprobarUsuario(this)" autocomplete="off" value="<?php echo isset($_REQUEST["usuario"]) ? $_REQUEST['usuario'] : ""; ?>">
                <?php echo!empty($errores['usuario']) ? "<span class=\"error\">" . $errores['usuario'] . "</span>" : ""; ?><br>
                <input type="text" name="descripcion" placeholder="descripcion" onblur="comprobarDescripcion(this)" autocomplete="off" value="<?php echo isset($_REQUEST["descripcion"]) ? $_REQUEST['descripcion'] : ""; ?>">
                <?php echo!empty($errores['descripcion']) ? "<span class=\"error\">" . $errores['descripcion'] . "</span>" : ""; ?><br>
                <input type="password" name="contrasena" id="contrasena" placeholder="Contraseña" onblur="comprobarPassword(this); comprobarPassword2(document.getElementById('contrasena2'))" autocomplete="off">
                <?php echo!empty($errores['contrasena']) ? "<span class=\"error\">" . $errores['contrasena'] . "</span>" : ""; ?><br>
                <input type="password" name="contrasena2" id="contrasena2" placeholder="Valida la contraseña" onblur="comprobarPassword2(this)" autocomplete="off">
                <?php echo!empty($errores['contrasena2']) ? "<span class=\"error\">" . $errores['contrasena2'] . "</span>" : ""; ?><br>
                <input type="file" name="imgPerfil" id="fichero" hidden onchange="comprobarFichero(this)">
                <input type="button" name="subirImg" id="subir" class="boton" onclick="document.getElementById('fichero').click();" value="Subir imagen">
                <input type="button" name="borrar"  class="boton" onclick="borrarImagen(document.getElementById('subir'))" value="Borrar"><br>
                <article class="opciones">
                    <input type="submit" name="aceptar" value="Aceptar">
                    <input type="submit" name="cancelar" value="Cancelar">
                </article>
            </form>
        </body>
    </html>
    <?php
}