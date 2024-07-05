<?php

class Comic {
    private $con;

    public function __construct($con){
        $this->con = $con;
    }

    public function obtenerComics(){
        $sql = "SELECT * FROM apariciones;";
        $result = $this->con->query($sql);
        $apariciones = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        return $apariciones;
    }
}
?>