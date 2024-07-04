<?php
include_once '../database/conexion.php';
include_once '../models/personaje.php';

$modeloPersonaje = new Personaje($con);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre_personaje'])) {
    $nombre = $_POST['nombre_personaje'];
    $personaje = $modeloPersonaje->obtenerPersonalePorNombre($nombre);

    // Almacenar en una cookie
    setcookie("ultima_busqueda", json_encode($personaje), time() + (86400 * 30), "/");
} else {
    $personajes = $modeloPersonaje->obtenerPersonajes();
}
?>