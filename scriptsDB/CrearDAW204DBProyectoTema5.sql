/**
 * Author:  daw2
 * Created: 25 nov. 2020
 */

-- Script de Miguel Angel
-- Crear base de datos 
   -- CREATE DATABASE if NOT EXISTS (Nombre base de datos);

-- Uso de la base de datos. 
   -- USE (Nombre base de datos);
    
-- Crear tablas. 
 --   CREATE TABLE IF NOT EXISTS (Nombre tabla);(
     --   (Nombre campo) varchar(3) PRIMARY KEY,
      --  (Nombre campo) varchar(255) NOT null
  --  )ENGINE=INNODB;

-- Crear del usuario
--CREATE USER IF NOT EXISTS '(Nombre Usuario)'@'%' identified BY '(password)'; 

-- Dar permisos al usuario 
   -- GRANT ALL PRIVILEGES ON (Nombre base de datos).* TO '(Nombre Usuario)'@'%'; 

-- Hacer el flush privileges, por si acaso da error de credenciales
   -- FLUSH PRIVILEGES;

-- CREACION BASE DE DATOS
-- Creacion de la base de datos DAW204DBDepartamentos
CREATE DATABASE if NOT EXISTS DAW204DBProyectoTema5;

-- Creacion de tablas de la base de datos
CREATE TABLE if NOT EXISTS DAW204DBProyectoTema5.Departamento (
    CodDepartamento VARCHAR(3) PRIMARY KEY,
    DescDepartamento VARCHAR(255) NOT NULL,
    FechaBajaDepartamento DATE NULL,
    FechaCreacionDepartamento INT NULL,
    VolumenNegocio FLOAT NULL
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS DAW204DBProyectoTema5.Usuario(
        CodUsuario VARCHAR(15) PRIMARY KEY,
        DescUsuario VARCHAR(25) NOT NULL,
        Password VARCHAR(64) NOT NULL,
        Perfil enum('administrador', 'usuario') DEFAULT 'usuario',
        FechaHoraUltimaConexion INT,
        NumConexiones INT DEFAULT 0,
        ImagenUsuario MEDIUMBLOB
)ENGINE=INNODB;


-- Creacion de usuario administrador de la base de datos: usuarioDAW204DBDepartamentos / paso
CREATE USER 'usuarioDAW204DBProyectoTema5'@'%' IDENTIFIED BY 'P@ssw0rd';

-- Permisos para la base de datos
GRANT ALL PRIVILEGES ON DAW204DBProyectoTema5.* TO 'usuarioDAW204DBProyectoTema5'@'%';

FLUSH PRIVILEGES;