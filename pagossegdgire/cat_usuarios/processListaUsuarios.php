<?php

$nombre = "";
$id_area = "";

require_once ("../common/config.php");
require_once ("../common/clsUsuario.php");
require_once ("validData_grid.php");

if($id_area==""){ $id_area=0; }

$limit = $length;
$sidx = $order[0]["column"];
$sord = $order[0]["dir"];

$lstHeader = array(
0 => "id_usuario"
,1 => "login"
,2 => "nombre_usr"
,3 => "ap_pat_usr"
,4 => "ap_mat_usr"
,5 => "vigente"
,6 => "nom_area"
,7 => "nom_rol"
,8 => ""
);

$sidx = $lstHeader[$sidx];


$usuarios = new clsUsuario();

if(!$usuarios){
	$json["error"] = "si";
	$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
	$json["debug"] = $usuarios->getError();
	$usuarios->close();
	die(json_encode($json));

}

//print_r($usuarios->getGrupo(11)); exit;
$lstParam = array("getCount" => true);
if($nombre!=""){
	$lstParam["nombre"] = $nombre;
}

if($id_area!=0){
	$lstParam["id_area"] = $id_area;
}

$resp = $usuarios->search_usuarios($lstParam);

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

if($nombre!=""){
	$lstParam["nombre"] = $nombre;
}

if($id_area!=0){
	$lstParam["id_area"] = $id_area;
}

//print_r($lstParam); exit;

//echo "sss"; 
$resp = $usuarios->search_usuarios($lstParam);

$json["draw"] = $draw;
$json["recordsTotal"] = $total;
$json["recordsFiltered"] = $total;

//print_r($resp); exit;

$i=0;
foreach($resp->usuarios as $id => $row){
	
	$json["data"][$i++] = array(
		$row->id_usuario
		,$row->login
		,$row->nombre_usr
		,$row->ap_pat_usr
		,$row->ap_mat_usr
		,$row->vgente
		,$row->nom_area
		,$row->nom_rol
		,""
	);
}


die(json_encode($json));

?>