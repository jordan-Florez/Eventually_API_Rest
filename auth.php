<?php

//Archivos requeridos.
require_once 'clases/auth.class.php';
require_once 'clases/respuestas.class.php';

//Instanciamos las clases.
$_auth = new auth;
$_respuestas = new respuestas;

//Creamos método para el envío mediante post.
if ($_SERVER['REQUEST_METHOD'] == "POST"){

    //A la hora de que una aplicación consuma nuestra API rest debemos de tener en cuenta que la devolución de información debe estar muy bien, para esto debemos de tener en cuenta 3 cosas: Recibir datos, enviar datos y devolver respuesta.


    //RECIBIMOS DATOS.
    //Definimos una variable en la que se pueda obtener el contenido.
    $postBody = file_get_contents("php://input");

    //ENVIAMOS DATOS AL MANEJADOR.
    //En caso de querer verlo.
    $datosArray = $_auth->login($postBody);

    //DEVOLVEMOS UNA RESPUESTA.
    //Aquí con ayuda de un header vamos a decirle específicamente que estamos devolviendo como respuesta.
    header('content-Type: application/json');

    //Aquí también debemos de enviarle el response code que lo hacemos en los códigos de error que tenemos (En la clase respuestas), para esto aquí:

    //Si en el array datosArray hay un subarray que tenga en el campo result el valor error_id, entonces. . .
    if (isset($datosArray["result"]["error_id"])) {
        //Creamos una variable para tomar de referencia si hay un error_id.
        $responseCode = $datosArray["result"]["error_id"];
        //Utilizamos método que sirve para definir un código de respuesta.
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }

    //Ahora si enviamos la respuesta pero no con el print_r ya que este sirve pero para depurar.
    echo json_encode($datosArray);


}else{
    //En caso de utilizar un método no permitido, vamos a no solo enviar un texto sino que le enviamos un header
    header('content-Type: application/json');

    //Creamos una variabl que igualaremos a un código de error.
    $datosArray = $_respuestas->error_405();

    //hacemos un echo de json encode de los datosArray.
    echo json_encode($datosArray);

}




?>