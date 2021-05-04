<?php

require_once ("../common/config.php");
require_once ("../common/clsSolicitudes.php");
require_once ("validData.php");


$solicitudes = new clsSolicitudes();


//die("3");

if(!$solicitudes){

	$json["error"] = true;
	$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el probelma persiste comuniquese con el administrador";
	$json["debug"] = $solicitudes->getError();	
	die(json_encode($json));

}

function getIni(){
	
	global $solicitudes;
	
	$conceptos = $solicitudes->getTiposConceptos();
	
	
	
	$json["error"] = false;
	$json["data"]["conceptos"] = $conceptos->tipos_conceptos;
	
	return $json;
}

//die("5");

$json["error"] = "no";


if($opt=="getIni"){
	
	$resp = $solicitudes->getEstadoPago();
	
	if(!$resp){
		$json["error"]=true;
		$json["msg"]="sdsdfsd";
		$json["debug"]=$conceptosPago->getError();
		die(json_encode($json));
	}
	
	$json["error"]=false;
	$json["estados"] = $resp->estados;
	
	die(json_encode($json));
}



if($opt=="getDetalleSolicitud"){
	
	$resp = $solicitudes->search_detalle_solicitud(array("folio_sol" => $folio_sol));
	//$resp = $solicitudes->getDetalleSolicitud($folio_sol);
	//print_r($resp);
	
	if(!$resp){
		$json["error"]=true;
		$json["msg"]="Error #001: Error en la conexión, comuniquese con el administrador";
		$json["debug"]=$solicitudes->getError();
		die(json_encode($json));
	}
	
	$detalles = $resp->detalles;
	foreach($detalles as $id => $d){
		$detalles[$id]->importe = '$'.number_format($detalles[$id]->importe,2);
		$detalles[$id]->precio_unitario = '$'.number_format($detalles[$id]->precio_unitario,2);
		$detalles[$id]->iva = '$'.number_format($detalles[$id]->iva,2);
		$detalles[$id]->monto_tot_conc = '$'.number_format($detalles[$id]->monto_tot_conc,2);
	}
	
	$json["error"]=false;
	$json["data"]["detalles"]=$detalles;

	$resp = $solicitudes->search_solicitudes(array("folio_sol" => $folio_sol));
	if(!$resp){
		$json["error"]=true;
		$json["msg"]="Error #002: Error en la conexión, comuniquese con el administrador";
		$json["debug"]=$solicitudes->getError();
		die(json_encode($json));
	}
		
	$montos = array(
		"monto_total_iva" => '$'.number_format($resp->solicitudes[0]->monto_total_iva,2)
        ,"monto_total_sin_iva" => '$'.number_format($resp->solicitudes[0]->monto_total_sin_iva,2)
        ,"monto_total" => '$'.number_format($resp->solicitudes[0]->monto_total,2)
	);
	
	$json["data"]["montos"]=$montos;
	
	die(json_encode($json));
}






?>