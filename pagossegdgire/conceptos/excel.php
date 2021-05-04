<?php

$shtml = "
<table border='1'>
<tr>
<th align='center'></th>
<th align='center'>Folio</th>
<th align='center'>Clave ISI</th>
<th align='center'>Concepto</th>
<th align='center'>Nombre concepto</th>
<th align='center'>Cantidad</th>
<th align='center'>Monto</th>
<th align='center'>IVA</th>
<th align='center'>Precio unit</th>
<th align='center'>Solicitante</th>
<th align='center'>Estado pago</th>
<th align='center'># Factura</th>
<th align='center'># Ticket</th>
<th align='center'>Entregado</th>
<th align='center'>Comentario</th>
</tr>
";


session_start();

//$id_solicitante = $_SESSION["userData2"]->id_solicitante;


$folio_sol = "";
$cve_edo_pago = "";
$fnombre = "";

require_once ("../common/config.php");
require_once ("../common/clsSolicitudes.php");
require_once ("validData_grid.php");

$solicitudes = new clsSolicitudes();

if(!$solicitudes){
	header( "Content-Type: application/octet-stream");
	header("Content-Transfer-Encoding: UTF-8"); //UTF-8 ISO-8859-1
	header( "Content-Disposition: attachment; filename=das.xls");
	die($shtml);
}


$lstParam = array();

if($folio_sol!=""){
	$lstParam["folio_sol"] = $folio_sol;
}

if($cve_edo_pago!=""){
	$lstParam["cve_edo_pago"] = $cve_edo_pago;
}

if($fnombre!=""){
	$lstParam["nom_persona_sol"] = $fnombre;
}


$resp = $solicitudes->detalle_solicitud($lstParam);



if(!$resp){
	header( "Content-Type: application/octet-stream");
	header("Content-Transfer-Encoding: UTF-8"); //UTF-8 ISO-8859-1
	header( "Content-Disposition: attachment; filename=das.xls");
	die($shtml);
}

if($resp->count==0){
	header( "Content-Type: application/octet-stream");
	header("Content-Transfer-Encoding: UTF-8"); //UTF-8 ISO-8859-1
	header( "Content-Disposition: attachment; filename=das.xls");
	die($shtml);
}


$i=0;
foreach($resp->detalles as $id => $row){
	
	$tola_sin_iva = $row->monto * $row->cant_requerida;

	$total_iva = $row->monto_iva * $row->cant_requerida;
	
	$importe=number_format(($tola_sin_iva+$total_iva)/$row->cant_requerida,2);
	
	$i++;
	$shtml.= "
		<tr>
		<td>$i</td>
		<td>$row->folio_sol</td>
		<td>$row->ptl_ptl</td>
		<td>$row->id_concepto_pago</td>
		<td>".utf8_decode($row->nom_concepto_pago)."</td>
		<td>$row->cant_requerida</td>
		<td>".'$'.number_format($row->monto_tot_conc,2)."</td>
		<td>".'$'.number_format($total_iva,2)."</td>
		<td>".'$'.number_format($tola_sin_iva,2)."</td>
		<td>".utf8_decode($row->nom_persona_sol)."</td>
		<td>".utf8_decode($row->nom_edo_pago)."</td>
		<td>$row->folio_fac</td>
		<td>$row->folio_ticket</td>
		<td>$row->entregado</td>
		<td>".utf8_decode($row->comentario_resp)."</td>
		</tr>
	";
	
}

header( "Content-Type: application/octet-stream");
header("Content-Transfer-Encoding: UTF-8"); //UTF-8 ISO-8859-1
header( "Content-Disposition: attachment; filename=das.xls");
die($shtml);

?>