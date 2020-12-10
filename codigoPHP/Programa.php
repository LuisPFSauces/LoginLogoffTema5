<?php

session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login.php");
}

if (isset($_REQUEST['cerrar'])) {
    session_destroy();
    header("Location: ../../indexProyectoDWES.php");
}

if (isset($_REQUEST['editar'])){
    header("Location: Editar.php");
}

$lenguaje = Array(
    "es" => "Hola",
    "en" => "Hi",
    "pr" => "Oi",
    "it" => "Ciao",
    "ge" => "Hallo",
    "bi" => "01001000 01101111 01101100 01100001",
    "hx" => "486f6c61"
);

if (!isset($_COOKIE['idioma'])) {
    setcookie("idioma", "es", time() + (60 * 60 * 24 * 30));
}

if (isset($_REQUEST["idioma"])) {
    setcookie("idioma", $_REQUEST["idioma"], time() + (60 * 60 * 24 * 30));
    header("Refresh:0");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="../webroot/css/estiloPerfil.css" type="text/css" rel="stylesheet">
        <title>Perfil</title>
    </head>
    <body>
        <?php
        require_once '../config/confDBPDO.php';
        $usuario = $_SESSION['usuario'];
        try {
            $miDB = new PDO(DSN, USER, PASSWORD);
            $consulta = $miDB->prepare("Select * from Usuario where CodUsuario = :codigo");
            $ejecucion = $consulta->execute(Array(":codigo" => $usuario));
            if ($ejecucion) {
                $Ousuario = $consulta->fetchObject();
                echo "<div class=\"opciones\">\n";
                echo "<h2>Programa</h2>";
                if (!is_null($Ousuario->ImagenUsuario)) {
                    echo '<img id="imgPerfil" src="data:image/png;base64,' . base64_encode($Ousuario->ImagenUsuario) . '"/>' . "\n";
                } else {
                    echo '<img id="imgPerfil" src="../webroot/images/perfil.jpg"/>'."\n";
                }
                ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype = "multipart/form-data">
                    <select name="idioma" id="idioma" onchange="this.form.submit()">
                        <option value="es" <?php
                        if ($_COOKIE['idioma'] == "es") {
                            echo "selected";
                        }
                        ?>>Español</option>
                        <option value="en" <?php echo ($_COOKIE['idioma'] == "en") ? "selected" : ""; ?>>English</option>
                        <option value="pr" <?php echo ($_COOKIE['idioma'] == "pr") ? "selected" : ""; ?> >Português</option>
                        <option value="it" <?php echo ($_COOKIE['idioma'] == "it") ? "selected" : ""; ?> >Italiano</option>
                        <option value="ge" <?php echo ($_COOKIE['idioma'] == "ge") ? "selected" : ""; ?> >Aleman</option>
                        <option value="bi" <?php echo ($_COOKIE['idioma'] == "bi") ? "selected" : ""; ?> >Binario</option>
                        <option value="hx" <?php echo ($_COOKIE['idioma'] == "hx") ? "selected" : ""; ?> >Hexadecimal</option>
                    </select>
                    <input type="submit" value="Editar" name="editar">
                    <input type="submit" value="Cerrar Sesión" name="cerrar">
                </form>
                <?php echo "</div>\n"; ?>
                <main>
                    <?php
                    echo "<p>" . $lenguaje[$_COOKIE["idioma"]] . " " . $Ousuario->DescUsuario . "</p>\n";
                    if ($_SESSION['FechaHoraUltimaConexion'] != null) {
                        echo "<p>Te has conectado " . $Ousuario->NumConexiones . " veces</p>\n";
                        $fecha = (new DateTime)->setTimestamp($_SESSION['FechaHoraUltimaConexion']);
                        echo "\t<p>La ultima vez que te conectaste fue el " . $fecha->format("d-m-Y") . " a las " . $fecha->format("H:i") . "\n";
                    } else {
                        echo "\t<p>Esta es la primera vez que te conectas</p>\n";
                    }
                } else {
                    throw new Exception("Error al hacer la busqueda \"" . $departamentos->errorInfo()[2] . "\"", $departamentos->errorInfo()[1]);
                }
            } catch (Exception $e) {
                echo "<p class=\"error\" >Se ha producido un error al conectar con la base de datos( " . $e->getMessage() . ", " . $e->getCode() . ")</p>";
                die();
            }
            ?>
        </main>
    </body>
</html>
