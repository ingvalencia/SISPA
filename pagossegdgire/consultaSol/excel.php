<?php

$shtml = "
<table border='1'>
<tr>
<th align='center'></th>
<th align='center'>Folio</th>
<th align='center'>Clave</th>
<th align='center'>Cuenta</th>
<th align='center'>Concepto</th>
<th align='center'>Importe</th>
<th align='center'>Cantidad</th>
<th align='center'>Monto</th>
<th align='center'>IVA</th>
<th align='center'>Precio unit</th>
<th align='center'>Estado pago</th>
<th align='center'>Ref Bancaria</th>
<th align='center'>Fc Solicitud</th>
<th align='center'>Actualización estado</th>
<th align='center'>Fc de factura</th>
<th align='center'>Solicitante</th>
<th align='center'>Entregado</th>
<th align='center'>Responsable</th>
<th align='center'>Plantel</th>
<th align='center'># Factura</th>
<th align='center'># Ticket</th>
</tr>
";


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

$solicitudes = new clsSolicitudes();

if(!$solicitudes){
	$json["error"] = "si";
	$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
	$json["debug"] = $solicitudes->getError();
	$solicitudes->close();
	die(json_encode($json));
}

$lstParam = array();
if($fec_sol1!=""){ $lstParam["fec_sol1"] = $fec_sol1; }
if($fec_sol2!=""){ $lstParam["fec_sol2"] = $fec_sol2; }
if($fec_actualizacion1!=""){ $lstParam["fec_actualizacion1"] = $fec_actualizacion1; }
if($fec_actualizacion2!=""){ $lstParam["fec_actualizacion2"] = $fec_actualizacion2; }
if($fec_factura1!=""){	$lstParam["fec_factura1"] = $fec_factura1; }
if($fec_factura2!=""){	$lstParam["fec_factura2"] = $fec_factura2; }
if($folio_sol1!=""){	$lstParam["folio_sol1"] = $folio_sol1; }
if($folio_sol2!=""){	$lstParam["folio_sol2"] = $folio_sol2; }
if($id_concepto_pago!=""){	$lstParam["id_concepto_pago"] = $id_concepto_pago; }
if($cve_edo_pago!=""){	$lstParam["cve_edo_pago"] = $cve_edo_pago; }
if($cve_edo_sol!=""){	$lstParam["cve_edo_sol"] = $cve_edo_sol; }
if($id_area!=""){	$lstParam["id_area"] = $id_area; }

$resp = $solicitudes->detalle_solicitud($lstParam);

if(!$resp){
	header( "Content-Type: application/octet-stream");
	header("Content-Transfer-Encoding: UTF-8"); //UTF-8 ISO-8859-1
	header( "Content-Disposition: attachment; filename=reporte.xls");
	die($shtml);
}

if($resp->count==0){
	header( "Content-Type: application/octet-stream");
	header("Content-Transfer-Encoding: UTF-8"); //UTF-8 ISO-8859-1
	header( "Content-Disposition: attachment; filename=reporte.xls");
	die($shtml);
}


$i=0;
foreach($resp->detalles as $id => $row){
	
	$tola_sin_iva = $row->monto * $row->cant_requerida;

	$total_iva = $row->monto_iva * $row->cant_requerida;
	
	$importe=number_format(($tola_sin_iva+$total_iva)/$row->cant_requerida,2);
	
	
	$i++;
	$shtml.="
		<tr>
		<td>$i</td>
		<td>$row->folio_sol</td>
		<td>$row->id_concepto_pago</td>
		<td>$row->cuenta</td>
		<td>".utf8_decode($row->nom_concepto_pago)."</td>
		<td>$".$importe."</td>
		<td>$row->cant_requerida</td>
		<td>$".number_format($row->monto_tot_conc,2)."</td>
		<td>$".number_format($total_iva,2)."</td>
		<td>$".number_format($tola_sin_iva,2)."</td>
		<td>".utf8_decode($row->nom_edo_pago)."</td>
		<td>$row->referencia_ban</td>
		<td>$row->fec_sol</td>
		<td>$row->fecha_sol</td>
		<td>$row->fec_factura</td>
		<td>$row->fec_entrega</td>
		<td>".utf8_decode($row->nom_persona_sol)."</td>
		<td>$row->entregado</td>
		<td>".utf8_decode($row->nom_usr)."</td>
		<td>".utf8_decode($row->nom_area)."</td>
		<td>$row->ptl_ptl</td>
		<td>$row->folio_fac</td>
		<td>$row->folio_ticket</td>
		</tr>
	";
	
}

header( "Content-Type: application/octet-stream");
header("Content-Transfer-Encoding: UTF-8"); //UTF-8 ISO-8859-1
header( "Content-Disposition: attachment; filename=reporte.xls");
die($shtml);

?>