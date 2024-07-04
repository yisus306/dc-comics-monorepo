DELIMITER //

CREATE PROCEDURE obtenerPersonajePorNombre (IN nombre_personaje VARCHAR(100))
BEGIN
    SELECT * FROM personajes INNER JOIN apariciones ON apariciones.id_personaje = personajes.id WHERE nombre = nombre_personaje;
END //

DELIMITER ;