<?php
$nombreServidor = "localhost";
$nombreUsuario = "root";
$contrasena = "";
$nombreBaseDatos = "dc_comics";

// Crear conexion
$con = new mysqli($nombreServidor, $nombreUsuario, $contrasena, $nombreBaseDatos);

// Verificar conexion
if($con->connect_error){
    die("Conexcion fallida: ". $con->connect_error);
}
?>