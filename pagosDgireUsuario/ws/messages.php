<?php

function getMessage($error){
	global $message;
	
	if(!isset($message[$error])){
		
		return array(
			'error' => 1
			,'num_error' => "A000"
			,'message' => "Error en el webservice comuniquese con el administrador"
		);
		
	}
	
	return array(
		'error' => 1
		,'num_error' => $error
		,'message' => $message[$error]
	);
}

function return_data($msg){
	
	return array(
		'error' => 0
		,'num_error' => 0
		,'message' => $msg
	);
}

$message = array(
	"A001" => "Usuario o password incorrectos"
	,"D001" => "Debe de ingresar un correo valido"
	,"D002" => "La lista de conceptos no tiene el formato solicitado"
	,"D003" => "Debe de indicar un folio valido"
	
);