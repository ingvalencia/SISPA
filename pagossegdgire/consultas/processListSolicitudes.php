<?php

session_start();

$folio_sol = "";
$cve_edo_pago = "";

require_once ("../common/config.php");
require_once ("../common/clsSolicitudes.php");
require_once ("validData_grid.php");

$limit = $length;
$sidx = $order[0]["column"];
$sord = $order[0]["dir"];

$lstHeader = array(
0 => "folio_sol"
,1 => ""
,2 => "cve_edo_pago"
,3 => "rfc"
//,4 => "fec_actualizacion"
,4 => "fec_sol"
,5 => ""
,6 => "comentario"
,7 => "referencia_ban"
);

$sidx = $lstHeader[$sidx];


$solicitudes = new clsSolicitudes();

if(!$solicitudes){
	$json["error"] = "si";
	$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
	$json["debug"] = $solicitudes->getError();
	$solicitudes->close();
	die(json_encode($json));

}

//print_r($solicitudes->getGrupo(11)); exit;
$lstParam = array("getCount" => true);
if($folio_sol!=""){
	$lstParam["folio_sol"] = $folio_sol;
}

if($cve_edo_pago!=""){
	$lstParam["cve_edo_pago"] = $cve_edo_pago;
}

$resp = $solicitudes->search_solicitudes($lstParam);

//print_r($resp); exit;

if(!$resp){
	$json["draw"] = 1;
	$json["recordsTotal"] = 0;
	$json["recordsFiltered"] = 0;
	$json["data"] = array();
	die(json_encode($json));
}

if($resp->count==0){
	$json["draw"] = 1;
	$json["recordsTotal"] = 0;
	$json["recordsFiltered"] = 0;
	$json["data"] = array();
	die(json_encode($json));
}

$total = $resp->count;


$lstParam = array(
	"sidx" => $sidx
	,"sord" => $sord
	,"start" => $start
	,"limit" => $limit
);

if($folio_sol!=""){
	$lstParam["folio_sol"] = $folio_sol;
}

if($cve_edo_pago!=""){
	$lstParam["cve_edo_pago"] = $cve_edo_pago;
}


//print_r($lstParam); exit;

//echo "sss"; 
$resp = $solicitudes->search_solicitudes($lstParam);

$json["draw"] = $draw;
$json["recordsTotal"] = $total;
$json["recordsFiltered"] = $total;

//print_r($resp); exit;

$i=0;
foreach($resp->solicitudes as $id => $row){
	
	$json["data"][$i++] = array(
		$row->folio_sol
		,'$'.number_format($row->monto_total,2)
		,$row->nom_edo_pago
		,$row->rfc
		//,$row->fec_actualizacion
		,$row->fec_sol
		,""
		,$row->comentario
		,$row->referencia_ban
	);
}


die(json_encode($json));

?>