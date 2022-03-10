<?php

//Ponemos archivos necesarios:
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';

//Aquí heredamos la clase conexión a turista
class auth extends conexion{

    //Creamos una función que a futuro servirá como login.
    public function login($json){

        //Instanciamos respuestas.
        $_respuestas = new respuestas;
        //Convertir JSON a array asociativo.
        $datos = json_decode($json,true);
        //Preguntamos si los campos que se estan enviando al api poseen campo usuario y campo password, así pudiendo ejecutar lo que queremos.
        if(!isset($datos['usuario']) || !isset($datos["password"])){
            //error con los campos.
            return $_respuestas->error_400();
        }else{
            //Todo esta bien.
            $usuario = $datos['usuario'];
            $password = $datos['password'];
            $password = parent::encriptar($password);

            //Llamamos al método de esta misma clase para saber si este correo si posee datos y obtenerlos en caso de que si.
            $datos = $this->obtenerDatosUsuario($usuario);
            //Evaluamos si tiene datos.
            if($datos){
                //Verificamos si la contraseña es igual.
                if($password == $datos[0]['Password']){
                    //Verificamos si el usuario está activo.
                    if($datos[0]['Estado'] == "Activo"){
                        //Creamos token
                        $verificar = $this->insertarToken($datos[0]['UsuarioId']);
                        //Ahora preguntamos si se guardó el token.
                        if($verificar){
                            //Si se guardó, aprovechamos para utilizar la propiedad response de respuestas.
                            //Creamos una variable result que será la que recibe todos los datos del response de la clase respuestas.
                            $result = $_respuestas->response;
                            //Ahora modificamos los datos que trajimos de respuestas para asignarle al campo result un array que tenga el token.
                            $result["result"] = array(
                                "token" => $verificar
                            );
                            return $result;
                        }else{
                            //Si no se guardó.
                            return $_respuestas->error_500("Error interno, no hemos podido guardar");
                        }
                    }else{
                        //En caso de que el usuario esté inactivo.
                        return $_respuestas->error_200("El usuario $usuario está inactivo");
                    }

                }else{
                    //En caso de que la contraseña no sea igual.
                    return $_respuestas->error_200("El password es inválido");
                }
            }else{
                //Si no existe el usuario.
                return $_respuestas->error_200("El usuario $usuario no existe");
            }

        }
    }

    //Creamos método para obtener los datos del usuario.
    private function obtenerDatosUsuario($email){
        //Realizamos la consulta para obtener el id, el estado y el password del usuario mediante el email.
        $query = "SELECT UsuarioId,Password,Estado FROM usuarios WHERE Usuario = '$email'";
        //En la variable datos solicitamos un método de este.
        $datos = parent::obtenerDatos($query);
        //Preguntamos si existe realmente el correo, datos nos devuelve un array por ende preguntamos si en la posición 0 existe un campo que se llama UsuarioId
        if(isset($datos[0]['UsuarioId'])){
            return $datos;
        }else{
            return 0;
        }
    }

    //Método encargado de generar token.
    private function insertarToken($usuarioId){
        //Creamos una variable para los parámetros debido a que no hacepta el dato directo.
        $val = true;
        //Generamos token mediante 2 funciones de php: 1- bin2hex (Nos devuelve un string hexadecimal numeros 1-9 y letras A-F) 2- openssl_radnom_pseudo_bytes (nos genera una cadena de bytes pseudo aleatoria).
        $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
        //Ahora procedemos a crear la fecha con el formato (año, mes, días, horas y minutos) debido a que la DB en su campo date lo recibe con este formato.
        $date = date("Y-m-d H:i");
        //Creamos una variable más que servirá para el estado del token.
        $estado = "Activo";
        //Creamos un query para insertar el token dentro de la tabla usuarios_token.
        $query = "INSERT INTO usuarios_token (UsuarioId,Token,Estado,Fecha) VALUES ('$usuarioId','$token','$estado','$date')";
        //Creamos una variable para verificar si fue agregado o no el valor.
        $verifica = parent::nonquery($query);
        if($verifica){
            //En caso de haberse guardado retornamos token.
            return $token;
        }else{
            //En caso de no haberse guardado retornamos 0.
            return 0;
        }

    }

}


?>