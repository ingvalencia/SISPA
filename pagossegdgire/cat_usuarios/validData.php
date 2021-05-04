<?php

require_once('../common/validation_function.php');


function mi_valid_function($dato){
	/* validadcion del dato*/
	return true;
}


$rules = array(
	"opt" => array("name" => "opción"
					,"type" => "lstString"
					,"lst" => array('getIni', 'existLogin', 'getUsuario', 'addUsuario', 'updUsuario', 'getExcel')
					,"error" => "Opción no valida"
					)
	,"id_usuario" => array( "name" => "Identificador del usuario"
						,"type" => "int"
						,"error" => "Debe de indicar el id del usuario"
						)
	,"id_area" => array( "name" => "Área"
						,"type" => "int"
						,"Error" => "Debe de seleccionar una área"
						)
	,"id_rol" => array( "name" => "Rol"
						,"type" => "int"
						,"Error" => "Debe de seleccionar un roll"
						)
	,"vigente" => array( "name" => "Vigente"
						,"type" => "booleanInt"
						,"Error" => "Debe de indicar la vigencia"
						)
	,"nombre_usr" => array( "name" => "Tipo de concepto"
						,"type" => "textEsp"
						,"error" => "Debe de indicar el nombre del usuario"
						)
	,"ap_pat_usr" => array("name" => "Apellido paterno"
					,"type" => "textEsp"
					,"error" => "Debe de ser un apellido valido"
					)
	,"ap_mat_usr" => array("name" => "Apellido materno"
					,"type" => "textEsp"
					,"error" => "Debe de ser un apellido valido"
					)
	,"login" => array("name" => "Login"
					,"type" => "login"
					,"error" => "Debe de ser un login valido"
					)
	,"passwd_ch" => array("name" => "Cambiar password"
					,"type" => "lstString"
					,"lst" => array('on', 'off')
					,"error" => "Debe indicar si cambia el password"
					)
	,"passwd" => array("name" => "Password"
					  ,"type" => "passwordEmpty"
					  ,"error" => "Debe de ser un password valido"
					  )
	,"confirm_passwd" => array("name" => "Confirm password"
					  ,"type" => "passwordEmpty"
					  ,"error" => "Debe de ser un password valido"
					  )
	

);


$required_data = array(
	
	'getIni' => array()
	,'getUsuario' => array('id_usuario')
	,'getExcel' => array('nombre', 'id_area')
	,'addUsuario' => array(
		'opt'
		,'nombre_usr'
		,'ap_pat_usr'
		,'ap_mat_usr'
		,'login'
		,'passwd'
		,'confirm_passwd'
		,'id_area'
		,'id_rol'
		,'vigente'
		,'passwd_ch'
	)
	,'updConcepto' => array(
		'opt'
		,'id_usuario'
		,'nombre_usr'
		,'ap_pat_usr'
		,'ap_mat_usr'
		,'login'
		,'passwd'
		,'confirm_passwd'
		,'id_area'
		,'id_rol'
		,'vigente'
		,'passwd_ch'
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