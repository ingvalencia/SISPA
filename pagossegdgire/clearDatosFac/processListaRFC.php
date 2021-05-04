<?php

$fnombre = "";
$fcorreo_e = "";


require_once ("../common/config.php");
require_once ("../common/clsDatosFacturacion.php");
require_once ("validData_grid.php");


$limit = $length;
$sidx = $order[0]["column"];
$sord = $order[0]["dir"];

/*
$order = $_POST["order"];
$start = $_POST["start"];
$limit = $_POST["length"];
$draw = $_POST["draw"];

$sidx = $order[0]["column"];
$sord = $order[0]["dir"];
*/

$lstHeader = array(
0 => 'id_solicitante'
,1 => 'nombre_fisc'
,2 => 'rfc'
,3 => 'correo_e'
,4 => 'calle'
,5 => 'num_ext'
,6 => 'num_int'
,7 => 'id_cp'
);

$sidx = $lstHeader[$sidx];


$facturas = new clsDatosFacturacion();

if(!$facturas){
	$json["error"] = "si";
	$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
	$json["debug"] = $facturas->getError();
	$facturas->close();
	die(json_encode($json));

}

//print_r($usuarios->getGrupo(11)); exit;
$lstParam = array("getCount" => true);

if($fnombre!=""){
	$lstParam["fnombre"] = $fnombre;
}

if($rfc!=""){
	$lstParam["rfc"] = $rfc;
}

if($fcorreo_e!=""){
	$lstParam["fcorreo_e"] = $fcorreo_e;
}



$resp = $facturas->search_datos_facturacion($lstParam);

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

//print_r($lstParam); exit;

//echo "sss"; 

if($rfc!=""){
	$lstParam["rfc"] = $rfc;
}

if($fnombre!=""){
	$lstParam["fnombre"] = $fnombre;
}

if($fcorreo_e!=""){
	$lstParam["fcorreo_e"] = $fcorreo_e;
}

$resp = $facturas->search_datos_facturacion($lstParam);

$json["draw"] = $draw;
$json["recordsTotal"] = $total;
$json["recordsFiltered"] = $total;

//print_r($resp); exit;

$i=0;
foreach($resp->rfc as $id => $row){

	$idSol = $row->id_solicitante;
	$rfc = $row->rfc;
	
	//$btn = "<button rfc = '$rfc' id_solicitante = '$idSol' class='btn btn-outline btn-danger center-block clsDelFac btn-xs'><span class='glyphicon glyphicon-trash'></span></button>";
	
	$json["data"][$i++] = array(
		$idSol
		,$row->correo_e
		,$rfc
		,$nom = $row->nombre_fisc != "" ? $row->nombre_fisc : $row->nombre ." ". $row->ap_paterno
		,$row->calle
		,$row->num_ext
		,$row->num_int
		,$row->id_cp
		,$row->nom_edo
		,$row->nom_municipio
		,$row->nom_ciudad
		,$col = $row->nom_colonia!="" ? $row->nom_colonia : $row->colonia_otra
		//,$btn
	);
}


				


die(json_encode($json));

?>