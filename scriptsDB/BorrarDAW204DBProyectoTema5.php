<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        </head>
    <body>
        
        <?php
        require_once '../config/confDBPDO.php';
            try { 
                $miDB = new PDO(DSN,USER,PASSWORD);
                $sql = <<<EOF
                    drop table if exists DepartamentoTema5;
                    drop table if exists Usuario;
EOF;
                $miDB->exec($sql);

               
               echo "<p style='color:green;'>CARGA INICIAL CORRECTO</p>";
            }catch (PDOException $miExceptionPDO) { // Codigo que se ejecuta si hay alguna excepcion
                echo "<p style='color:red;'>ERROR</p>";
                echo "<p style='color:red;'>Código de error: ".$miExceptionPDO->getCode()."</p>"; // Muestra el codigo del error
                echo "<p style='color:red;'>Error: ".$miExceptionPDO->getMessage()."</p>"; // Muestra el mensaje de error
            }finally{
                unset($miDB);
            }

        ?> 
    </body>
</html>