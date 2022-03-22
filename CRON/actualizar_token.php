<?php
// Este documento se ejecuta desde la tarea con CRON JOB.

    //Solicitamos lo necesario
    require_once '../clases/token.class.php';

    //Instanciamos la clase.
    $_token = new token;

    //Creamos una fecha.
    $fecha = date('Y-m-d H:i');

    //Ya con al fecha mandamos a llamar el método de actualizar token.
    echo $_token->actualizarToken($fecha);



?>