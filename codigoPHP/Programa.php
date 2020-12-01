<?php
$lenguaje = Array(
        "es" => "Hola",
        "en" => "Hi",
        "pr" => "Oi",
        "it" => "Garbini",
        "ge" => "subanestrugenbajen"
    );
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
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
        <p><?php echo $lenguaje[$_COOKIE['idioma']]." ".$_SESSION['usuario']?></p>
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
        </form>
        <?php ?>
    </body>
</html>
