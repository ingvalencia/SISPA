<?php
session_start();

include_once ("../common/config.php");
include_once ("../common/clsSolicitantes.php");


if(isset($_REQUEST['task']) && $_REQUEST['task']=='validate_account'){
	

	$solicitantes = new clsSolicitantes();
	
	if(!isset($_REQUEST['txtEmail'])){
		header("HTTP/1.1 400 Bad Request");
		echo "Parámetros inv&aacute;lidos";
		exit;
	}else{
		$resp = $solicitantes->send_activation($_REQUEST['txtEmail'], $_REQUEST['cadena_val']);
		echo json_encode($resp);
	}
	
}else{
	
	header( "HTTP/1.1 400 Bad Request");
	echo 'Error - No se reconoce la tarea';

}

//print_r($_REQUEST['task']);


?>