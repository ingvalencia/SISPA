<?php

require_once ("../common/config.php");
require_once ("../common/clsUsuario.php");
require_once ("../common/clsParametros.php");
require_once ("validData.php");

session_start();

$usr = new clsUsuario();
$parametros = new clsParametros();


//die("3");

if(!$usr){

	$json["error"] = true;
	$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
	$json["debug"] = $usr->getError();	
	die(json_encode($json));

}

//die("4");

function getIni(){
	global $usr;
	
	$areas = $usr->getAreas();
	$roles = $usr->getRoles();
	
	
	$json["error"] = false;
	$json["data"]["areas"] = $areas->areas;
	$json["data"]["roles"] = $roles->roles;
	
	return $json;
}
//die("5");

$json["error"] = "no";


if($opt=="getIni"){
	
	$json = getIni();
	die(json_encode($json));
}

if($opt=="validUser"){
	
	$login = $_SESSION["userData"]->login;
	
	$resp = $usr->isUserValid($login, $passwd);
	
	//print_r($resp); exit;
	
	$json["resp"]=$resp;
	
	if(!$resp){
		$json["error"] = true;
		$json["msg"] = "Error #C000: Se presento un error, comuniquese con el administrador";
		$json["debug"] = $usr->getError();	
		die(json_encode($json));
	}
	
	if($resp->valid==1){
		$json["msg"] = "Password correcto";
		$json["valid"]=true;
	}
	else{
		$json["msg"] = "Password incorrecto";
		$json["valid"]=false;
	}
	
	$_SESSION["valid_parametros"]=1;
	
	$json["error"] = false;	
	die(json_encode($json));

}

if($opt=="getParametros"){
	
	$resp = $parametros->search_parametros();
	
	
	if(!$resp){
		$json["error"] = true;
		$json["msg"] = "Error #C000: Se presento un error, comuniquese con el administrador";
		$json["debug"] = $parametros->getError();	
		die(json_encode($json));
	}

	//print_r($resp);
	
	if(!$resp->count){
		$json["error"] = false;
		$json["exist"] = false;
		$json["msg"] = "No existe el usuario";
		$json["debug"] = $usr->getError();	
		die(json_encode($json));
	}
	
	$json["error"] = false;
	$json["parametros"] = array(
		"cveserie" => $resp->parametros["cveserie"]
		,"iva" => $resp->parametros["iva"]
		,"smdf" => $resp->parametros["smdf"]
		,"apertura_sistema" => $resp->parametros["apertura_sistema"]
		,"cierre_sistema" => $resp->parametros["cierre_sistema"]
		,"apertura_sistema_temporal" => $resp->parametros["apertura_sistema_temporal"]
		,"cierre_sistema_temporal" => $resp->parametros["cierre_sistema_temporal"]
	);
	die(json_encode($json));
	
}

if($opt=="updParametros"){
	
	if($_SESSION["valid_parametros"]!=1){
		$json["error"] = true;
		$json["msg"] = "No tiene permisos para editar los parametros";
		die(json_encode($json));
	}
	
	$parametros->update_parametro("cveserie", array("valor" => $cveserie));
	$parametros->update_parametro("iva", array("valor" => $iva));
	$parametros->update_parametro("smdf", array("valor" => $smdf));
	
	$json["error"] = false;
	$json["msg"] = "Se han actualizado los parametros";
	die(json_encode($json));
}


if($opt=="update_cveserie"){
	
	if($_SESSION["valid_parametros"]!=1){
		$json = array(
			"error" => true
			,"msg" => "No tiene permisos para editar los parametros"
		);
		die(json_encode($json));
	}
	
	if(!$parametros->update_parametro("cveserie", array("valor" => $cveserie))){
		$json = array(
			"error" => true
			,"msg" => "No se ha podido actualizar la serie"
		);
		die(json_encode($json));
	}
	
	$json = array(
		"error" => false
		,"msg" => "Se ha actualizado la seria"
	);
	die(json_encode($json));
}


if($opt=="update_iva"){
	
	if($_SESSION["valid_parametros"]!=1){
		$json = array(
			"error" => true
			,"msg" => "No tiene permisos para editar los parametros"
		);
		die(json_encode($json));
	}
	
	if(!$parametros->update_parametro("iva", array("valor" => $iva))){
		$json = array(
			"error" => true
			,"msg" => "No se ha podido actualizar el IVA"
		);
		die(json_encode($json));
	}
	
	$json = array(
		"error" => false
		,"msg" => "Se ha actualizado el IVA"
	);
	die(json_encode($json));
}


if($opt=="update_smdf"){
	
	if($_SESSION["valid_parametros"]!=1){
		$json = array(
			"error" => true
			,"msg" => "No tiene permisos para editar los parametros"
		);
		die(json_encode($json));
	}
	
	if(!$parametros->update_parametro("smdf", array("valor" => $smdf))){
		$json = array(
			"error" => true
			,"msg" => "No se ha podido actualizar el SMDF"
		);
		die(json_encode($json));
	}
	
	$json = array(
		"error" => false
		,"msg" => "Se ha actualizado el SMDF"
	);
	die(json_encode($json));
}



if($opt=="update_apertura_sistema"){
	
	if($_SESSION["valid_parametros"]!=1){
		$json = array(
			"error" => true
			,"msg" => "No tiene permisos para editar los parametros"
		);
		die(json_encode($json));
	}
	
	if(!$parametros->update_parametro("apertura_sistema", array("valor" => $date))){
		$json = array(
			"error" => true
			,"msg" => "No se ha podido actualizar la apertura de sistema"
		);
		die(json_encode($json));
	}
	
	$json = array(
		"error" => false
		,"msg" => "Se ha actualizado la apertura del sistema"
	);
	die(json_encode($json));
}



if($opt=="update_cierre_sistema"){
	
	if($_SESSION["valid_parametros"]!=1){
		$json = array(
			"error" => true
			,"msg" => "No tiene permisos para editar los parametros"
		);
		die(json_encode($json));
	}
	
	if(!$parametros->update_parametro("cierre_sistema", array("valor" => $date))){
		$json = array(
			"error" => true
			,"msg" => "No se ha podido actualizar el cierre del sistema"
		);
		die(json_encode($json));
	}
	
	$json = array(
		"error" => false
		,"msg" => "Se ha actualizado el cierre del sistema"
	);
	die(json_encode($json));
}


if($opt=="update_apertura_sistema_temporal"){
	
	if($_SESSION["valid_parametros"]!=1){
		$json = array(
			"error" => true
			,"msg" => "No tiene permisos para editar los parametros"
		);
		die(json_encode($json));
	}
	
	if(!$parametros->update_parametro("apertura_sistema_temporal", array("valor" => $apertura_sistema_temporal))){
		$json = array(
			"error" => true
			,"msg" => "No se ha podido actualizar la apertura del sistema temporal"
		);
		die(json_encode($json));
	}
	
	if(!$parametros->update_parametro("cierre_sistema_temporal", array("valor" => $cierre_sistema_temporal))){
		$json = array(
			"error" => true
			,"msg" => "No se ha podido actualizar el cierre del sistema temporal"
		);
		die(json_encode($json));
	}
	
	$json = array(
		"error" => false
		,"msg" => "Se ha actualizado el cierre del sistema"
	);
	die(json_encode($json));
}




?>