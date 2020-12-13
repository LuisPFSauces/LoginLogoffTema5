<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login.php");
    die();
}

if (isset($_REQUEST['cancelar'])) {
    header("Location: Programa.php");
    die();
}

if (isset($_REQUEST["cambiar"])) {
    header("Location: CambiarPassword.php");
    die();
}

require_once '../config/confDBPDO.php';
if (isset($_REQUEST["bBorrar"])) {
     try {
        $miDB = new PDO(DSN, USER, PASSWORD);
        $consulta = $miDB ->prepare("Delete from Usuario where CodUsuario = :usuario");
        $consulta ->bindParam(":usuario", $_SESSION['usuario']);
        $ejecucion = $consulta ->execute();
        if ($ejecucion){
            unset($miDB);
            session_destroy();
            header("Location: ../../indexProyectoDWES.php");
            die();
        } else {
            throw new Exception("Error al actualizar el usuario \"" . $consulta->errorInfo()[2] . "\"", $consulta->errorInfo()[1]);
        }
    } catch (Exception $e) {
        unset($miDB);
        
        echo "<p class=\"error\" >Se ha producido un error al conectar con la base de datos( " . $e->getMessage() . ", " . $e->getCode() . ")</p>";
        die();
    }
}


$formulario = [
    "descripcion" => null,
    "imgPerfil" => null,
];

require_once '../config/confDBPDO.php';
require_once '../config/confImages.php';
require_once '../core/libreriaValidacion.php';
$entradaOk = true;
$errores = array(
    "descripcion" => null,
    "conexion" => null
);

if (isset($_REQUEST['aceptar'])) {
    $errores['descripcion'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['descripcion'], 25, 3, 1);
    if (!empty($errores['descripcion'])) {
        $entradaOk = false;
        $_REQUEST['descripcion'] = "";
    }

    if (!empty($_FILES['imgPerfil']['tmp_name'])) {
        if ($_FILES['imgPerfil']['size'] < 5242880) {
            if (!in_array(exif_imagetype($_FILES['imgPerfil']['tmp_name']), $tipos_permitidos)) {
                $errores['imgPerfil'] = "El formato del fichero no esta permitido. Introduce un JPG, PNG o JPEG";
                $entradaOk = false;
            } else {
                $formulario["imgPerfil"] = file_get_contents($_FILES['imgPerfil']['tmp_name']);
            }
        } else {
            $errores['imgPerfil'] = "El tamaño de la imagen no puede ser superior a 5MB";
            $entradaOk = false;
        }
    }
} else {
    $entradaOk = false;
}

try {
    $miDB = new PDO(DSN, USER, PASSWORD);
    if ($entradaOk) {
        $formulario['descripcion'] = $_REQUEST['descripcion'];
        $sql = "Update Usuario set DescUsuario = :descripcion";
        if (!is_null($formulario["imgPerfil"])) {
            $sql .= ", ImagenUsuario = :imagen where CodUsuario = :usuario";
            echo $sql;
            $consulta = $miDB->prepare($sql);
            $ejecucion = $consulta->execute([":descripcion" => $formulario['descripcion'], ":imagen" => $formulario['imgPerfil'], ":usuario" => $_REQUEST['usuario']]);
        } else {
            $sql .= " where CodUsuario = :usuario";
            echo $sql;
            $consulta = $miDB->prepare($sql);
            $ejecucion = $consulta->execute([":descripcion" => $formulario['descripcion'], ":usuario" => $_REQUEST['usuario']]);
        }

        if ($ejecucion) {
            unset($miDB);
            header("Location: Programa.php");
            die();
        } else {
            throw new Exception("Error al actualizar el usuario \"" . $consulta->errorInfo()[2] . "\"", $consulta->errorInfo()[1]);
        }
    } else {
        $consulta = $miDB->prepare("Select * from Usuario where CodUsuario = :codigo limit 1");
        $consulta->bindParam(":codigo", $_SESSION['usuario']);
        $ejecucion = $consulta->execute();
        if ($ejecucion && $consulta->rowCount() > 0) {
            $Ousuario = $consulta->fetchObject();
            ?>
            <!DOCTYPE html>
            <html>
                <head>
                    <meta charset="UTF-8">
                    <title></title>
                    <script src="../webroot/script/script.js"></script>
                    <link href="../webroot/css/estilos.css" type="text/css" rel="stylesheet" >
                </head>
                <body>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="editar" enctype= "multipart/form-data">
                        <?php
                        if (!is_null($Ousuario->ImagenUsuario)) {
                            echo "<div class=\"imagen\">";
                            echo "\t" . '<img id="subirImg" src="data:image/png;base64,' . base64_encode($Ousuario->ImagenUsuario) . '" onclick="document.getElementById(\'imgPerfil\').click()"/>' . "\n";
                            echo "<span class=\"cerrar\">X</span>";
                            echo '<span class="textoInf" onclick="document.getElementById(\'imgPerfil\').click()" >Selecionar imagen de perfil</span>';
                            echo "</div>";
                        } else {
                            echo "<div class=\"imagen\">";
                            echo "\t" . '<img id="subirImg" src="../webroot/images/perfil.jpg" onclick="document.getElementById(\'imgPerfil\').click()"/>' . "\n";
                            echo '<span class="cerrar" onclick="borrar()">X</span>';
                            echo '<span class="textoInf" onclick="document.getElementById(\'imgPerfil\').click()" >Selecionar imagen de perfil</span>';
                            echo "</div>";
                        }
                        ?><br>
                        <input type="file" hidden id="imgPerfil" name="imgPerfil" onchange="comprobarFichero2(this)">
                        <label for="usuario">Usuario</label>
                        <input type="text" id="usuario" readonly name="usuario" value="<?php echo $Ousuario->CodUsuario ?>"><br>
                        <label for="usuario">Descripción</label>
                        <input type="text" name="descripcion" value="<?php
                        if (isset($_REQUEST['descripcion'])) {
                            echo $_REQUEST['descripcion'];
                        } else {
                            echo $Ousuario->DescUsuario;
                        }
                        ?>"><br>
            <?php echo!empty($errores['descripcion']) ? "<span class=\"error\">" . $errores['descripcion'] . "</span><br>" : ""; ?>
                        <label for="fecha">Ultima Conexion</label>
                        <input type="text" id="fecha" readonly name="fecha" value="<?php echo (new DateTime)->setTimestamp($Ousuario->FechaHoraUltimaConexion)->format("d-m-Y H:i") ?>"><br>
                        <label for="conexiones">Numero de Conexiones</label>
                        <input type="text" id="conexiones" readonly name="conexiones" value="<?php echo $Ousuario->NumConexiones ?>"><br>
                        <input type="submit" name="cambiar" value="Cambiar la contraseña"><br>
                        <input type="button" name="borrar" value="Borrar la cuenta" onclick="document.getElementById('dialogo').style.display = 'block'" ><br>
                        <article class="opciones">
                            <input type="submit" name="aceptar" value="Aceptar">
                            <input type="submit" name="cancelar" value="Cancelar">
                        </article>
                        <article class="dialogo" id="dialogo">
                            <h2>¿Estas seguro?</h2>
                            <p>Esto es irreversible y una vez echo no podras deshacer este cambio.</p>
                            <p>Escribe <span id="tUsuario"><?php echo $Ousuario->CodUsuario; ?>/Borrar</span> para aceptar</p>
                            <input type="text" name="tBorrar" id="tBorrar" oninput="borrarU(this)" autocomplete="off" ><br>
                            <input type="submit" name="bBorrar" id="bBorrar" disabled value="Borrar Cuenta">
                        </article>
            <?php echo!empty($errores['conexion']) ? "<span class=\"error\">" . $errores['conexion'] . "</span><br>" : ""; ?>
                    </form>
                </body>
            </html>
            <?php
            unset($miDB);
        } else {
            unset($miDB);
            echo "<p class=\"error\">Error al hacer la busqueda en la base de datos</p>";
        }
    }
} catch (Exception $e) {
    unset($miDB);
    echo "<p class=\"error\" >Se ha producido un error al conectar con la base de datos( " . $e->getMessage() . ", " . $e->getCode() . ")</p>";
    die();
}
