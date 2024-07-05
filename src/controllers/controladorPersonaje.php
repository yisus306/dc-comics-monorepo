<?php
include_once '../database/conexion.php';
include_once '../models/personaje.php';

$modeloPersonaje = new Personaje($con);

$personajes = $modeloPersonaje->obtenerPersonajes();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['abrir_modal'])){
    $value = $_POST['abrir_modal'];
    if($value == "yes"){
        $showModal = true;
    }else{
        $showModal = false;
    }
} else {
    $showModal = false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre_personaje'])) {
    $nombre = $_POST['nombre_personaje'];
    $personaje = $modeloPersonaje->obtenerPersonajePorNombre($nombre);

    $error = ''; // Inicializar la variable de error
    if ($personaje === null) {
        $error = "El personaje '$nombre' no se encontró.";
    } else {
        // Almacenar en una cookie el último personaje encontrado
        setcookie("ultima_busqueda", json_encode($personaje), time() + (86400 * 1), "/");
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_personaje'])){
    $idPersonaje = $_POST['id_personaje'];
    $isDeleted = $modeloPersonaje->eliminarPersonajePorID($idPersonaje);
    if($isDeleted){
        $personajes = $modeloPersonaje->obtenerPersonajes();
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre']) && isset($_POST['alias']) && isset($_POST['especie']) && isset($_POST['id_comic'])){
    
    $characterName = $_POST['nombre'];
    $alias = $_POST['alias'];
    $especie = $_POST['especie'];

    if($characterName != "" && $alias != "" && $especie != ""){
        $idComic = $_POST['id_comic'];
        $isInsert = $modeloPersonaje->insertarPersonaje($idComic, $characterName, $alias, $especie);
        if($isInsert){
            $personajes = $modeloPersonaje->obtenerPersonajes();
        } else {
            $error = "Registro no creado";
        }    
    }
}

?>