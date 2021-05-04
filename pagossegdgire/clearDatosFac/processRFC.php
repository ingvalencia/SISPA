<?php

require_once ("../common/config.php");
require_once ("../common/clsDatosFacturacion.php");


foreach($_POST as $k => $val){
	$var = "\$" . $k . "=0;";
	eval($var);
	$var = "\$ref=&$" . $k . ";";
	eval($var);
	$ref = addslashes($val);
}

//die("2");

$facturas = new clsDatosFacturacion();


//die("3");

if(!$facturas){

	$json["error"] = true;
	$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
	$json["debug"] = $facturas->getError();	
	die(json_encode($json));

}


if($opt=="deleteRFC"){
	
	$resp = $facturas->delete_datos_facturacion($id_solicitante, $rfc);
	
	if(!$resp){
		$json["error"] = true;
		$json["msg"] = "Error #E001: Se presento un error, comuniquese con el administrador";
		$json["debug"] = $facturas->getError();	
		$json["lastQuery"] = $facturas->getLastQuery();	
		die(json_encode($json));
	}
	
	$json["error"] = false;
	$json["msg"] = "Se ha eliminado el RFC";
	die(json_encode($json));

}


?>