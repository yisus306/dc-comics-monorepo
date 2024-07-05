<?php
include_once '../database/conexion.php';
include_once '../models/comic.php';

$modeloComic = new Comic($con);

$comics = $modeloComic->obtenerComics();

?>