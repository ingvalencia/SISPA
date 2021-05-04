<?php

require_once('../common/validation_function.php');


function mi_valid_function($dato){
	/* validadcion del dato*/
	return true;
}


$rules = array(
    
                            "opt" => array(    "name" => "opción"
                                                        ,"type" => "lstString"
                                                        ,"lst" => array('getIni', 'validEmail', 'validRFC', 'addSolicitante', 'getColonia')
                                                        ,"error" => "Opción no válida"
                                            )
                            
                            /*validEmail*/
                            ,"correo_e" => array(      "name" => "Correo electronico"
                                                                    ,"type" => "email"
                                                                    ,"error" => "Debe de indicar un correo electrónico válido"
                                                    )
                            
                            /*validRFC*/
                            ,"rfc" => array(    "name" => "RFC de facturación"
                                                         ,"type" => "rfc"
                                                         ,"error" => "Debe de ser un RFC válido"
                                            )
                            
                            /*addSolicitante*/
                            ,"id_perfil" => array(  "name" => "Identificador del perfil"
                                                                ,"type" => "lstString"
                                                                ,"lst" => array('1','2','3','4')
                                                                ,"error" => "Debe de indicar el perfil"
                                                    )
                            /*
                             ,"ptl_ptl" => array(   "name" => "Clave del plantel"
                                                                ,"type" => "ptl"
                                                                 ,"error" => "Debe de indicar la clave del plantel"
                                                    )
                            
                             ,"exp_unam" => array(   "name" => "Clave del plantel"
                                                                ,"type" => "exp"
                                                                 ,"error" => "Debe de indicar la clave del plantel"
                                                    )
                             
                            ,"nombre" => array(    "name" => "Tipo de concepto"
                                                                        ,"type" => "email"
                                                                        ,"error" => "Debe de indicar el nombre del usuario"
                                                            )
                            */
                            
                            /*getColonia*/
                            ,"id_cp" => array(    "name" => "Id de código postal"
                                                            ,"type" => "cp"
                                                            ,"error" => "Debe de indicar un código postal válido"
                                                )
                            
                          
);


$required_data = array(
                                        'getIni' => array()
                                        ,'validEmail' => array('correo_e')
                                        ,'validRFC' => array('rfc')
                                        ,'addSolicitante' => array(   'id_perfil'
                                                                                       /* 
                                                                                      ,'ptl_ptl'
                                                                                      ,'exp_unam'
                                                                                      ,'nombre  '*/
                                                                          
                                                                            )
                                        ,'getColonia' => array('id_cp')
	
	
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