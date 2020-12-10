<?php
if (isset($_REQUEST["cancelar"])) {
    header("Location: Programa.php");
}

session_start();
require_once '../config/confDBPDO.php';
$errores = [
   "contrasena" => null,
    "contrasena2" => null
];
if (!isset($_SESSION["password"])) {
    if (isset($_REQUEST['aceptar'])) {
        $miDB = new PDO(DSN, USER, PASSWORD);
        
    } else{
        $entradaOk = false;
    }
    if($entradaOk){
        
    } else{
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
                    <input type="password" placeholder="Introduce la contraseña" name="password">
                    <?php ?>
                    <article class="opciones">
                        <input type="submit" value="Aceptar" name="aceptarPass">
                        <input type="submit" value="Cancelar" name="cancelar">
                    </article>
                </form>
            </body>
        </html>
        <?php
    }
} else {
    unset($_SESSION["password"]);
    header("Location: Editar.php");
}
?>



