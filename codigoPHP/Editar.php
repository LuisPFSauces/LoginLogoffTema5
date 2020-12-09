<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../Login.php");
}

if (isset($_REQUEST['cancelar'])) {
    header("Location: Programa.php");
}

if (isset($_REQUEST["cambiar"])){
    
}

require_once '../config/confDBPDO.php';
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
} else {
    $entradaOk = false;
}
try {
    $miDB = new PDO(DSN, USER, PASSWORD);
} catch (Exception $e) {
    $errores['conexion'] = "<p class=\"error\" >Se ha producido un error al conectar con la base de datos( " . $e->getMessage() . ", " . $e->getCode() . ")</p>";
    $entradaOk = false;
}

if ($entradaOk) {
    
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
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="editar">
                    <?php
                    if (!is_null($Ousuario->ImagenUsuario)) {
                        echo '<img id="subirImg" src="data:image/png;base64,' . base64_encode($Ousuario->ImagenUsuario) . '" onclick="document.getElementById(\'imgPerfil\').click()"/>' . "\n";
                    } else {
                        echo '<img id="subirImg" src="../webroot/images/perfil.jpg" onclick="document.getElementById(\'imgPerfil\').click()"/>'."\n";
                    }
                    ?><br>
                    <input type="file" hidden id="sub" onchange="comprobarFichero(this)">
                    <label for="usuario">Usuario</label>
                    <input type="text" id="usuario" readonly name="Usuario" value="<?php echo $Ousuario -> CodUsuario ?>"><br>
                    <label for="usuario">Descripción</label>
                    <input type="text" name="descripcion" value="<?php 
                        if(isset($_REQUEST['descripcion'])){
                            echo $_REQUEST['descripcion'];
                        } else{
                            echo $Ousuario -> DescUsuario;
                        }
                    ?>"><br>
                    <?php echo!empty($errores['descripcion']) ? "<span class=\"error\">" . $errores['descripcion'] . "</span><br>" : ""; ?>
                    <label for="fecha">Ultima Conexion</label>
                    <input type="text" id="fecha" readonly name="fecha" value="<?php echo (new DateTime)->setTimestamp($Ousuario -> FechaHoraUltimaConexion) ->format("d-m-Y H:i") ?>"><br>
                    <label for="conexiones">Numero de Conexiones</label>
                    <input type="text" id="conexiones" readonly name="conexiones" value="<?php echo $Ousuario -> NumConexiones ?>"><br>
                    <input type="submit" name="cambiar" value="Cambiar la contraseña"><br>
                    <article class="opciones">
                        <input type="submit" name="aceptar" value="Aceptar">
                        <input type="submit" name="cancelar" value="Cancelar">
                    </article>
                    <?php echo!empty($errores['conexion']) ? "<span class=\"error\">" . $errores['conexion'] . "</span><br>" : ""; ?>
                </form>
            </body>
        </html>
        <?php
    } else {
        echo "<p class=\"error\">Error al hacer la busqueda en la base de datos</p>";
    }
}
