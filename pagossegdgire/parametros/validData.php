<?php

require_once('../common/validation_function.php');


function mi_valid_function($dato){
	/* validadcion del dato*/
	return true;
}


$rules = array(
	"opt" => array("name" => "opción"
					,"type" => "lstString"
					,"lst" => array(
					'getIni'
					,'validUser'
					,'getParametros'
					,'updParametros'
					,'update_cveserie'
					,'update_iva'
					,'update_smdf'
					,'update_apertura_sistema'
					,'update_cierre_sistema'
					,'update_apertura_sistema_temporal'
					)
					,"error" => "Opción no valida"
					)
	,"passwd" => array("name" => "Password"
					  ,"type" => "password"
					  ,"error" => "Debe de ser un password valido"
					  )
	,"cveserie" => array( "name" => "clave de serie"
						,"type" => "text_clave"
						,"error" => "Debe de indicar la clave de la serie"
						)
	,"smdf" => array( "name" => "Valor salario minimo"
						,"type" => "float+"
						,"error" => "Debe de indicar el valor del salario minimo valido"
						)
	,"iva" => array( "name" => "Porcentaje del iva"
						,"type" => "float+"
						,"error" => "Debe de indicar el valor del iva"
						)
	,"date" => array( "name" => "Fecha"
						,"type" => "regExp"
						,"regExp" => "/^\d\d-\d\d-\d\d\d\d\s\d\d:\d\d$/"
						,"error" => "Debe indicar una fecha valida"
						)
	,"apertura_sistema_temporal" => array( "name" => "Fecha"
						,"type" => "regExp"
						,"regExp" => "/^\d\d-\d\d-\d\d\d\d\s\d\d:\d\d$/"
						,"error" => "Debe indicar una fecha de apertura"
						)
	,"cierre_sistema_temporal" => array( "name" => "Fecha"
						,"type" => "regExp"
						,"regExp" => "/^\d\d-\d\d-\d\d\d\d\s\d\d:\d\d$/"
						,"error" => "Debe indicar una fecha de cierre"
						)
);


$required_data = array(
	
	'getIni' => array()
	,'getParametros' => array()
	,'validUser' => array('passwd')
	,'updParametros' => array(
		'cveserie'
		,'iva'
		,'smdf'
	)
	,'update_cveserie' => array('cveserie')
	,'update_iva' => array('iva')
	,'update_smdf' => array('smdf')
	,'update_apertura_sistema' => array('date')
	,'update_cierre_sistema' => array('date')
	,'update_apertura_sistema_temporal' => array('apertura_sistema_temporal', 'cierre_sistema_temporal')
	
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