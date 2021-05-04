<?php

include_once ("../common/config.php");
include_once ("../common/clsSolicitantes.php");
include_once ("../common/clsParametros.php");
include_once ("../js/recaptcha/recaptchalib.php");
include_once ("../common/clsLog.php");

//require_once ("validData.php");

session_start();


foreach($_POST as $k => $val){
	$var = "\$" . $k . "=0;";
	eval($var);
	$var = "\$ref=&$" . $k . ";";
	eval($var);
	$ref = addslashes(utf8_decode($val));
}


$solicitantes = new clsSolicitantes();
	
if(!$solicitantes){

	$json["error"] = "si";
	$json["msg"] = "Error A001: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
	$json["debug"] = $solicitantes->getError();	
	die(json_encode($json));

}

if($opt == "getIni"){
	
	$resp = $solicitantes->getPerfil();
	if(!$resp){
		$json["error"] = true;
		$json["msg"] = "Error A002: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
		$json["debug"] = $solicitantes->getError();	
		die(json_encode($json));
	}
	
	$json["error"] = false;
	die(json_encode($json));
}

if($opt == "checkCorreoSol"){
		
	$resp = $solicitantes->checkCorreoSolRegistrado($correo_e);
	
	if(!$resp){
		$json["error"] = true;
		$json["msg"] = "Error A003: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
		$json["debug"] = $solicitantes->getError();	
		die(json_encode($json));
	}
	
	
	$json["error"] = false;
	$json["exist"] = $resp->exist;
	die(json_encode($json));
}


if($opt == "recoveryPassword"){
		
	/*enviar cadena por correo*/	
	
	#Declaro la ruta base
    global $CONFIG;

    $baseUrlUsuario= $CONFIG->rutas->baseDir;

	$resp = $solicitantes->recoveryPassword($correo_e,$baseUrlUsuario);

	if(!$resp){
		
		$salto="\r\n";
		
		$log = new clsLog($logfile='error_recoveryPassword.log');
		$log_message = ' El usuario <'.$correo_e.'>'.' No se le pudo enviar su recoveryPassword'.$salto.'Error : en la Cls:recoveryPassword'.$salto;
		$log->writeLog($log_message);
		
		$json["error"] = true;
		$json["msg"] = "Error A004: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
		$json["debug"] = $solicitantes->getError();	
		die(json_encode($json));
	}
    
	$json["error"] = false;
    $json["msg"] = "Fue enviado el correo";
	$json["correo_e"] =$correo_e;
	die(json_encode($json));
   
}


if($opt == "changePassword"){
		
	/*enviar datos para cambio de contraseña*/

    $correo_e = $_REQUEST['email'];
    $cadena_valida = $_REQUEST['cadena_val'];
    $passwd = $_REQUEST['passwd'];

    $resp = $solicitantes->changePassword($correo_e,$cadena_valida,$passwd);


    if(!$resp){
        $json["error"] = true;
        $json["msg"] = "Error A005: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
        $json["debug"] = $solicitantes->getError();
        die(json_encode($json));
    }

    $solicitantes->commitMysql();

    $json["error"] = false;
    $json["query"] = $solicitantes->getLastQuery();
    $json["msg"] = "Fue cambiada la contraseña";
    $json["correo_e"] =$correo_e;
    $json['resp']=$resp;
    die(json_encode($json));

}




?>