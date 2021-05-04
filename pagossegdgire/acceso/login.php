<?php


include_once ("../common/config.php");
include_once ("../common/clsUsuario.php");

$json["error"]="no";
$json["sucess"]="no";
$json["msg"]='<span style="font-size:14px; color:#A60A0A;">Usuario o contraseña incorrectos</span>';


if(!isset($_POST["usuario"])){
	//echo 1;
	die(json_encode($json));	
}

if($_POST["usuario"]==""){
	//echo 2;
	die(json_encode($json));	
}

if(!isset($_POST["password"])){
	//echo 3;
	die(json_encode($json));	
}

if($_POST["password"]==""){
	//echo 4;
	die(json_encode($json));	
}


$usuario = addslashes($_POST["usuario"]);
$password = addslashes($_POST["password"]);

//die("1");
$user= new clsUsuario();
//die("2");
//echo $usuario.":".$password;

$resp = $user->isUserValid($usuario, $password);

if(!$resp){
	$json["error"] = "si";
	$json["msg"] = "Error comuniquese con el administracor";
	die(json_encode($json));
}

if(!$resp->valid){
	die(json_encode($json));
}

$resp = $user->getUserInfo($usuario);

//die($resp->datos->idRol);

//die("ww");
//print_r($resp->datos); exit;

//print_r($resp); exit;

/*
if($resp->user->id_rol!=1){
	$json["error"] = "si";
	$json["msg"] = "Acceso restringido, no cuenta con los permisos necesarios";
	die(json_encode($json));
}
*/

session_start();

$_SESSION["access"]=1;

$_SESSION["userData"]=$resp->user;
//die("ww");
$json["msg"]='<span style="font-size:14px; color:#043c6d;">Usuario y contraseña correctos</span>';
$json["url"]="../inicio/index.php";
$json["sucess"]="si";
die(json_encode($json));


?>