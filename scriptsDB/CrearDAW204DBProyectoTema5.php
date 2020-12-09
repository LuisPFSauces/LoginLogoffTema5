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
                    CREATE TABLE if NOT EXISTS DepartamentoTema5 (
                        CodDepartamento VARCHAR(3) PRIMARY KEY,
                        DescDepartamento VARCHAR(255) NOT NULL,
                        FechaBajaDepartamento DATE NULL,
                        FechaCreacionDepartamento INT NULL,
                        VolumenNegocio FLOAT NULL
                    )ENGINE=INNODB;

                    CREATE TABLE IF NOT EXISTS Usuario(
                            CodUsuario VARCHAR(15) PRIMARY KEY,
                            DescUsuario VARCHAR(25) NOT NULL,
                            Password VARCHAR(64) NOT NULL,
                            Perfil enum('administrador', 'usuario') DEFAULT 'usuario',
                            FechaHoraUltimaConexion INT,
                            NumConexiones INT DEFAULT 0,
                            ImagenUsuario MEDIUMBLOB
                    )ENGINE=INNODB;

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
