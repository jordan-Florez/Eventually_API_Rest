<?php

    class conexion {

        //Definimos los atributos para la conexión que son las mismas del archivo config.
        private $server;
        private $user;
        private $password;
        private $database;
        private $port;

        //Creamos atributo privado para la conexión.
        private $conexion;

        //Creamos la función constructor.
        function __construct(){
            //Definimos una variable para guardar todos los datos de la conexión.
            $listaDatos = $this->datosConexion();
            //Recorremos los datos de conexión.
            foreach ($listaDatos as $key => $value) {
                //Perdimos que se iguale los atributos de la clase a los valores del array.
                $this->server = $value['server'];
                $this->user = $value['user'];
                $this->password = $value['password'];
                $this->database = $value['database'];
                $this->port = $value['port'];
            }
            //Llamamos a la instancia mysqli de la clase mysql de php, recibe como parámetro el nombre del servidor, nombre de usuario, password, nombre de la BD y por último el puerto.
            $this->conexion = new mysqli($this->server,$this->user,$this->password,$this->database,$this->port);
            //En caso de que algo falle.
            if($this->conexion->connect_errno){
                echo "Algo va mal con la conexión";
                //Función die para que no siga ejecutando nada.
                die();
            }else{
                // echo "todo joya c:";
            }

        }

        //Creamos una función privada que nos permitirá obtener los datos del archivo config y convertirlos al atributo.
        private function datosConexion(){
            //Definimos una variable que almacenará la dirección del archivo.
            $direccion = dirname(__FILE__);
            //Declaramos otra variable a la cual le llevamos la función que obtiene toda la información de un archivo y la devuelve.
            $jsondata = file_get_contents($direccion . "/" . "config");
            //Retornamos la decodificación del archivo y ponemos true para que se converta en un array asosiativo.
            return json_decode($jsondata, true);
        }

        //Convertir registros que nos envía la BD a utf-8, con esto evitamos problemas con las palabras con tilde o ñ.
        private function convertirUTF8($array){
            //Este método recibe 2 parámetros, un array y un trigged, el cual también recibe 2 parámetros. un puntero (En POO cuando pasamos un parámetro por referencia, se declara con &%"x") y una variable.
            array_walk_recursive($array,function(&$item,$key){
                //Evaluamos si el array no tiene ningún signo o carácter raro mediante mb_detect_encoding y de ser así que diga que es un UTF-8.
                if(!mb_detect_encoding($item,'utf-8',true)){
                    //En caso de que encuentre un carácter raro, lo codifica a utf-8.
                    $item = utf8_encode($item);
                }
            });
            //retornamos el array.
            return $array;
        }

        //Método para obtener los datos.
        public function obtenerDatos($sqlstr){

            //Almacenamos en una variable el resultado, ejecutamos la conexión y una consulta que es la que recibimos por parámetro.
            $results = $this->conexion->query($sqlstr);

            //Ahora queremos pasarlos uno por uno a un array vacío.
            $resultArray = array();
            foreach ($results as $key) {
                //Pedimos que nos cree una nueva fila en el array result.
                //Esto funciona a modo de array push, una abreviación.
                $resultArray[] = $key;
            }
            return $this->convertirUTF8($resultArray);

        }

        //Método guardar o nonquery.
        public function nonQuery($sqlstr){

            //Almacenamos en una variable el resultado, ejecutamos la conexión y una consulta que es la que recibimos por parámetro.
            $results = $this->conexion->query($sqlstr);

            //Este return nos devulve 1 pues representa la afectación de una fila.
            return $this->conexion->affected_rows;

        }

        //Método para guardar pero que además nos devuleve el id de la persona que guardamos.
        public function nonQueryId($sqlstr){

            //Almacenamos en una variable el resultado, ejecutamos la conexión y una consulta que es la que recibimos por parámetro.
            $results = $this->conexion->query($sqlstr);

            //Nuevamente aquí retornará con base en si hubo afectación de fila o no.
            $filas = $this->conexion->affected_rows;
            if($filas >= 1){
                return $this->conexion->insert_id;
            }else{
                return 0;
            }

        }

        //Encriptar
        //Creamos una función para encriptación.
        protected function encriptar($string){

            //Función de php que nos premite encriptar un string
            return md5($string);

        }



    }

?>