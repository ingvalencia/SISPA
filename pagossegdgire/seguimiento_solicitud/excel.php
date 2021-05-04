<?php

$shtml = "
<table border='1'>
<tr>
<th align='center'></th>
<th align='center'>Folio</th>
<th align='center'>Fc actualización</th>
<th align='center'>Estado pago</th>
<th align='center'>Estado sol</th>
<th align='center'>Nombre</th>
<th align='center'>Correo electrénico</th>
<th align='center'>Teléfono</th>
<th align='center'>Factura</th>
<th align='center'>Monto</th>
<th align='center'>IVA</th>
<th align='center'>Precio unitario</th>
<th align='center'># Serie</th>
<th align='center'>RFC</th>
<th align='center'># Factura</th>
<th align='center'># Ticket</th>
<th align='center'>Aclaración</th>
<th align='center'>Comentario</th>
<th align='center'>Ref Bancaria</th>
</tr>
";

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

if($fnombre!=""){
	$lstParam["fnombre"] = $fnombre;
}

if($cve_edo_pago!=""){
	$lstParam["cve_edo_pago"] = $cve_edo_pago;
}

$resp = $solicitudes->search_solicitudes($lstParam);



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
foreach($resp->solicitudes as $id => $row){
	
	$i++;
	$shtml .= "
		<tr>
		<td>$i</td>
		<td>$row->folio_sol</td>
		<td>$row->fec_actualizacion</td>
		<td>".utf8_decode($row->nom_edo_pago)."</td>
		<td>".utf8_decode($row->nom_edo_sol)."</td>
		<td>".utf8_decode($row->nombre_completo)."</td>
		<td>$row->correo_e</td>
		<td>$row->telefono</td>
		<td>$row->factura_text</td>
		<td>$row->monto_total</td>
		<td>$row->monto_total_iva</td>
		<td>$row->monto_total_sin_iva</td>
		<td>$row->serie_fac</td>
		<td>$row->rfc</td>
		<td>$row->folio_fac</td>
		<td>$row->folio_ticket</td>
		<td>$row->aclaracion</td>
		<td>".utf8_decode($row->comentario)."</td>
		<td>$row->referencia_ban</td>
		";
	
}

header( "Content-Type: application/octet-stream");
header("Content-Transfer-Encoding: UTF-8"); //UTF-8 ISO-8859-1
header( "Content-Disposition: attachment; filename=das.xls");
die($shtml);



?>