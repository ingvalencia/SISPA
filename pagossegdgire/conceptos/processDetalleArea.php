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


if($opt == "saveComentarioDetalleResp"){
	
	
	$lstParam=array("comentario_resp" => $comentario_resp);
	
	$resp = $solicitudes->update_detalle_solicitud($id_solicitante, $folio_sol, $id_concepto_pago, $lstParam);
	
	if(!$resp){
		$json["error"]=true;
		$json["msg"]="No se pudo cuardar el comentario";
		$json["debug"]=$solicitudes->getError();
		$json["query"]=$solicitudes->getLastQuery();
		die(json_encode($json));
	}
	
	$json["error"] = false;
	$json["query"] = $solicitudes->getLastQuery();
	$json["msg"] = "Se ha actualizado el comentario de la solicitud <strong>$folio_sol</strong> y concepto <strong>$id_concepto_pago</strong>";
	die(json_encode($json));
	
}

if($opt=="getComentarioDetalleSolicitud"){
	
	$lstParam = array("folio_sol" => $folio_sol, "id_concepto_pago" => $id_concepto_pago);
	
	$resp = $solicitudes->search_detalle_solicitud($lstParam);
	
	if(!$resp){
		$json["error"]=true;
		$json["msg"]="sdsdfsd";
		$json["debug"]=$solicitudes->getError();
		die(json_encode($json));
	}
	
	if(!$resp->count){
		$json["error"]=false;
		$json["exist"]=false;
		$json["msg"]="No existe la solicitud";
		die(json_encode($json));
	}
	
	if($resp->detalles[0]->comentario_resp==""){
		$resp->detalles[0]->comentario_resp="";
	}
	
	if($resp->detalles[0]->comentario_UA==""){
		$resp->detalles[0]->comentario_UA="";
	}
	
	//print_r($resp);
	$json["error"]=false;
	$json["exist"]=true;
	$json["data"]["detalles"]=$resp->detalles[0];
	
	
	die(json_encode($json));
}






?>