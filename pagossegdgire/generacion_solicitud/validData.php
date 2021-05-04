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
					,"lst" => array('getIni','getColonia','validRFC','getMontos','addSolUsu','getPDF', 'searchRFCS', 'searchRFCPagos')
					,"error" => "Opción no valida"
					)
	
	
	/*-getColonia--*/
	,"id_cp" => array( "name" => "Id Codigo Postal"
						            ,"type" => "cp"
						            ,"error" => "Debe de ser un Codigo Postal valido"
						        )
	/*-validRFC--*/
	,"rfc" => array( "name" => "RFC"
						            ,"type" => "rfc"
						            ,"error" => "Debe de ser un rfc valido"
						        )
	/*getMontos*/
	/*
	,"clave" => array( "name" => "Clave del concepto"
						            ,"type" => "array_clave"
						            ,"error" => "Debe de ser una clave valida"
						        )
	
	,"cantidad" => array( "name" => "Cantidad del concepto"
						            ,"type" => "int"
						            ,"error" => "Debe ser una cantidad valida"
						        )
								*/
	,"precio_variable" => array( "name" => "Precio variable"
						            ,"type" => "float+"
						            ,"error" => "Error sobre el precio variable"
						        )
								
	,"correo_e" => array( "name" => "Correo electronico"
						            ,"type" => "email"
						            ,"error" => "Debe de ingresar un email valido"
						        )
	,"correo_usuario" => array( "name" => "Correo electronico"
						            ,"type" => "email"
						            ,"error" => "Debe de ingresar un email valido"
						        )
	,"slcTipoUser" => array("name" => "Tipo de usuario registrado"
					,"type" => "lstString"
					,"lst" => array('usr_reg', 'no_usr_reg')
					,"error" => "Opción no valida"
					)
	,"slcOrigenRFC" => array("name" => "RFC de facturación"
					,"type" => "rfc"
					,"error" => "Debe de ser un valido"
					)
	,"tipo_persona" => array("name" => "Tipo de persona"
					,"type" => "lstString"
					,"lst" => array('1','2')
					,"error" => "Debe de indicar el tipo de persona"
					)
	,"id_municipio" => array("name" => "Id de municipio"
					,"type" => "int"
					,"error" => "Debe de indicar el municipio"
					)
					
	,"id_edo" => array("name" => "Id de estado"
					,"type" => "int"
					,"error" => "Debe de indicar el estado"
					)
	
	,"id_ciudad" => array("name" => "Id de ciudad"
					,"type" => "int"
					,"error" => "Debe de indicar la ciudad"
					)	
	
	,"id_cp" => array("name" => "Id de código postal"
					,"type" => "int"
					,"error" => "Debe de indicar el código postal"
					)
	
	,"id_colonia" => array("name" => "Id de colonia"
					,"type" => "int"
					,"error" => "Debe de indicar la colonia"
					)
					
	,"calle" => array("name" => "Calle"
					,"type" => "textEsp"
					,"error" => "Debe de indicar una calle valida"
					)
					
	,"num_int" => array("name" => "Número interior"
					,"type" => "textEsp"
					,"error" => "Debe de indicar un número interior valido"
					)
	,"nombre_fisc" => array("name" => "Nombre fiscal"
					,"type" => "textEsp"
					,"error" => "Debe de indicar un nombre fiscal valido"
					)
	,"fnombre" => array("name" => "Nombre de persona fisica"
					,"type" => "textEsp"
					,"error" => "Debe de indicar un nombre valido"
					)
	,"fap_paterno" => array("name" => "Apellido paterno"
					,"type" => "textEsp"
					,"error" => "Debe de indicar un apellido paterno valido"
					)
	,"fap_materno" => array("name" => "Apellido materno"
					,"type" => "textEsp"
					,"error" => "Debe de indicar un apellido materno valido"
					)
	,"txtOtraCol" => array("name" => "Otra colonia"
					,"type" => "textEsp"
					,"error" => "Debe de indicar un otra colonia valida"
					)
	,"num_ext" => array("name" => "Número exterior"
					,"type" => "textEsp"
					,"error" => "Debe de indicar un úmero exterior valido"
					)
		
		
	
	/*addSolUsu*/
	
	,"clave" => array( "name" => "Clave"
						            ,"type" => "int"
						            ,"error" => "Error #1"
								)
	,"ptl_ptl" => array( "name" => "Plantel"
						            ,"type" => "ptl"
						            ,"error" => "Error #x"
								)
	,"cantidad" => array( "name" => "Cantidad"
						            ,"type" => "int"
						            ,"error" => "Error #2"
								)
	,"nombre_solicitante" => array( "name" => "Nombre solicitante"
						            ,"type" => "text_empty"
						            ,"error" => "Error #2"
								)
	
	/*
	,"precio_unitarioT" => array( "name" => "Precio unitario"
						            ,"type" => "float+"
						            ,"error" => "Error sobre el precio unitario"
								)
								*/
	,"conceptos" => array( "name" => "Conceptos"
						            ,"type" => "array_concepto"
						            ,"error" => "Error sobre los conceptos"
								)
	,"chFactura" => array( "name" => "Requiere factura"
						            ,"type" => "booleanInt"
						            ,"error" => "Error sobre el checkbox"
								)
	,"folio_sol" => array( "name" => "Folio de solicitud"
						            ,"type" => "int"
						            ,"error" => "Debe de ser un folio valido"
								)
	,"chDataRFC" => array( "name" => "Requiere factura"
						            ,"type" => "booleanInt"
						            ,"error" => "Error sobre el checkbox"
								)
								
);


$required_data = array(
	
	'getIni' => array()
	,'id_cp'  => array('id_cp')
	,'rfc'    => array('rfc')	
	,'getMontos' => array('clave'
						 ,'cantidad'
						 ,'precio_variable'
						)
	,'addSolUsu' => array('conceptos'
						,'slcTipoUser'
						,'correo_usuario'
						,'chFactura' 
						,'chDataRFC'
						,'nombre_solicitante'
						,'ptl_ptl' 
						)
	,'getPDF' => array('folio_sol')
	,'searchRFCS' => array('correo_usuario', 'slcTipoUser')
	,'searchRFCPagos' => array()
    
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
			$json["debug"] = $name_data;
			die(json_encode($json));
		}
		
	}

}


if($opt == "addSolUsu"){
	
	
	if(($chFactura == 1)&&(isset($slcTipoUser)&&($slcTipoUser == "user_reg"))){
	
		if(!isset($correo_usuario)){
			$json["error"] = true;
			$json["msg"] = "No se puede continuar con la operación hacen falta datos";
			$json["debug"] = "correo_usuario";
			die(json_encode($json));
		}
		
		
		if(!isset($slcOrigenRFC)){
			$json["error"] = true;
			$json["msg"] = "No se puede continuar con la operación hacen falta datos";
			$json["debug"] = "rfc";
			die(json_encode($json));
		}
		
	}
	
	if(($chFactura == 1)&&(isset($slcTipoUser)&&($slcTipoUser == "no_user_reg"))){
	
		$lstData = array('slcTipoUser','correo_usuario', 'rfc');
		
		foreach($lstData as $key => $name_data){
	
			if(!isset($_POST[$name_data])){
				$json["error"] = true;
				$json["msg"] = "No se puede continuar con la operación hacen falta datos";
				$json["debug"] = $name_data;
				die(json_encode($json));
			}
		}
		
		if($checkbox==1){
			$lstDataRFC = array('tipo_persona', 'nombre_fisc', 'fnombre', 'fap_paterno', 'fap_materno', 'calle', 'num_ext', 'num_int', 'id_cp'
								,'id_edo','id_ciudad','id_municipio','id_colonia','check_colonia','txtOtraCol');
								

			foreach($lstDataRFC as $key => $name_data){
	
				if(!isset($_POST[$name_data])){
					$json["error"] = true;
					$json["msg"] = "No se puede continuar con la operación hacen falta datos";
					$json["debug"] = $name_data;
					die(json_encode($json));
				}
			}

		}

	}
	
}

?>




