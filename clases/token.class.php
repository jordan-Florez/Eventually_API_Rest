<?php

    //Pedimos la conexión.
    require_once 'conexion/conexion.php';

    //Definimos la clase.
    class token extends conexion{

        //Creamos un método público que nos permitirá actualizar el token.
        public function actualizarToken($fecha){

            //Query que nos permite actualizar la tabla del token.
            $query = "UPDATE usuarios_token SET Estado = 'Inactivo' WHERE Fecha < '$fecha' AND Estado = 'Activo'";
            //Con lo anterior podríamos tener un problema pues estaríamos desactivando absolutamente todos los tokens incluso los que esten inactivos, porque hay algunos que tendrán la fecha menor a la fecha actual, para solucionar eso se debe de: poner un AND Estado = 'Activo';

            //en caso de que queramos ejecutarlo en más tiempo simplemente debemos sumarle ese tiempo a la fecha obtenida.

            //Enviamos query al padre.
            $verificar = parent::nonQuery($query);

            //Preguntamos si existen filas afectadas.
            if ($verificar > 0) {
                //retornamos un true.
                return 1;
            }else{
                //retornamos un false.
                return 0;
            }

        }

    }

?>