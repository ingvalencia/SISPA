<?php
session_start();

include_once ("../common/config.php");
include_once ("../common/clsUsuario.php");


foreach($_POST as $k => $val){
	$var = "\$" . $k . "=0;";
	eval($var);
	$var = "\$ref=&$" . $k . ";";
	eval($var);
	$ref = addslashes(utf8_decode($val));
}

$usuarios = new usuario();
//$usuarios->connect();
	
if(!$usuarios){

	$json["error"] = "si";
	$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
	$json["debug"] = $usuarios->getError();	
	die(json_encode($json));

}

if(!strcmp($opt,"getIni")){
	
	$resp = $usuarios->getRoles();
	if(!$resp){
		$json["error"] = "si";
		$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
		$json["debug"] = $usuarios->getError();	
		die(json_encode($json));
	}
	
	$json["error"] = "no";
	$json["roles"] = $resp->roles;
	die(json_encode($json));
}

if(!strcmp($opt,"addUsuario")){
	
	$resp = $usuarios->addUsuario($nombre_usr, $login, $idRol, $password);
	if(!$resp){
		$json["error"] = "si";
		$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
		$json["debug"] = $usuarios->getError();	
		die(json_encode($json));
	}
	
	$json["error"] = "no";
	$json["msg"] = "El usuario $login fue agregado";
	die(json_encode($json));
}

if(!strcmp($opt,"updateUsuario")){
	$dUsuario = array(
		"nombre_usr" => $nombre_usr
		,"idRol" => $idRol
	);
	//print_r($dUsuario); exit;
	if(!$usuarios->updateUsuario($id_usuario, $dUsuario)){
		$json["error"] = "si";
		$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
		$json["debug"] = $usuarios->getError();	
		die(json_encode($json));
	}
	
	$json["error"] = "no";
	$json["msg"] = "Los datos del usuario han sido actualizados";
	die(json_encode($json));
}


if(!strcmp($opt,"updatePassword")){

	$dUsuario = array(
		"password" => $password
	);
	//print_r($dUsuario); exit;
	if(!$usuarios->updateUsuario($id_usuario, $dUsuario)){
		$json["error"] = "si";
		$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
		$json["debug"] = $usuarios->getError();	
		die(json_encode($json));
	}
	
	$json["error"] = "no";
	$json["msg"] = "EL password del usuario a sido cambiado";
	die(json_encode($json));
}

if(!strcmp($opt,"disableUsuario")){
	
	$resp = $usuarios->disable_usuario($id_usuario);
	if(!$resp){
		$json["error"] = "si";
		$json["msg"] = "No se pudo deshabilitar";
		die(json_encode($json));
	}
	$json["error"] = "no";
	$json["msg"] = "Se ha deshabilitado el usuario";
	die(json_encode($json));
	 
}


if(!strcmp($opt,"deleteUsuario")){
	
	$resp = $usuarios->delete_usuario($id_usuario);
	if(!$resp){
		$json["error"] = "si";
		$json["msg"] = "No se pudo eliminar el usuario";
		die(json_encode($json));
	}
	$json["error"] = "no";
	$json["msg"] = "Se ha eliminado el usuario";
	die(json_encode($json));
	 
}


if(!strcmp($opt,"enableUsuario")){
	
	$resp = $usuarios->enable_usuario($id_usuario);
	if(!$resp){
		$json["error"] = "si";
		$json["msg"] = "No se pudo habilitar";
		die(json_encode($json));
	}
	$json["error"] = "no";
	$json["msg"] = "Se ha habilitado el usuario";
	die(json_encode($json));
	 
}


if(!strcmp($opt,"getUsuario")){
	
	$resp = $usuarios->getRoles();
	if(!$resp){
		$json["error"] = "si";
		$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
		$json["debug"] = $usuarios->getError();	
		die(json_encode($json));
	}
	
	$json["roles"] = $resp->roles;
	
	$resp = $usuarios->getUsuario($id_usuario);
	if(!$resp){
		$json["error"] = "si";
		$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
		$json["debug"] = $usuarios->getError();	
		die(json_encode($json));
	}
	
	//print_r($resp);
	//$r=(object)$resp->usuario;
	//$r.nompre_personaq="wwwww";
	
	$json["error"] = "no";
	$json["usuario"] = $resp->usuario;
	die(json_encode($json));
}

if(!strcmp($opt,"existUser")){
	
	$resp = $usuarios->existUser($login);
	if(!$resp){
		$json["error"] = "si";
		$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
		$json["debug"] = $usuarios->getError();	
		die(json_encode($json));
	}
	
	
	$json["error"] = "no";
	$json["exist"] = $resp->count == 0 ? "no" : "si";
	$json["id_usuario"] = $resp->id_usuario;
	die(json_encode($json));

}

if(!strcmp($opt,"upPassword")){
	
	$id_usuario = $_SESSION["userData"]->id_usuario;
	//print_r($_SESSION["userData"]);exit;
	
	$lstParam = array("password" => $password);
	
	$resp = $usuarios->updateUsuario($id_usuario, $lstParam);
	if(!$resp){
		$json["error"] = "si";
		$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
		$json["debug"] = $usuarios->getError();	
		die(json_encode($json));
	}
	
	
	$json["error"] = "no";
	$json["msg"] = "El password ha sido cambiado";
	die(json_encode($json));

}

if(!strcmp($opt,"upPasswordUser")){
	
	if($_SESSION["userData"]->id_tipo_usuario!=1){
		$json["error"] = "si";
		$json["msg"] = "No tiene permisos para realizar esta acción";
		die(json_encode($json));
	}
	
	$lstParam = array("password" => $password);
	
	$resp = $usuarios->updateUsuario($id_usuario, $lstParam);
	if(!$resp){
		$json["error"] = "si";
		$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
		$json["debug"] = $usuarios->getError();	
		die(json_encode($json));
	}
	
	
	$json["error"] = "no";
	$json["msg"] = "El password ha sido cambiado";
	die(json_encode($json));

}


?>