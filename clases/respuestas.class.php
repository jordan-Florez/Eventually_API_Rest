<?php

class respuestas{

	//Creamos una propiedad y le asignamos un array.
	public $response = [
		//Este array tendrá 2 filas
		'status' => "ok",
		//La segunda será un array vacío.
		"result" => array()
	];

	//Ahora creamos una función que cuando el usuario envía una solicitud a nuestra API pero con un método no permitido.
	public function error_405(){

		//Aquí definimos que el status lo pase de "okay" a "error" para usarlo en donde sepamos que hay error.
		$this->response['status'] = "error";
		//Y ahora en vez de enviar un array vacío para la segunda fila, enviamos algunos errores.
		$this->response['result'] = array(
			"error_id" => "405",
			"error_msg" => "Método no permitido"
		);
		return $this->response;
	}

	//Creamos otra función de error para el caso en el que pase la solicitud de manera correcta pero con datos incorrectos.
	//Cabe resaltar que el parámetro al sestar definido se convierte en opcional.
	public function error_200($valor = "Datos incorrectos"){

		//Aquí definimos que el status lo pase de "okay" a "error" para usarlo en donde sepamos que hay error.
		$this->response['status'] = "error";
		//Y ahora en vez de enviar un array vacío para la segunda fila, enviamos algunos errores.
		$this->response['result'] = array(
			"error_id" => "200",
			"error_msg" => $valor
		);
		return $this->response;
	}

	public function error_400(){
		//Aquí definimos que el status lo pase de "okay" a "error" para usarlo en donde sepamos que hay error.
		$this->response['status'] = "error";
		//Y ahora en vez de enviar un array vacío para la segunda fila, enviamos algunos errores.
		$this->response['result'] = array(
			"error_id" => "400",
			"error_msg" => "Datos enviados incompletos o con formato incorrecto"
		);
		return $this->response;
	}

	//Creamos un nuevo método de error que hace referencia a un error interno del servidor (lo usamos en token).
	public function error_500($valor = "Error interno del servidor."){

		//Aquí definimos que el status lo pase de "okay" a "error" para usarlo en donde sepamos que hay error.
		$this->response['status'] = "error";
		//Y ahora en vez de enviar un array vacío para la segunda fila, enviamos algunos errores.
		$this->response['result'] = array(
			"error_id" => "500",
			"error_msg" => $valor
		);
		return $this->response;
	}

	//Creamos un nuevo método de error que hace referencia a un error interno del servidor (lo usamos cuando comprobamos si exsite el token).
	public function error_401($valor = "No autorizado"){

		//Aquí definimos que el status lo pase de "okay" a "error" para usarlo en donde sepamos que hay error.
		$this->response['status'] = "error";
		//Y ahora en vez de enviar un array vacío para la segunda fila, enviamos algunos errores.
		$this->response['result'] = array(
			"error_id" => "401",
			"error_msg" => $valor
		);
		return $this->response;
	}




}

?>
