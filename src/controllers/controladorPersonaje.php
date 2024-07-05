<?php
include_once '../database/conexion.php';
include_once '../models/personaje.php';

$modeloPersonaje = new Personaje($con);

$personajes = $modeloPersonaje->obtenerPersonajes();
$showModal = false;
$error = "";
$onEdit = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['abrir_modal'])){
    if($_POST['abrir_modal'] == "yes") $showModal = true;
} 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre_personaje'])) {
    $nombre = $_POST['nombre_personaje'];
    $personaje = $modeloPersonaje->obtenerPersonajePorNombre($nombre);

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
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['id_personaje_editar'])){
    $idPersonaje = $_POST['id_personaje_editar'];
    $personajeData = $modeloPersonaje->obtenerPersonajePorId($idPersonaje);
    $personaje = $personajeData[0];
    $idCharacter = $personaje['id'];
    $onEdit = true;
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_personaje'])){
    $idPersonaje = $_POST['id_personaje'];
    $isDeleted = $modeloPersonaje->eliminarPersonajePorID($idPersonaje);
    if($isDeleted){
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
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
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error =  "Registro no creado";
        }    
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_nombre']) && isset($_POST['edit_alias']) && isset($_POST['edit_especie']) && isset($_POST['edit_id_comic'])){   
    $characterName = $_POST['edit_nombre'];
    $alias = $_POST['edit_alias'];
    $especie = $_POST['edit_especie'];

    if($characterName != "" && $alias != "" && $especie != ""){
        $idCharacter = $_POST['id'];
        $idComic = $_POST['edit_id_comic'];
        $isEdit = $modeloPersonaje->editarPersonaje($idCharacter, $idComic, $characterName, $alias, $especie);
        if($isEdit){
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $error =  "Registro no editado";
        }    
    }
}

?>