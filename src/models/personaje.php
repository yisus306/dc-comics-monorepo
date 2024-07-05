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

    public function obtenerPersonajePorId($idCharacter){
        $sql = "SELECT personajes.id, personajes.id_aparicion, personajes.nombre, personajes.alias, personajes.especie, apariciones.titulo_comic FROM apariciones INNER JOIN personajes ON personajes.id_aparicion = apariciones.id WHERE personajes.id = $idCharacter;";
        $result = $this->con->query($sql);
        $personaje = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        return $personaje;    
    }

    public function editarPersonaje($idCharacter, $idComic, $nombre, $alias, $especie){
        $sql = "UPDATE personajes SET id_aparicion = ?, nombre = ?, alias = ?, especie = ? WHERE id = ?;";
        
        if ($stmt = $this->con->prepare($sql)) {
            $stmt->bind_param("isssi", $idComic, $nombre, $alias, $especie, $idCharacter);
            
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                $resultado = true;
            } else {
                $resultado = false; 
            }
            
            $stmt->close();
        } else {
            $resultado = false; 
        }
              
        return $resultado;
    }

    public function obtenerPersonajes(){
        $sql = "SELECT * FROM personajes;";
        $result = $this->con->query($sql);
        $personajes = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        return $personajes;
    }

    public function eliminarPersonajePorID($idPersonaje){
        $sqlDelete = "DELETE FROM personajes WHERE id = ?";
        
        if ($stmt = $this->con->prepare($sqlDelete)) {
            $stmt->bind_param("i", $idPersonaje);
            
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                $resultado = true;
            } else {
                $resultado = false; 
            }
            
            $stmt->close();
        } else {
            $resultado = false; 
        }
        
        return $resultado;
    }

    public function insertarPersonaje($idComic, $nombre, $alias, $especie){
        $sqlDelete = "INSERT INTO personajes (id_aparicion, nombre, alias, especie) VALUES(?, ?, ?, ?);";
        
        if ($stmt = $this->con->prepare($sqlDelete)) {
            $stmt->bind_param("isss", $idComic, $nombre, $alias, $especie);
            
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                $resultado = true;
            } else {
                $resultado = false; 
            }
            
            $stmt->close();
        } else {
            $resultado = false; 
        }
              
        return $resultado;
    }
}
?>