<?php

require_once('../common/validation_function.php');


function mi_valid_function($dato){
	/* validadcion del dato*/
	return true;
}


$rules = array(

	"folio_sol1" => array( "name" => "Folio de pago"
						,"type" => "int"
						,"Error" => "x"
						)
	,"folio_sol2" => array( "name" => "Folio de pago"
						,"type" => "int"
						,"Error" => "x"
						)
	,"fec_sol1" => array( "name" => "Fecha de solicitud uno"
						,"type" => "date"
						,"Error" => "x"
						)
	,"fec_sol2" => array( "name" => "Fecha de solicitud dos"
						,"type" => "date"
						,"Error" => "x"
						)
	,"fec_actualizacion1" => array( "name" => "Fecha de actualizacion uno"
						,"type" => "date"
						,"Error" => "x"
						)
	,"fec_actualizacion2" => array( "name" => "Fecha de actualizacion dos"
						,"type" => "date"
						,"Error" => "x"
						)
	,"fec_factura1" => array( "name" => "Fecha de facturaci�n uno"
						,"type" => "date"
						,"Error" => "x"
						)
	,"fec_factura2" => array( "name" => "Fecha de facturaci�n dos"
						,"type" => "date"
						,"Error" => "x"
						)
	,"id_concepto_pago" => array( "name" => "Id concepto pago"
						,"type" => "int"
						,"Error" => "x"
						)
	,"cve_edo_sol" => array( "name" => "clave estado de sol"
						,"type" => "lstString"
						,"lst" => array('CAN', 'ENPR', 'FINZ', 'INIC')
						,"Error" => "x"
						)
	,"cve_edo_pago" => array( "name" => "clave estado de pago"
						,"type" => "lstString"
						,"lst" => array('ACDP', 'CAN', 'DENV', 'FIENV', 'PFIN', 'PSOL')
						,"Error" => "x"
						)
	,"id_area" => array( "name" => "Id �rea"
						,"type" => "int"
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
	,"length" => array("name" => "N�mero de renglones"
					  ,"type" => "int"
					  ,"error" => "x"
					  )
	,"draw" => array("name" => "p�gina"
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