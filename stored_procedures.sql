DELIMITER //

CREATE PROCEDURE obtenerPersonajePorNombre (IN nombre_personaje VARCHAR(100))
BEGIN
    SELECT * FROM personajes WHERE name = nombre_personaje;
END //

DELIMITER ;