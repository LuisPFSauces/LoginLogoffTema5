<?php
$lenguaje = Array(
    "es" => "Hola",
    "en" => "Hi",
    "pr" => "Oi",
    "it" => "Ciao",
    "ge" => "Hallo"
);
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
}

if(isset($_REQUEST['cerrar'])){
    session_destroy();
    header("Location: ../../indexProyectoDWES.php");
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
        <title></title>
        <script>
        </script>
    </head>
    <body>
        
        <?php
        require_once '../config/confDBPDO.php';
        $usuario = $_SESSION['usuario'];
        try {
            $miDB = new PDO(DSN, USER, PASSWORD);
            $consula = $miDB->prepare("Select * from Usuario where CodUsuario = :codigo");
            $ejecucion = $consula->execute(Array(":codigo" => $usuario));
            if ($ejecucion){
                $Ousuario = $consula ->fetchObject();
                echo "<p>".$lenguaje[$_COOKIE['idioma']] . " " . $Ousuario -> DescUsuario."</p>";
                if( $_SESSION['FechaHoraUltimaConexion'] != null){
                    echo "<p>Te has conectado ".$Ousuario -> NumConexiones ." veces</p>";
                    $fecha = (new DateTime) ->setTimestamp($_SESSION['FechaHoraUltimaConexion']);
                    echo "<p>La ultima vez que te conectaste fue el ".$fecha ->format("d-m-Y")." a las ".$fecha ->format("H:i");
                } else {
                    echo "<p>Esta es la primera vez que te conectas</p>";
                }
                
            } else {
                throw new Exception("Error al hacer la busqueda \"" . $departamentos->errorInfo()[2] . "\"", $departamentos->errorInfo()[1]);
            }
        } catch (Exception $e) {
            echo "<p class=\"error\" >Se ha producido un error al conectar con la base de datos( " . $e->getMessage() . ", " . $e->getCode() . ")</p>";
        }
        ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <label for="idioma">Elige un idioma</label>
            <select name="idioma" id="idioma" onchange="this.form.submit()">
                <option value="es" <?php
                if (!isset($_COOKIE['idioma']) || (isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == "es")) {
                    echo "selected";
                }
                ?>>Español</option>
                <option value="en" <?php echo (isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == "en") ? "selected" : ""; ?>>English</option>
                <option value="pr" <?php echo (isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == "pr") ? "selected" : ""; ?> >Português</option>
                <option value="it" <?php echo (isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == "it") ? "selected" : ""; ?> >Italiano</option>
                <option value="ge" <?php echo (isset($_COOKIE['idioma']) && $_COOKIE['idioma'] == "ge") ? "selected" : ""; ?> >Aleman</option>
            </select>
            <input type="submit" value="Cerrar Sesión" name="cerrar">
        </form>
<?php ?>
    </body>
</html>
