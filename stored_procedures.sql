CREATE TABLE apariciones (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo_comic VARCHAR(100) NOT NULL,
        );
        
CREATE TABLE personajes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            alias VARCHAR(100),
            especie VARCHAR(100),
            id_aparicion INT NOT NULL,
            CONSTRAINT fk_id_aparicion FOREIGN KEY (id_aparicion) REFERENCES apariciones(id)
        );

DELIMITER //

CREATE PROCEDURE obtenerPersonajePorNombre (IN nombre_personaje VARCHAR(100))
BEGIN
   SELECT personajes.id, personajes.id_aparicion, personajes.nombre, personajes.alias, personajes.especie, apariciones.titulo_comic FROM apariciones INNER JOIN personajes ON personajes.id_aparicion = apariciones.id WHERE nombre = nombre_personaje;
END //


DELIMITER ;