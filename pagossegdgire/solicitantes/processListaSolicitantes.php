<?php

$fnombre = "";
$fcorreo_e = "";

require_once ("../common/config.php");
require_once ("../common/clsSolicitantes.php");
require_once ("validData_grid.php");

$limit = $length;
$sidx = $order[0]["column"];
$sord = $order[0]["dir"];

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

$sidx = $lstHeader[$sidx];


$solicitantes = new clsSolicitantes();

if(!$solicitantes){
	$json["error"] = "si";
	$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
	$json["debug"] = $solicitantes->getError();
	$solicitantes->close();
	die(json_encode($json));

}

//print_r($usuarios->getGrupo(11)); exit;
$lstParam = array("getCount" => true);


if($fnombre!=""){
	$lstParam["fnombre"] = $fnombre;
}

if($fcorreo_e!=""){
	$lstParam["fcorreo_e"] = $fcorreo_e;
}



$resp = $solicitantes->search_solicitantes($lstParam);

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


if($fnombre!=""){
	$lstParam["fnombre"] = $fnombre;
}

if($fcorreo_e!=""){
	$lstParam["fcorreo_e"] = $fcorreo_e;
}


$resp = $solicitantes->search_solicitantes($lstParam);

$json["draw"] = $draw;
$json["recordsTotal"] = $total;
$json["recordsFiltered"] = $total;

//print_r($resp); exit;

$i=0;
foreach($resp->solicitantes as $id => $row){
	
	$json["data"][$i++] = array(
		$row->id_solicitante
		,$row->nom_perfil
		,$row->ptl_ptl
		,$row->exp_unam
		,$row->nombre
		,$row->ap_paterno
		,$row->ap_materno
		,$row->correo_e
		,$row->telefono
		,$row->celular
		,$row->fec_registro
		,$row->vigente
	);
}


die(json_encode($json));

?>