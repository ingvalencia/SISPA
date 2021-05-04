<?php

$shtml = "
<table border='1'>
<tr>
<th align='center'></th>
<th align='center'>Folio</th>
<th align='center'>Monto</th>
<th align='center'>Estado pago</th>
<th align='center'>RFC</th>
<th align='center'>Actualización status</th>
<th align='center'>Fecha de entrega</th>
<th align='center'>Comentarios</th>
<th align='center'>Ref Bancaria</th>
</tr>
";

session_start();

$folio_sol = "";
$cve_edo_pago = "";

require_once ("../common/config.php");
require_once ("../common/clsSolicitudes.php");
require_once ("validData_grid.php");


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
	$shtml.="
		<tr>
		<td>$i</td>
		<td>$row->folio_sol</td>
		<td>$".number_format($row->monto_total,2)."</td>
		<td>".utf8_decode($row->nom_edo_pago)."</td>
		<td>$row->rfc</td>
		<td>$row->fec_sol</td>
		<td></td>
		<td>".utf8_decode($row->comentario)."</td>
		<td>$row->referencia_ban</td>
		</tr>
	";
}


header( "Content-Type: application/octet-stream");
header("Content-Transfer-Encoding: UTF-8"); //UTF-8 ISO-8859-1
header( "Content-Disposition: attachment; filename=reporte.xls");
die($shtml);

?>