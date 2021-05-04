<?php

require_once('../common/validation_function.php');


function mi_valid_function($dato){
	/* validadcion del dato*/
	return true;
}


$rules = array(
	"opt" => array("name" => "opción"
					,"type" => "lstString"
					,"lst" => array('getIni', 'validClave', 'addConcepto', 'getConcepto','updConcepto', 'getExcel')
					,"error" => "Opción no valida"
					)
	,"id_concepto_pago" => array( "name" => "Identificador del concepto pago"
						,"type" => "int"
						,"error" => "Debe de ser un concepto valido"
						)
	,"nom_concepto_pago" => array( "name" => "Nombre concepto pago"
						,"type" => "textEsp"
						,"error" => "Nombre de concepto no valido"
						)
	,"id_area" => array( "name" => "Área"
						,"type" => "int"
						,"Error" => "Debe de seleccionar una área"
						)
	,"cve_tipo_con" => array( "name" => "Tipo de concepto"
						,"type" => "lstString"
						,"lst" => array('CURSO', 'OTROS', 'TRAMS')
						,"error" => "Debe de seleccionar un tipo"
						)
	,"costo_variable" => array("name" => "Costo variable"
					,"type" => "booleanInt"
					,"error" => "Debe de indicar una opción para el costo variable"
					)
	,"importe_smdf" => array("name" => "Número de salarios"
					  ,"type" => "int"
					  ,"error" => "El número de sm debe ser entero"
					  )
	,"importe_pesos" => array("name" => "Importe pesos"
					  ,"type" => "float+"
					  ,"error" => "Debe de ser un importe en pesos valido"
					  )
	,"calcula_iva" => array( "name" => "Calcula iva"
						,"type" => "lstInt"
						,"lst" => array('0', '1', '2')
						,"error" => "Debe de seleccionar una opción"
						)	
	,"cuenta" => array( "name" => "Número de cuenta"
						,"type" => "int"
						,"error" => "Debe indicar un número de cuenta valido"
						)
	,"vigente" => array( "name" => "vigente"
						,"type" => "booleanInt"
						,"error" => "Estado vigente no valido"
						)
	,"precio_unitario" => array( "name" => "Precio unitario"
						,"type" => "float+"
						,"error" => "Debe de ser un precio unitario valido"
						)
	,"ww" =>  array( "name" => "Dato de kkkprueba"
						,"type" => "float+"
						,"error" => "Debe de ser un precio unitario valido"
						)
	/* ejemplo de usos de funciones propias y expresiones regulares 
	,"nombre_dato" => array( "name" => "Nombre del dato"
						,"type" => "function"
						,"function" => "mi_valid_function"
						,"error" => "Mensaje de error"
						)
	,"nombre_dato2" => array( "name" => "Nombre del dato2"
						,"type" => "regExp"
						,"regExp" => "/^(B|\d)\d{3}$/"
						,"error" => "Mensaje de error"
						,"utf8_encode" =>true
						,"addslashes" =>true
						)
	*/

);


$required_data = array(
	
	'getIni' => array()
	,'validClave' => array('id_concepto_pago')
	,'addConcepto' => array(
		'id_concepto_pago'
		,'id_area'
		,'cve_tipo_con'
		,'nom_concepto_pago'
		,'precio_unitario'
		,'importe_smdf'
		,'calcula_iva'
		,'cuenta'
		,'vigente'
		,'costo_variable'
	)
	,'getConcepto' => array('id_concepto_pago')
	,'updConcepto' => array(
		'id_concepto_pago'
		,'id_area'
		,'cve_tipo_con'
		,'nom_concepto_pago'
		,'importe_pesos'
		,'importe_smdf'
		,'calcula_iva'
		,'cuenta'
		,'vigente'
		,'costo_variable'
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