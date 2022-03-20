<?php

//Importamos lo necesario.
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

//Declaramos lo necesario.


class pacientes extends conexion{

    //Creamos un atributo privado que nos servirá para identificar la tabla de la que se consulta.
    private $table = "pacientes";
    //Creamos otros atributos privados equivalentes a los de la DB.
    private $pacienteId = "";
    private $dni = "";
    private $nombre = "";
    private $direccion = "";
    private $codigoPostal = "";
    private $genero = "";
    private $telefono = "";
    //Caso especial, es un date, no un string.
    private $fechaNacimiento = "0000-00-00";
    private $correo = "";
    //Creamos una variable que nos servirá para hacer las respectivas validaciones con el token que creamos a la hora de iniciar una sesión.
    private $token = "";
    //Pegamos el token que vamos a utilizar.
    //ecb6c7d75d38e179b1c5f53b8f6dc120

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

    //Método para comporbar si la información por post es adecuada.
    public function post($json)
    {

        //Instanciamos la clase respuestas.
        $_respuestas = new respuestas;
        //Definimos una variable para decodificar el Json y transformarlo en array asociativo.
        $datos = json_decode($json, true);

        //Aquí ya que incluimos el token debemos comprobar si existe.
        if(!isset($datos['token'])){

            //En caso de no existir el token devolvemos un error (error nuevo).
            return $_respuestas->error_401();

        }else{
            //En caso de que todo esté bien, esté puesto el token.

            //Definimo una variable para el token.
            $this->token = $datos['token'];

            //Definimos otra variable en la que almacenaremos todos los datos que vengan de la tabla del token.
            $arrayToken = $this->buscarToken();

            //Evaluamos si existen datos dentro de arrayToken
            if ($arrayToken) {

                //Ahora pasamos a validar si los datos que nos envían poseen los campos requeridos para hacer un insert.
                // Para pacientes DNI, Nombre y correo aunque tengan más.
                if(!isset($datos['nombre']) || !isset($datos['dni']) || !isset($datos['correo'])){
                    //En caso de error.
                    return $_respuestas->error_400();
                }else{
                    //En caso de que estén los campos que si pedimos.
                    $this->nombre = $datos['nombre'];
                    $this->dni = $datos['dni'];
                    $this->correo = $datos['correo'];
                    //Al ser las únicas 3 propiedades que validamos existe la posibilidad de error si se envían más, para evitarlo, hacemos:
                    if(isset($datos['telefono'])){$this->telefono = $datos['telefono'];}
                    if(isset($datos['direccion'])){$this->direccion = $datos['direccion'];}
                    if(isset($datos['codigoPostal'])){$this->codigoPostal = $datos['codigoPostal'];}
                    if(isset($datos['genero'])){$this->genero = $datos['genero'];}
                    if(isset($datos['fechaNacimiento'])){$this->fechaNacimiento = $datos['fechaNacimiento'];}

                    //Ejecutamos el método para insertar el paciente.
                    $resp = $this->insertarPaciente();

                    //Evaluamos si nos devuelve la inserción correcta
                    if($resp){
                        //En caso de hacerlo, asignamos en la variable respuesta la propiedad de la clase respuetas response.
                        $respuesta = $_respuestas->response;
                        //Dentro de response que ahora es respuestas, en el campo de result que es un array vacío, le asignaremos a ese array vació el pacienteId, que será igual a resp que es el que nos trae el id debido al nonqueryId.
                        $respuesta["result"] = array(
                            "pacienteId" => $resp
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }

            }else{
                //Enviamos error en caso de que no exista.
                return $_respuestas->error_401("El token que envio es invalido o ha caducado");
            }

        }

    }

    //Método para insertar pacientes.
    private function insertarPaciente(){

        //Creamos la query para insertar los datos dentro de la tabla pacientes.
        $query = "INSERT INTO " . $this->table . " (DNI,Nombre,Direccion,CodigoPostal,Telefono,Genero,FechaNacimiento,Correo) values ('" . $this->dni . "','" . $this->nombre . "','" . $this->direccion . "','" . $this->codigoPostal . "','" . $this->telefono . "','" . $this->genero . "','" . $this->fechaNacimiento . "','" . $this->correo . "')";

        //Creamos variable respuesta.
        $resp = parent::nonQueryId($query);
        //Evaluamos si la información ha sido introducida o no.
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }

    //Método para actualizar.
    public function put($json){

        //Instanciamos la clase respuestas.
        $_respuestas = new respuestas;
        //Definimos una variable para decodificar el Json y transformarlo en array asociativo.
        $datos = json_decode($json, true);


        //Aquí ya que incluimos el token debemos comprobar si existe.
        if(!isset($datos['token'])){

            //En caso de no existir el token devolvemos un error (error nuevo).
            return $_respuestas->error_401();

        }else{
            //En caso de que todo esté bien, esté puesto el token.

            //Definimo una variable para el token.
            $this->token = $datos['token'];

            //Definimos otra variable en la que almacenaremos todos los datos que vengan de la tabla del token.
            $arrayToken = $this->buscarToken();

            //Evaluamos si existen datos dentro de arrayToken
            if ($arrayToken) {

                //Ahora pasamos a validar si los datos que nos envían poseen los campos requeridos para hacer un insert.
                //Para pacientes DNI, Nombre y correo aunque tengan más.
                if(!isset($datos['pacienteId'])){
                    //En caso de error.
                    return $_respuestas->error_400();
                }else{
                    //Igualamos los campos a los valores (es el único que estamos validando):
                    $this->pacienteId = $datos['pacienteId'];
                    //Al ser las únicas 3 propiedades que validamos existe la posibilidad de error si se envían más, para evitarlo, hacemos:
                    if(isset($datos['nombre'])){$this->nombre = $datos['nombre'];}
                    if(isset($datos['dni'])){$this->dni = $datos['dni'];}
                    if(isset($datos['correo'])){$this->correo = $datos['correo'];}
                    if(isset($datos['telefono'])){$this->telefono = $datos['telefono'];}
                    if(isset($datos['direccion'])){$this->direccion = $datos['direccion'];}
                    if(isset($datos['codigoPostal'])){$this->codigoPostal = $datos['codigoPostal'];}
                    if(isset($datos['genero'])){$this->genero = $datos['genero'];}
                    if(isset($datos['fechaNacimiento'])){$this->fechaNacimiento = $datos['fechaNacimiento'];}

                    //Ejecutamos el método para modificar el paciente.
                    $resp = $this->modificarPaciente();

                    //Evaluamos si nos devuelve la modificación correcta
                    if($resp){
                        //En caso de hacerlo, asignamos en la variable respuesta la propiedad de la clase respuetas response.
                        $respuesta = $_respuestas->response;
                        //Dentro de response que ahora es respuestas, en el campo de result que es un array vacío, le asignaremos a ese array vació el pacienteId, que será igual a resp que es el que nos trae el id debido al nonqueryId.
                        $respuesta["result"] = array(
                            "pacienteId" => $this->pacienteId
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }

            }else{
                //Enviamos error en caso de que no exista.
                return $_respuestas->error_401("El token que envio es invalido o ha caducado");
            }

        }

    }

    //Método para modificar pacientes.
    private function modificarPaciente(){

        //Creamos la query para modificar los datos dentro de la tabla pacientes.
        $query = "UPDATE " . $this->table . " SET Nombre ='" . $this->nombre . "', Direccion = '" . $this->direccion . "', DNI = '" . $this->dni . "', CodigoPostal = '" . $this->codigoPostal . "', Telefono = '" . $this->telefono . "', Genero ='" . $this->genero . "', FechaNacimiento = '" . $this->fechaNacimiento . "', Correo = '" . $this->correo . "' WHERE PacienteId = '" . $this->pacienteId . "'";
        //Imprimimos query para comprobar desde sql.
        // print_r($query);

        //Creamos variable respuesta.
        $resp = parent::nonQuery($query);
        //Evaluamos las filas afectadas.
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }

    //Método para eliminar Paciente
    public function delete($json){
        //Instanciamos la clase respuestas.
        $_respuestas = new respuestas;
        //Definimos una variable para decodificar el Json y transformarlo en array asociativo.
        $datos = json_decode($json, true);


        //Aquí ya que incluimos el token debemos comprobar si existe.
        if(!isset($datos['token'])){

            //En caso de no existir el token devolvemos un error (error nuevo).
            return $_respuestas->error_401();

        }else{
            //En caso de que todo esté bien, esté puesto el token.

            //Definimo una variable para el token.
            $this->token = $datos['token'];

            //Definimos otra variable en la que almacenaremos todos los datos que vengan de la tabla del token.
            $arrayToken = $this->buscarToken();

            //Evaluamos si existen datos dentro de arrayToken
            if ($arrayToken) {

                //Ahora pasamos a validar si los datos que nos envían poseen los campos requeridos para hacer un insert.
                //Para pacientes DNI, Nombre y correo aunque tengan más.
                if(!isset($datos['pacienteId'])){
                    //En caso de error.
                    return $_respuestas->error_400();
                }else{
                    //Igualamos los campos a los valores (es el único que estamos validando):
                    $this->pacienteId = $datos['pacienteId'];

                    //Ejecutamos el método para modificar el paciente.
                    $resp = $this->eliminarPaciente();

                    //Evaluamos si nos devuelve la modificación correcta
                    if($resp){
                        //En caso de hacerlo, asignamos en la variable respuesta la propiedad de la clase respuetas response.
                        $respuesta = $_respuestas->response;
                        //Dentro de response que ahora es respuestas, en el campo de result que es un array vacío, le asignaremos a ese array vació el pacienteId, que será igual a resp que es el que nos trae el id debido al nonqueryId.
                        $respuesta["result"] = array(
                            "pacienteId" => $this->pacienteId
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }

            }else{
                //Enviamos error en caso de que no exista.
                return $_respuestas->error_401("El token que envio es invalido o ha caducado");
            }

        }

    }

    //Método para eliminar Paciente
    private function eliminarPaciente(){
        //Creamos query para ejecutar con ayuda del padre (Conexión).
        $query = "DELETE FROM " . $this->table . " WHERE PacienteId = '" . $this->pacienteId . "'";

        //Ejecutamos el método nonQuery del padre que nos devuelve la cantidad de filas afectadas.
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }

    //Método para buscar token
    private function buscarToken(){
        //Creamos una query para evaluar si el token corresponde al igual que los demás datos y si esta está activo o no.
        $query = "SELECT  TokenId,UsuarioId,Estado from usuarios_token WHERE Token = '" . $this->token . "' AND Estado = 'Activo'";

        //Enviamos la query.
        $resp = parent::obtenerDatos($query);

        if ($resp) {
            //en caso de existir el token, vamos a devolver.
            return $resp;
        }else{
            return 0;
        }

    }

    //Método para actualizar el token
    //esto lo debemos hacer pues cada vez que haga una consulta el usuario o la aplicación que consuma nuestra API hay que acutualizar nuestro usuario para saber que sigue activo.
    private function actualizarToken($tokenId){
        //Definimos un formato de fecha, en este caso la H respresenta el formato de hora de 25H.
        $date = date("Y-m-d H:i");
        //Creamos una query que nos permita actualizar la fecha del token del usuario
        $query = "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenId' ";
        $resp = parent::nonQuery($query);
        if ($resp >= 1) {
            return $resp;
        }else{
            return 0;
        }
    }
}



?>