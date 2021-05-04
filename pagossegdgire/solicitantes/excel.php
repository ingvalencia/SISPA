<?php


$shtml = "
<table border='1'>
<tr>
<th align='center'></th>
<th align='center'>id</th>
<th align='center'>Tipo</th>
<th align='center'>Plantel</th>
<th align='center'># Exp</th>
<th align='center'>Nombre</th>
<th align='center'>Ap paterno</th>
<th align='center'>Ap materno</th>
<th align='center'>Email</th>
<th align='center'>Teléfono</th>
<th align='center'>Celular</th>
<th align='center'>Registro</th>
<th align='center'>Vigente</th>
</tr>
";

$fnombre = "";
$fcorreo_e = "";

require_once ("../common/config.php");
require_once ("../common/clsSolicitantes.php");
require_once ("validData_grid.php");


$lstHeader = array(
0 => 'id_solicitante'
,1 => 'id_perfil'
,2 => 'ptl_ptl'
,3 => 'num_exp'
,4 => 'nombre'
,5 => 'ap_paterno'
,6 => 'ap_materno'
,7 => 'correo_e'
);


$solicitantes = new clsSolicitantes();

if(!$solicitantes){
	header( "Content-Type: application/octet-stream");
	header("Content-Transfer-Encoding: UTF-8"); //UTF-8 ISO-8859-1
	header( "Content-Disposition: attachment; filename=reporte.xls");
	die($shtml);

}


$lstParam = array();


if($fnombre!=""){
	$lstParam["fnombre"] = $fnombre;
}

if($fcorreo_e!=""){
	$lstParam["fcorreo_e"] = $fcorreo_e;
}



$resp = $solicitantes->search_solicitantes($lstParam);


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
foreach($resp->solicitantes as $id => $row){
	
	$i++;
	$shtml.="
		<tr>
		<td>$i</td>
		<td>$row->id_solicitante</td>
		<td>$row->nom_perfil</td>
		<td>$row->ptl_ptl</td>
		<td>$row->exp_unam</td>
		<td>$row->nombre</td>
		<td>$row->ap_paterno</td>
		<td>$row->ap_materno</td>
		<td>$row->correo_e</td>
		<td>$row->telefono</td>
		<td>$row->celular</td>
		<td>$row->fec_registro</td>
		<td>$row->vigente</td>
		</tr>
	";
}


header( "Content-Type: application/octet-stream");
header("Content-Transfer-Encoding: UTF-8"); //UTF-8 ISO-8859-1
header( "Content-Disposition: attachment; filename=reporte.xls");
die($shtml);

?>