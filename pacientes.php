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
        echo json_encode($listaPacientes);

    //Evaluamos si existe una variable en el get que se llame id para hacer consulta específica.
    }else if(isset($_GET["id"])){
        //Asignamos la variable del id a paciente.
        $pacienteId = $_GET["id"];
        //Creamos la variable datosPaciente para recibirlos.
        $datosPaciente = $_pacientes->obtenerPaciente($pacienteId);
        echo json_encode($datosPaciente);
    }
}else if ($_SERVER['REQUEST_METHOD'] == "POST"){
    echo "hola Post";
}else if ($_SERVER['REQUEST_METHOD'] == "PUT"){
    echo "hola Put";
}else if ($_SERVER['REQUEST_METHOD'] == "DELETE"){
    echo "hola Delete";
}else{
    //En caso de utilizar un método no permitido, vamos a no solo enviar un texto sino que le enviamos un header
    header('content-Type: application/json');

    //Creamos una variabl que igualaremos a un código de error.
    $datosArray = $_respuestas->error_405();

    //hacemos un echo de json encode de los datosArray.
    echo json_encode($datosArray);
}

?>