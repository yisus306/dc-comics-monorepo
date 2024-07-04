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


}
?>