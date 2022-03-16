<?php

//Agregamos lo requerido:
require_once 'clases/respuestas.class.php';
require_once 'clases/pacientes.class.php';

//Instanciamos las clases.
$_respuestas = new respuestas;
$_pacientes = new pacientes;

//Preguntamos cual método se va a usar o se está solicitando.
if($_SERVER['REQUEST_METHOD'] == "GET"){

    //Ejecutamos el método para obtener todos los pacientes.
    //Evaluamos si existe dentro de la variable _Get un page.
    if(isset($_GET["page"])){
        //Si existe, entonces asignaremos page a una variable $pagina
        $pagina = $_GET["page"];
        //Llamamos el método de listar pacientes enviando por parámetro $pagina.
        $listaPacientes = $_pacientes->listaPacientes($pagina);
        //Pasamos aquí la informacíon bien especificada, para eso un header.
        header("Content-Type: application/json");
        echo json_encode($listaPacientes);
        //Definimos el código de respuesta.
        http_response_code(200);

    //Evaluamos si existe una variable en el get que se llame id para hacer consulta específica.
    }else if(isset($_GET["id"])){
        //Asignamos la variable del id a paciente.
        $pacienteId = $_GET["id"];
        //Creamos la variable datosPaciente para recibirlos.
        $datosPaciente = $_pacientes->obtenerPaciente($pacienteId);
        //Pasamos aquí la informacíon bien especificada, para eso un header.
        header("Content-Type: application/json");
        echo json_encode($datosPaciente);
        //Definimos el código de respuesta.
        http_response_code(200);
    }
//Método para insertar.
}else if ($_SERVER['REQUEST_METHOD'] == "POST"){

    //Recibimos los datos enviados.
    $postBody = file_get_contents("php://input");
    //Enviamos los datos al manejador.
    $datosArray = $_pacientes->post($postBody);
    //Devolvemos una respuesta.
    //Devolvemos header.
    header('Content-Type: application/json');
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

//Método para actualizar.
}else if ($_SERVER['REQUEST_METHOD'] == "PUT"){

    //RECIBIMOS LOS DATOS ENVIADOS:

    //Obtenemos datos que nos envían para actualizar.
    $postBody = file_get_contents("php://input");

    //ENVIAMOS DATOS AL MANEJADOR.

    //Enviamos los datos ejecutando el método put.
    $datosArray = $_pacientes->put($postBody);

    //DEVOLVEMOS UNA RESPUESTA.

    //Devolvemos header.
    header('Content-Type: application/json');
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


}else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){

    //RECIBIMOS LOS DATOS ENVIADOS:

    //Obtenemos datos que nos envían para actualizar.
    $postBody = file_get_contents("php://input");

    //ENVIAMOS DATOS AL MANEJADOR.

    //Enviamos los datos ejecutando el método Delete.
    $datosArray = $_pacientes->delete($postBody);

    //DEVOLVEMOS UNA RESPUESTA.

    //Devolvemos header.
    header('Content-Type: application/json');
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