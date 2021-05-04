<?php

require_once ("../common/config.php");
require_once ("../common/clsSolicitudes.php");

//die("1");

foreach($_POST as $k => $val){
	$var = "\$" . $k . "=0;";
	eval($var);
	$var = "\$ref=&$" . $k . ";";
	eval($var);
	$ref = addslashes($val);
}

//die("2");

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
	
	//$p = new clsConceptosPago();
	
	$resp = $solicitudes->getEstadoPago();
	$edo_pago = $resp->estados;
	$resp = $solicitudes->getEstadoSolicitud();
	$edo_sol = $resp->estados;
	$resp = $solicitudes->getConceptosPago();
	$conceptos = $resp->conceptos;
	$resp = $solicitudes->getAreas();
	$areas = $resp->areas;
	
	
	
	if(!$resp){
		$json["error"]=true;
		$json["msg"]="sdsdfsd";
		$json["debug"]=$conceptosPago->getError();
		die(json_encode($json));
	}
	
	$json["error"]=false;
	$json["edo_pago"] = $edo_pago;
	$json["edo_sol"] = $edo_sol;
	$json["conceptos"] = $conceptos;
	$json["areas"] = $areas;
	
	die(json_encode($json));
}



if($opt=="getDetalleSolicitud"){
	
	$resp = $solicitudes->getDetalleSolicitud($folio_sol);
	
	if(!$resp){
		$json["error"]=true;
		$json["msg"]="sdsdfsd";
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
		$json["msg"]="sdsdfsd";
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