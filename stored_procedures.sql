DELIMITER //

CREATE PROCEDURE obtenerPersonajePorNombre (IN nombre_personaje VARCHAR(100))
BEGIN
   SELECT personajes.id, personajes.id_aparicion, personajes.nombre, personajes.alias, personajes.especie, apariciones.titulo_comic FROM apariciones INNER JOIN personajes ON personajes.id_aparicion = apariciones.id WHERE nombre = nombre_personaje;
END //


DELIMITER ;