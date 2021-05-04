<?php

require_once('../common/validation_function.php');


function mi_valid_function($dato){
	/* validadcion del dato*/
	return true;
}


$rules = array(
	"opt" => array("name" => "opción"
					,"type" => "lstString"
					,"lst" => array('getIni', 'getDetalleSolicitud')
					,"error" => "Opción no valida"
					)
	,"folio_sol" => array( "name" => "Folio de solicitud"
						,"type" => "int"
						,"error" => "Debe de indicar un folio de solicitud valido"
						)	
);


$required_data = array(
	'getIni' => array()
	,'getDetalleSolicitud' => array('folio_sol')
);
	
$opt="not_option";

foreach($_POST as $validName => $validData){
	
	if(isset($rules[$validName])){
		
		if(validField($validName, $validData)||($validData=="")){
			$var = "\$" . $validName . "=0;";
			eval($var);
			$var = "\$ref=&$" . $validName . ";";
			eval($var);
			
			if(isset($rules[$validName]["utf8_encode"])){
				$validData = utf8_decode($validData);
			}
			
			if(isset($rules[$validName]["addslashes"])){
				$validData = addslashes($validData);
			}
			
			$ref = $validData;
		
		}
		else{
			$json["error"] = true;
			$json["msg"] = $rules[$validName]["error"];
			die(json_encode($json));
		}
		
	}
}

if($opt == "not_option"){
	$json["error"] = true;
	$json["msg"] = "operación no valida";
	die(json_encode($json));
}


/* validar datos requerdos */
if(isset($opt)&&isset($required_data[$opt])){
	foreach($required_data[$opt] as $key => $name_data){
	
		if(!isset($_POST[$name_data])){
			$json["error"] = true;
			$json["msg"] = "No se puede continuar con la operación hacen falta datos";
			//$json["debug"] = $rules[$name_data]["name"];
			die(json_encode($json));
		}
		
	}

}


?>