<?php

require_once('../common/validation_function.php');


function mi_valid_function($dato){
	/* validadcion del dato*/
	return true;
}


$rules = array(

	"folio_sol" => array( "name" => "Folio de pago"
						,"type" => "int"
						,"Error" => "x"
						)
	,"cve_edo_pago" => array( "name" => "Clave estado de pago"
						,"type" => "lstString"
						,"lst" => array("ACDP", "CAN", "DENV", "FIENV", "PFIN", "PSOL")
						,"Error" => "x"
						)
	,"order" => array( "name" => "ordenar"
						,"type" => "array_grid"
						,"error" => "x"
						)
	,"start" => array("name" => "inicio"
					,"type" => "int"
					,"error" => "x"
					)
	,"length" => array("name" => "Número de renglones"
					  ,"type" => "int"
					  ,"error" => "x"
					  )
	,"draw" => array("name" => "página"
					  ,"type" => "int"
					  ,"error" => "x"
					  )

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
			
			$json["draw"] = 1;
			$json["recordsTotal"] = 0;
			$json["recordsFiltered"] = 0;
			$json["data"] = array();
			die(json_encode($json));
			
		}
		
	}
}


?>