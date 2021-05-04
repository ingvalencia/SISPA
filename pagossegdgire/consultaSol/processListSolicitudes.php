<?php

session_start();

//$id_solicitante = $_SESSION["userData2"]->id_solicitante;


$fec_sol1 = "";
$fec_sol2 = "";
$fec_actualizacion1 = "";
$fec_actualizacion2 = "";
$fec_factura1 = "";
$fec_factura2 = "";
$folio_sol1 = "";
$folio_sol2 = "";
$id_concepto_pago = "";
$cve_edo_sol = "";
$cve_edo_pago = "";
$id_area = "";

require_once ("../common/config.php");
require_once ("../common/clsSolicitudes.php");
require_once ("validData_grid.php");


$limit = $length;
$sidx = $order[0]["column"];
$sord = $order[0]["dir"];



$lstHeader = array(
0 => 'folio_sol'
,1 => 'id_concepto_pago'
,2 => 'cuenta'
,3 => 'nom_concepto_pago'
,4 => ''
,5 => 'cant_requerida'
,6 => 'row->monto_tot_conc'
,7 => ''
,8 => ''
,9 => 'nom_edo_pago'
,10 => 'referencia_ban'
,11 => 'fec_sol'
,12 => 'fecha_sol'
,13 => 'fec_factura'
,14 => 'fec_entrega'
,15 => 'nom_persona_sol'
,16 => 'entregado'
,17 => 'nom_usr'
,18 => 'nom_area'
,19 => 'ptl_ptl'
,20 => 'folio_fac'
,21 => 'folio_ticket'
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

$lstParam2 = array();
if($fec_sol1!=""){ $lstParam2["fec_sol1"] = $fec_sol1; }
if($fec_sol2!=""){ $lstParam2["fec_sol2"] = $fec_sol2; }
if($fec_actualizacion1!=""){ $lstParam2["fec_actualizacion1"] = $fec_actualizacion1; }
if($fec_actualizacion2!=""){ $lstParam2["fec_actualizacion2"] = $fec_actualizacion2; }
if($fec_factura1!=""){	$lstParam2["fec_factura1"] = $fec_factura1; }
if($fec_factura2!=""){	$lstParam2["fec_factura2"] = $fec_factura2; }
if($folio_sol1!=""){	$lstParam2["folio_sol1"] = $folio_sol1; }
if($folio_sol2!=""){	$lstParam2["folio_sol2"] = $folio_sol2; }
if($id_concepto_pago!=""){	$lstParam2["id_concepto_pago"] = $id_concepto_pago; }
if($cve_edo_pago!=""){	$lstParam2["cve_edo_pago"] = $cve_edo_pago; }
if($cve_edo_sol!=""){	$lstParam2["cve_edo_sol"] = $cve_edo_sol; }
if($id_area!=""){	$lstParam2["id_area"] = $id_area; }

//print_r($solicitudes->getGrupo(11)); exit;
$lstParam = $lstParam2;
$lstParam["getCount"] = true;


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


$lstParam = $lstParam2;

$lstParam["sidx"] = $sidx;
$lstParam["sord"] = $sord;
$lstParam["start"] = $start;
$lstParam["limit"] = $limit;


//die("wwwzzzzzzddsss");



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
		$row->folio_sol
		,$row->id_concepto_pago
		,$row->cuenta
		,$row->nom_concepto_pago
		,"$".$importe
		,$row->cant_requerida
		,'$'.number_format($row->monto_tot_conc,2)
		,'$'.number_format($total_iva,2)
		,'$'.number_format($tola_sin_iva,2)
		,$row->nom_edo_pago
		,$row->referencia_ban
		,$row->fec_sol
		,$row->fecha_sol
		,$row->fec_factura
		,$row->fec_entrega
		,$row->nom_persona_sol
		,$row->entregado
		,$row->nom_usr
		,$row->nom_area
		,$row->ptl_ptl
		,$row->folio_fac
		,$row->folio_ticket
	);
}


die(json_encode($json));

?>