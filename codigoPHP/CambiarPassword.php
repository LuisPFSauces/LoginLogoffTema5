<?php
if (isset($_REQUEST["cancelar"])) {
    header("Location: Programa.php");
    die();
}

session_start();
if (!isset($_SESSION['usuario'])) {

    header("Location: ../Login.php");
    die();
}

require_once '../config/confDBPDO.php';
$errores = [
    "contrasena" => null,
    "contrasena2" => null
];

$entradaOk = true;
try {
    $miDB = new PDO(DSN, USER, PASSWORD);
    if (isset($_REQUEST['aceptar']) && (!isset($_SESSION['password']) || !$_SESSION['password'])) {

        $consulta = $miDB->prepare("Select Password from Usuario where CodUsuario = :usuario");
        $consulta->bindParam(":usuario", $_SESSION['usuario']);
        $ejecucion = $consulta->execute();
        if ($ejecucion) {
            $Ousuario = $consulta->fetchObject();
            if ($Ousuario->Password == hash("sha256", $_SESSION['usuario'] . $_REQUEST['contrasena'])) {
                $_SESSION['password'] = true;
            } else {
                $_SESSION['password'] = false;
                $errores['contrasena'] = "Creedenciales incorrectas";
            }
        } else {
            throw new Exception("Error al recuperar los usuarios \"" . $Ousuario->errorInfo()[2] . "\"", $Ousuario->errorInfo()[1]);
        }
    } else if (isset($_REQUEST['aceptarVal']) && isset($_SESSION['password'])) {
        require_once '../core/libreriaValidacion.php';
        $errores['contrasena'] = validacionFormularios::validarPassword($_REQUEST['contrasena'], 20, 4, 2);
        empty($errores['contrasena']) ?: $entradaOk = false;

        if ($_REQUEST['contrasena'] != $_REQUEST["contrasena2"]) {
            $errores['contrasena2'] = "Error las contraseñas no coinciden";
            $entradaOk = false;
        }
    }
    if (!isset($_SESSION["password"]) || !$_SESSION['password']) {
        unset($miDB);
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8">
                <title>Contraseña</title>
                <link href="../webroot/css/estilos.css" rel="stylesheet" type="text/css">
            </head>
            <body>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <input type="password" placeholder="Introduce la contraseña" name="contrasena">
                    <?php echo!is_null($errores['contrasena']) ? "<span class=\"error\">" . $errores['contrasena'] . "</span>" : ""; ?>
                    <article class="opciones">
                        <input type="submit" value="Aceptar" name="aceptar">
                        <input type="submit" value="Cancelar" name="cancelar">
                    </article>
                </form>
            </body>
        </html>
        <?php
    } else {
        if (!isset($_REQUEST['aceptarVal'])) {
            $entradaOk = false;
        }

        if (isset($_REQUEST['aceptarVal']) && $entradaOk) {
            $consulta = $miDB->prepare("Update Usuario set Password = :contrasena where CodUsuario = :usuario");
            $ejecucion = $consulta->execute([":contrasena" => hash("sha256", $_SESSION['usuario'] . $_REQUEST['contrasena']), "usuario" => $_SESSION['usuario']]);
            unset($miDB);
            if ($ejecucion) {
                header("Location: Programa.php");
                die();
            } else {
                echo "Error al recuperar los usuarios \"" . $Ousuario->errorInfo()[2] . "\"" . $Ousuario->errorInfo()[1];
            }
        } else {
            unset($miDB);
            ?>
            <!DOCTYPE html>
            <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Contraseña</title>
                    <link href="../webroot/css/estilos.css" rel="stylesheet" type="text/css">
                    <script src="../webroot/script/script.js"></script>
                </head>
                <body>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <input type="password" id="contrasena" placeholder="Introduce la contraseña" name="contrasena" onblur="comprobarPassword(this)">
                        <?php echo!empty($errores['contrasena']) ? "<span class=\"error\">" . $errores['contrasena'] . "</span>" : ""; ?><br>
                        <input type="password" placeholder="Introduce la contraseña" name="contrasena2" onblur="comprobarPassword2(this)">
                        <?php echo!empty($errores['contrasena2']) ? "<span class=\"error\">" . $errores['contrasena2'] . "</span>" : ""; ?>
                        <article class="opciones">
                            <input type="submit" value="Aceptar" name="aceptarVal">
                            <input type="submit" value="Cancelar" name="cancelar">
                        </article>
                    </form>
                </body>
            </html>
            <?php
        }
    }
} catch (Exception $e) {
    echo "<p class=\"error\" >Se ha producido un error al conectar con la base de datos( " . $e->getMessage() . ", " . $e->getCode() . ")</p>";
    unset($miDB);
    die();
}



