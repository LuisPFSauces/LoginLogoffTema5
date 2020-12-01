<?php
require_once "./core/libreriaValidacion.php";
require_once './config/confDBPDO.php';

$errores = Array(
    "usuario" => null,
    "password" => null,
    "login" => null
);
$entradaOk = true;
if (isset($_REQUEST['login'])) {
    $errores["usuario"] = validacionFormularios::comprobarNoVacio($_REQUEST['usuario']);
    $errores["password"] = validacionFormularios::comprobarNoVacio($_REQUEST['password']);
    try {
        $miDB = new PDO(DSN, USER, PASSWORD);

        $consulta = $miDB->prepare("Select * from Usuario where CodUsuario = :codigo and Password = :password");
        $ejecucion = $consulta->execute(Array(":codigo" => $_REQUEST['usuario'], ":password" => hash("sha256", $_REQUEST['usuario'] . $_REQUEST['password'])));
        if ($consulta->rowCount() == 0) {
            $errores['login'] = "Credenciales incorrectas";
        } else if(!$ejecucion){
            throw new Exception("Error al recuperar los usuarios \"" . $departamentos->errorInfo()[2] . "\"", $departamentos->errorInfo()[1]);
        }
    } catch (Exception $e) {
        echo "<p class=\"error\" >Se ha producido un error al conectar con la base de datos( " . $e->getMessage() . ", " . $e->getCode() . ")</p>";
    }

    foreach ($errores as $error) {
        if (!is_null($error)) {
            $entradaOk = false;
            unset($miDB);
        }
    }
} else {
    $entradaOk = false;
}
if ($entradaOk) {
    session_start();
    $_SESSION['usuario'] = $_REQUEST['usuario'];
    $usuario = $consulta->fetchObject();
    $_SESSION['FechaHoraUltimaConexion'] = $usuario->FechaHoraUltimaConexion;
    $timeStamp = (new DateTime())->getTimestamp();
    $sql = <<<EOF
          Update Usuarios set 
          FechaHoraUltimaConexion = :fecha,
          NumConexiones = NumConexiones + 1
          where CodUsuario = :usuario;
    EOF;
    $consulta = $miDB ->prepare($sql);
    $consulta ->execute(Array(":fecha" => $timeStamp, ":usuario" => $_SESSION['usuario']));
    header("Location: codigoPHP/Programa.php");
} else {
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Login</title>
            <style>
                .error{
                    color: red;
                }
            </style>
        </head>
        <body>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <input type="text" name="usuario" value="<?php echo isset($_REQUEST["usuario"]) ? $_REQUEST['usuario'] : ""; ?>">
                <?php echo!empty($errores['usuario']) ? "<span class=\"error\">" . $errores['usuario'] . "</span>" : ""; ?><br>
                <input type="password" name="password">
                <?php echo!empty($errores['password']) ? "<span class=\"error\">" . $errores['password'] . "</span>" : ""; ?><br>
                <?php echo!empty($errores['login']) ? "<span class=\"error\">" . $errores['login'] . "</span>" : ""; ?><br>
                <input type="submit" name="login" value="aceptar">
            </form>
            <?php ?>
        </body>
    </html>
    <?php
}