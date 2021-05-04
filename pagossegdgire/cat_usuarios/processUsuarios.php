<?php

$nombre = "";
$id_area = "";

require_once ("../common/config.php");
require_once ("../common/clsUsuario.php");
require_once ("validData.php");


$usr = new clsUsuario();

//die("3");

if(!$usr){

	$json["error"] = true;
	$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
	$json["debug"] = $usr->getError();	
	die(json_encode($json));

}

//die("4");

function getIni(){
	global $usr;
	
	$areas = $usr->getAreas();
	$roles = $usr->getRoles();
	
	
	$json["error"] = false;
	$json["data"]["areas"] = $areas->areas;
	$json["data"]["roles"] = $roles->roles;
	
	return $json;
}
//die("5");

$json["error"] = "no";


if($opt=="getIni"){
	
	$json = getIni();
	die(json_encode($json));
}

if($opt=="existLogin"){
	
	$resp = $usr->existLogin($login);
	
	if(!$resp){
		$json["error"] = true;
		$json["msg"] = "Error #C000: Se presento un error, comuniquese con el administrador";
		$json["debug"] = $usr->getError();	
		die(json_encode($json));
	}

	if($resp->exist){
		$json["msg"] = "Ya existe el usuario";
	}
	else{
		$json["msg"] = "Usuario valido";
	}
	
	$json["exist"] = $resp->exist;
	$json["error"] = false;	
	die(json_encode($json));

}

if($opt=="getUsuario"){
	
	$resp = $usr->getUsuario($id_usuario);
	
	if(!$resp){
		$json["error"] = true;
		$json["msg"] = "Error #C000: Se presento un error, comuniquese con el administrador";
		$json["debug"] = $usr->getError();	
		die(json_encode($json));
	}

	//print_r($resp);
	
	if(!$resp->count){
		$json["error"] = false;
		$json["exist"] = false;
		$json["msg"] = "No existe el usuario";
		$json["debug"] = $usr->getError();	
		die(json_encode($json));
	}
	
	
	$json["error"] = false;
	$json["exist"] = true;
	$json["msg"] = "Existe el usuario";
	$json["usuario"] = $resp->usuario;
	die(json_encode($json));
	
}

if($opt=="addUsuario"){
		
	$resp = $usr->addUsuario($nombre_usr, $ap_pat_usr, $ap_mat_usr, $login, $passwd, $id_area, $id_rol, $vigente);
	
	if(!$resp){
		$json["error"] = true;
		$json["msg"] = "Error #C000: Se presento un error, comuniquese con el administrador";
		$json["debug"] = $usr->getError();	
		die(json_encode($json));
	}

	$json["error"] = false;
	$json["msg"] = "Se agrego el usuario";
	die(json_encode($json));
	
}

if($opt=="updUsuario"){
	
	$lstParam = array(
		"nombre_usr" => $nombre_usr
		,"ap_pat_usr" => $ap_pat_usr
		,"ap_mat_usr" => $ap_mat_usr
		//,"login" => $login
		,"id_area" => $id_area
		,"id_rol" => $id_rol
		,"vigente" => $vigente
	);
	
	if(($passwd_ch == "on")&&($passwd != "")){
		$lstParam["passwd"] = $passwd;
	}
	
	$resp = $usr->updUsuario($id_usuario, $lstParam);
	
	if(!$resp){
		$json["error"] = true;
		$json["msg"] = "Error #C000: Se presento un error, comuniquese con el administrador";
		$json["debug"] = $usr->getError();	
		die(json_encode($json));
	}

	$json["error"] = false;
	$json["msg"] = "Se han actualizado los datos del usuario";
	die(json_encode($json));
	
}




if($opt=="getExcel"){

	if($id_area==""){ $id_area=0; }

	$lstParam = array();
	if($nombre!=""){
		$lstParam["nombre"] = $nombre;
	}
	
	if($id_area!=0){
		$lstParam["id_area"] = $id_area;
	}
	
	$lstParam["sidx"] = "id_usuario";
	$lstParam["sord"] = "asc";
	
	$resp = $usr->search_usuarios($lstParam);
		
	$excel = "

		<table border='1'>
		<tr>
			<th align=center>ID usuario</th>
			<th align=center>Login</th>
			<th align=center>Nombre usuario</th>
			<th align=center>Ap. paterno</th>
			<th align=center>Ap. materno</th>
			<th align=center>Subdirección</th>
			<th align=center>Departamento</th>
			<th align=center>Rol</th>
			<th align=center>Activo</th>
		</tr>
	";
	
	//print_r($resp); exit;
	foreach($resp->usuarios as $id => $row){
		
		//print_r($row);
		
		$excel .="
			<tr>
				<td>$row->id_usuario</td>
				<td>$row->login</td>
				<td>$row->nombre_usr</td>
				<td>$row->ap_pat_usr</td>
				<td>$row->ap_mat_usr</td>
				<td>$row->nom_sub</td>
				<td>$row->nom_area</td>
				<td>$row->nom_rol</td>
				<td>$row->vgente</td>
			</tr>
		";
	}
	
	$excel.= "</table>";

	header("Content-Type: application/octet-stream");
	header("Content-Transfer-Encoding: ISO-8859-1");
	//header("Content-Transfer-Encoding: UTF8");
	header("Content-Disposition: attachment; filename=reporte_catalogo_conceptos.xls");
	
	echo utf8_decode($excel);
	exit();

	
}




?>