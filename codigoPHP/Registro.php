<?php
if (isset($_REQUEST['cancelar'])) {
    header("Location: ../Login.php");
}

require_once '../config/confDBPDO.php';
require_once '../core/libreriaValidacion.php';
require_once '../config/confImages.php';
$errores = array(
    "usuario" => null,
    "descripcion" => null,
    "contrasena" => null,
    "contrasena2" => null,
    "imgPerfil" => null
);

$formulario = array(
    "usuario" => null,
    "descripcion" => null,
    "contrasena" => null,
    "imgPerfil" => null
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

        $consulta = $miDB->prepare("Select * from Usuario where CodUsuario = :codigo");
        $consulta->bindParam(":codigo", $_REQUEST['usuario']);
        $ejecucion = $consulta->execute();

        if ($ejecucion) {
            if ($consulta->rowCount() > 0) {
                $errores['usuario'] = "Error el usuario " . $_REQUEST['usuario'] . " ya existe.";
            }
        } else {
            throw new Exception("Error al recuperar los usuarios \"" . $departamentos->errorInfo()[2] . "\"", $departamentos->errorInfo()[1]);
        }

        $errores['usuario'] .= validacionFormularios::comprobarAlfaNumerico($_REQUEST['usuario'], 15, 3, 1);
        if (!empty($_FILES['imgPerfil']['tmp_name'])) {
            if ($_FILES['imgPerfil']['size'] < 5242880) {
                if (!in_array(exif_imagetype($_FILES['imgPerfil']['tmp_name']), $tipos_permitidos)) {
                    $errores['imgPerfil'] = "El formato del fichero no esta permitido. Introduce un JPG, PNG o JPEG";
                } else {
                    $formulario["imgPerfil"] = file_get_contents($_FILES['imgPerfil']['tmp_name']);
                }
            } else {
                $errores['imgPerfil'] = "El tamaño de la imagen no puede ser superior a 5MB";
            }
        }
        foreach ($errores as $clave => $error) {
            if (!empty($error)) {
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
    $formulario['contrasena'] = hash("sha256", $_REQUEST['usuario'] . $_REQUEST['contrasena']);
    $timeStamp = (new DateTime())->getTimestamp();
    $consulta = $miDB->prepare("Insert into Usuario(CodUsuario, DescUsuario, Password, FechaHoraUltimaConexion, NumConexiones, ImagenUsuario) values (:codigo, :descripcion, :contrasena, :fecha, 1, :imagen)");
    $ejecucion = $consulta->execute(array(
        ":codigo" => $formulario['usuario'],
        ":descripcion" => $formulario['descripcion'],
        ":contrasena" => $formulario['contrasena'],
        ":fecha" => $timeStamp,
        ":imagen" => $formulario['imgPerfil']
    ));


    if ($ejecucion) {
        session_start();
        $_SESSION['FechaHoraUltimaConexion'] = null;
        $_SESSION['usuario'] = $formulario['usuario'];
        header("Location: Programa.php");
    } else {
        header("Location: ../Login.php");
    }
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
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype = "multipart/form-data">
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
                <input type="button" name="borrar"  class="boton" onclick="borrarImagen(document.getElementById('subir'))" value="Borrar">
                <?php echo!empty($errores['contrasena2']) ? "<span class=\"error\">" . $errores['contrasena2'] . "</span>" : ""; ?><br>
                <article class="opciones">
                    <input type="submit" name="aceptar" value="Aceptar">
                    <input type="submit" name="cancelar" value="Cancelar">
                </article>
            </form>
        </body>
    </html>
    <?php
}