<?php

session_start();

//$id_solicitante = $_SESSION["userData2"]->id_solicitante;


$folio_sol = "";
$cve_edo_pago = "";
$fnombre = "";

require_once ("../common/config.php");
require_once ("../common/clsSolicitudes.php");
require_once ("validData_grid.php");

$limit = $length;
$sidx = $order[0]["column"];
$sord = $order[0]["dir"];
									
$lstHeader = array(
0 => 'id_solicitante'
,1 => 'folio_sol'
,2 => 'ptl_ptl'
,3 => 'id_concepto_pago'
,4 => 'nom_concepto_pago'
,5 => 'cant_requerida'
,6 => ''
,7 => ''
,8 => ''
,9 => 'nom_persona_sol'
,10 => 'cve_edo_pago'
,11 => 'folio_fac'
,12 => 'folio_ticket'
,13 => 'entregado'
,14 => 'comentario_resp'
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

if($fnombre!=""){
	$lstParam["nom_persona_sol"] = $fnombre;
}



//die("xxxdddddd");

$resp = $solicitudes->detalle_solicitud($lstParam);
//die("fffdddddd");
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

//die("wwwzzzzzzddsss");


if($folio_sol!=""){
	$lstParam["folio_sol"] = $folio_sol;
}


if($cve_edo_pago!=""){
	$lstParam["cve_edo_pago"] = $cve_edo_pago;
}

if($fnombre!=""){
	$lstParam["nom_persona_sol"] = $fnombre;
}


//die("sss");
//print_r($lstParam); exit;

//echo "sss"; 
$resp = $solicitudes->detalle_solicitud($lstParam);

$json["draw"] = $draw;
$json["recordsTotal"] = $total;
$json["recordsFiltered"] = $total;

//print_r($resp); exit;

$i=0;
foreach($resp->detalles as $id => $row){
	
	$tola_sin_iva = $row->monto * $row->cant_requerida;

	$total_iva = $row->monto_iva * $row->cant_requerida;
	
	$importe=number_format(($tola_sin_iva+$total_iva)/$row->cant_requerida,2);
		
	//die("dadasdasd");
	$json["data"][$i++] = array(
		$row->id_solicitante
		,$row->folio_sol
		,$row->ptl_ptl
		,$row->id_concepto_pago
		,$row->nom_concepto_pago
		,$row->cant_requerida
		,'$'.number_format($row->monto_tot_conc,2)
		,'$'.number_format($total_iva,2)
		,'$'.number_format($tola_sin_iva,2)
		,$row->nom_persona_sol
		,$row->nom_edo_pago
		,$row->folio_fac
		,$row->folio_ticket
		,$row->entregado
		,$row->comentario_resp
	);
}


die(json_encode($json));

?>