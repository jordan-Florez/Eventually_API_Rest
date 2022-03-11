<?php

//Importamos lo necesario.
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

//Declaramos lo necesario.


class pacientes extends conexion{

    //Creamos un atributo privado que nos servirá para identificar la tabla de la que se consulta.
    private $table = "pacientes";

    //Creamos una función pública para listar a los pacientes. Teniendo en cuenta que no vamos a mostrar todos los pacientes simplemente mostramos de 100 en 100, para esto creamos variable página.
    public function listaPacientes($pagina = 1){
        //Creamos variable inicio para saber por qué registro comenzar.
        $inicio = 0;
        //Creamos variable cantidad que son los registros que vamos a mostrar al principio.
        $cantidad = 100;

        //Utilizamos un if para reacomodar el valor que empieza en 0.
        if ($pagina > 1) {
            //Acomodamos para que siempre muestre el registro 101, 201, 301. . .
            $inicio = ($cantidad * ($pagina-1)) + 1;
            //Definimos la cantidad de registros que se mostrarán.
            $cantidad = $cantidad * $pagina;
        }
        //Hacemos una query con ayuda de la propiedad de la tabla y limitando la cantidad de resultados con ayuda del Inicio y Cantidad.
        $query = "SELECT PacienteId,Nombre,DNI,Telefono,Correo FROM " . $this->table . " limit $inicio,$cantidad";
        //Creamos variable datos para llevar los datos de la query.
        $datos = parent::obtenerDatos($query);
        return ($datos);
    }

    //Método para obtener un paciente de manera individual pasando por parámetro el Id.
    public function obtenerPaciente($id){
        //Creamos consulta basada en el id que recibimos como parámetro.
        $query = "SELECT * FROM " . $this->table . " WHERE PacienteId = '$id'";
        return parent::obtenerDatos($query);

    }

}



?>