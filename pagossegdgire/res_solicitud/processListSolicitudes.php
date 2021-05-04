<?php

session_start();

require_once ("../common/config.php");
require_once ("../common/clsSolicitudes.php");

$folio_sol = "";
if(isset($_POST["folio_sol"])){
	$folio_sol = $_POST["folio_sol"];
}

$cve_edo_pago = "";
if(isset($_POST["cve_edo_pago"])){ 
	$cve_edo_pago = $_POST["cve_edo_pago"];
}

$fnombre = "";
if(isset($_POST["fnombre"])){ 
	$fnombre = $_POST["fnombre"];
}

$order = $_POST["order"];
$start = $_POST["start"];
$limit = $_POST["length"];
$draw = $_POST["draw"];

$sidx = $order[0]["column"];
$sord = $order[0]["dir"];

$lstHeader = array(
0 => "id_solicitante"
,1 => "folio_sol"
,2 => ""
,3 => "cve_edo_pago"
,4 => "rfc"
//,4 => "fec_actualizacion"
,5 => "fec_sol"
,6 => ""
,7 => "comentario"
,8 => "referencia_ban"
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

$lstParam = array("getCount" => true);

if($folio_sol!=""){
	$lstParam["folio_sol"] = $folio_sol;
}

if($fnombre!=""){
	$lstParam["fnombre"] = $fnombre;
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

if($fnombre!=""){
	$lstParam["fnombre"] = $fnombre;
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
		$row->id_solicitante
		,$row->folio_sol
		,$row->fec_actualizacion
		,$row->nom_edo_pago
		,$row->nom_edo_sol
		,$row->nombre_completo
		,$row->correo_e
		,$row->telefono
		,$row->factura_text
		,$row->monto_total
		,$row->monto_total_iva
		,$row->monto_total_sin_iva
		,$row->serie_fac
		,$row->rfc
		,$row->folio_fac
		,$row->folio_ticket
		,$row->aclaracion
		,$row->comentario
		,$row->referencia_ban
	);
}


die(json_encode($json));

?>