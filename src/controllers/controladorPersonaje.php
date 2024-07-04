<?php
include_once '../database/conexion.php';
include_once '../models/personaje.php';

$modeloPersonaje = new Personaje($con);

$personajes = $modeloPersonaje->obtenerPersonajes();

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

?>