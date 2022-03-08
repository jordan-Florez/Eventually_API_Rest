<?php

// Instanciamos la clase conexion().
require_once "clases/conexion/conexion.php";

//Definimos una variable con la conexión.
$conexion = new conexion;

//Traemos todos los datos de la tabla usuarios en la DB.
// $query = "SELECT * FROM usuarios";
// print_r($conexion->obtenerDatos($query));

//Prueba de insert into, para recibir un 1 (Que significa que se agregó una fila más).
// $query = "INSERT INTO usuarios (UsuarioId)value(10)";
// print_r($conexion->nonQuery($query));

//Prueba de insert into, para recibir el id (Que se hace debido a que especificamos que cuando una fila sea afectada nos devuelva el último id).
$query = "INSERT INTO usuarios (UsuarioId)value(11)";
print_r($conexion->nonQueryId($query));

//Video #4 min 2:03

?>