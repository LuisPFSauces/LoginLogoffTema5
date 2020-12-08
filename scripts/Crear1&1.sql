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
