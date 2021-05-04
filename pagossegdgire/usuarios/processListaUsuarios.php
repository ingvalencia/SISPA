<?php


include_once ("../common/config.php");
include_once ("../common/clsUsuario.php");


$order = $_POST["order"];
$start = $_POST["start"];
$limit = $_POST["length"];
$draw = $_POST["draw"];

$sidx = $order[0]["column"];
$sord = $order[0]["dir"];
									
switch($sidx){
	case 0:
		$sidx = "id_usuario";
		break;
	case 1:
		$sidx = "nombre_usr";
		break;
	case 2:
		$sidx = "login";
		break;
	case 3:
		$sidx = "id_rol";
		break;
	case 4:
		$sidx = "vigente";
		break;
	case 5:
		$sidx = "fcIngreso";
		break;
	default:
		$sidx = "";
}

$usuarios = new usuario();
//$usuarios->connect();
	
if(!$usuarios){

	$json["error"] = "si";
	$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
	$json["debug"] = $usuarios->getError();	
	die(json_encode($json));

}

$lstParam = array("getCount" => true);
$rest = $usuarios->search_usuarios($lstParam);

if(!$rest){
	return false;
}

if($rest->count==0){
	return false;
}

$total = $rest->count;


$lstParam = array(
	"sidx" => $sidx
	,"sord" => $sord
	,"start" => $start
	,"limit" => $limit
);

$rest = $usuarios->search_usuarios($lstParam);

//print_r($rest); exit;
$json["draw"] = $draw;
$json["recordsTotal"] = $total;
$json["recordsFiltered"] = $total;

$i=0;
foreach($rest->usuarios as $id_usuario => $row){

	$c = array(
		$row->id_usuario
		,$row->login
		,$row->nombre_usr
		,($row->nomRol)
		,$row->vigente == 1 ? "Si" : "No"
		,$row->fcIngreso
	);
	
	$json["data"][$i++] = $c;
	
}
//print_r($json); exit;

die(json_encode($json));

?>