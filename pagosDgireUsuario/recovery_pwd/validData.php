<?php

require_once('../common/validation_function.php');


function mi_valid_function($dato){
	/* validadcion del dato*/
	return true;
}


$rules = array(

                            /*Parametros del opt*/
                            "opt" => array("name" => "opción"
                                                    ,"type" => "lstString"
                                                    ,"lst" => array('getIni','checkCorreoSol','recoveryPassword','changePassword')
                                                    ,"error" => "Opción no valida"
                                            )
                            
                            /*-checkCorreoSol--*/
                            ,"correo_e" => array( "name" => "Correo electronico"
                                                                ,"type" => "email"
                                                                ,"error" => "Debe de ingresar correo electrónico valido"
                                                    )
);


$required_data = array(
                                        'getIni' => array()
                                        ,'checkCorreoSol'  => array('correo_e')
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


/* validar datos requeridos */
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