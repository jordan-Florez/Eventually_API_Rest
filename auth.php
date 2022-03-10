<?php

//Archivos requeridos.
require_once 'clases/auth.class.php';
require_once 'clases/respuestas.class.php';

//Instanciamos las clases.
$_auth = new auth;
$_respuestas = new respuestas;

//Creamos método para el envío mediante post.
if ($_SERVER['REQUEST_METHOD'] == "POST"){

    //Definimos una variable en la que se pueda obtener el contenido.
    $postBody = file_get_contents("php://input");
    //En caso de querer verlo.
    $datosArray = $_auth->login($postBody);
    print_r(json_encode($datosArray));


}else{
    echo "Método no está permitido.";
}




?>