<?php

include_once ("../common/config.php");
include_once ("../common/clsUsuario.php");
include_once ("../common/clsLog.php");
include_once ("../common/messages.php");


if(!isset($_POST["usuario"])){

	die(json_encode($json));
}

if($_POST["usuario"]==""){

	die(json_encode($json));
}

if(!isset($_POST["password"])){

	die(json_encode($json));
}

if($_POST["password"]==""){

	die(json_encode($json));
}


$usuario = addslashes($_POST["usuario"]);
$password = addslashes($_POST["password"]);


$user= new clsUsuario();

$resp = $user->isUserValid($usuario, $password);


if(!$resp){

    $json = ($lst_message->lg_user);
	die(json_encode($json));
}


if($resp->valid == 1){

    #Se crea la información del usuario

    $resp = $user->getUserInfo2($usuario);

    $nuevo_arreglo = array();
    $arreglo = get_object_vars($resp);

    foreach( $arreglo as $indice=>$valor )
    {
        $nuevo_arreglo[] = $valor->nombre_usr;

    }

    $nombre = $nuevo_arreglo[1];

    session_start();

    $_SESSION['tiempo']=time();
    $_SESSION["access"]=1;
    $_SESSION["userData2"]=$resp->user;


	#Logueo en Bitacora

    $salto="\r\n";

    $log = new clsLog($logfile='modulo_acceso.log');
    $log_message = ' Login exitoso para usuario : '.'< '.$usuario. ' - '.utf8_decode($nombre).' >'.$salto;
    $log->writeLog($log_message);


    #Respuesta de exito

    $json = ($lst_message->lg_sucess_user);

	die(json_encode($json));

}

if($resp->valid == 0 && $resp->consulta ==0){

    $json = ($lst_message->lg_user_valid);

    die(json_encode($json));

}

if($resp->vigente == 0){

    $json = ($lst_message->lg_user_vigente);

    die(json_encode($json));

}


?>
