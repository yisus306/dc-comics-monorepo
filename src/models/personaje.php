<?php

class Personaje {
    private $con;

    public function __construct($con){
        $this->con = $con;
    }

    public function obtenerPersonajePorNombre($nombre){
        $sql = "CALL obtenerPersonajePorNombre(?)";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function obtenerPersonajes(){
        $sql = "SELECT * FROM personajes;";
        $result = $this->con->query($sql);
        $personajes = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        return $personajes;
    }

    public function eliminarPersonajePorID($idPersonaje){
    
        $this->con->begin_transaction();

        try {
            // Actualiza la llave foránea en la tabla apariciones
            $sqlUpdate = "UPDATE apariciones SET id_personaje = NULL WHERE id_personaje = ?";
            $stmtUpdate = $this->con->prepare($sqlUpdate);
            $stmtUpdate->bind_param("i", $idPersonaje);
            $stmtUpdate->execute();

            // Elimina el registro del personaje
            $sqlDelete = "DELETE FROM personajes WHERE id = ?";
            $stmtDelete = $this->con->prepare($sqlDelete);
            $stmtDelete->bind_param("i", $idPersonaje);
            $stmtDelete->execute();

            // Si todo fue bien, confirma la transacción
            $this->con->commit();

            return true;
        } catch (Exception $e) {
            $this->con->rollback();

            return false;
        }
    }
}
?>