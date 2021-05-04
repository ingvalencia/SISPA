<?php
require_once ("../common/config.php");
require_once ("../common/clsConceptosPago.php");
require_once ("../common/clsParametros.php");
require_once ("validData.php");


function calculaMontos($row){
	global $smdf;
	global $xiva;
	
	$precio_unitario = 0;
	$monto_iva = 0;
	$iva = $xiva;
	
	if($row->importe_smdf!=0){
		$precio_unitario = $row->importe_smdf*$smdf;
	}
	else{
		$precio_unitario = $row->importe_pesos;
	}

	if($row->calcula_iva==1){
		$monto_iva = $precio_unitario * $iva;
	}

	$importe = $precio_unitario + $monto_iva;
	
	return array("importe" => $importe, "monto_iva" => $monto_iva);
		
}

$conceptosPago = new clsConceptosPago();

if(!$conceptosPago){

	$json["error"] = true;
	$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el probelma persiste comuniquese con el administrador";
	$json["debug"] = $conceptosPago->getError();	
	die(json_encode($json));

}

$p = new clsParametros($conceptosPago->getLinkMysql());
$parametros = $p->search_parametros();

$smdf = $parametros->parametros["smdf"]->valor;
$iva = $parametros->parametros["iva"]->valor;
$xiva = $iva/100;
//die("4");

function getIni(){
	
	global $conceptosPago;
	
	$areas = $conceptosPago->getAreas();
	$conceptos = $conceptosPago->getTiposConceptos();
	
	$json["error"] = false;
	$json["data"]["areas"] = $areas->areas;
	$json["data"]["conceptos"] = $conceptos->tipos_conceptos;
	
	return $json;
}


function validClave($clave){
	global $conceptosPago;
	
	$resp = $conceptosPago->existClave($clave);
	
	//print_r($resp);
	
	if(!$resp){
		$json["error"] = true;
		$json["msg"] = "Error #C000: Se presento un error, comuniquese con el administrador";
		$json["debug"] = $conceptosPago->getError();	
		return $json;
	}

	if($resp->exist){
		$json["error"] = false;
		$json["msg"] = "Ya existe la clave";
		$json["exist"] = true;
		$json["debug"] = $conceptosPago->getError();
		return $json;
	}
	
	$json["error"] = false;
	$json["msg"] = "No existe la clave";
	$json["exist"] = false;
	return $json;

}

//die("5");

$json["error"] = "no";


if($opt=="getIni"){
	
	$json = getIni();
	$json["data"]["smdf"]= $smdf;
	$json["data"]["iva"]= $iva;
	die(json_encode($json));
}

if($opt=="validClave"){
	
	die(json_encode(validClave($id_concepto_pago)));
	
}


if($opt=="getConcepto"){
	
	$resp = $conceptosPago->getConcepto($id_concepto_pago);
	
	if(!$resp){
		$json["error"] = true;
		$json["msg"] = "Error #C000: Se presento un error, comuniquese con el administrador";
		$json["debug"] = $conceptosPago->getError();	
		die(json_encode($json));
	}
	
	$resp->concepto->precio_unitario = number_format($resp->concepto->importe_pesos, 2, '.', '');
	$json["error"] = false;
	$json["concepto"] = $resp->concepto;
	$json["smdf"]= $smdf;
	$json["iva"]= $iva;

	die(json_encode($json));
}

if($opt=="addConcepto"){
	
	
	$resp = validClave($id_concepto_pago);
	
	if($resp["error"]||$resp["exist"]){
		die(json_encode($resp));
	}

	
	$newConcepto=array(
		"id_concepto_pago" => $id_concepto_pago
		,"id_area" => $id_area
		,"cve_tipo_con" => $cve_tipo_con
		,"nom_concepto_pago" => $nom_concepto_pago
		,"precio_unitario" => $precio_unitario
		,"importe_smdf" => $importe_smdf
		,"calcula_iva" => $calcula_iva
		,"cuenta" => $cuenta
		,"vigente" => $vigente
		,"costo_variable" => $costo_variable
	);
	
	$resp = $conceptosPago->addConcepto($newConcepto);
	
	if(!$resp){
		$json["error"] = true;
		$json["msg"] = "Error #C000: Se presento un error, comuniquese con el administrador";
		$json["debug"] = $conceptosPago->getError();	
		die(json_encode($json));
	}

	$json["error"] = false;
	$json["msg"] = "Se agrego el concepto $id_concepto_pago ";

	die(json_encode($json));
	
}


if($opt=="updConcepto"){
	
	$updConcepto=array(
		"id_area" => $id_area
		,"cve_tipo_con" => $cve_tipo_con
		,"nom_concepto_pago" => $nom_concepto_pago
		,"importe_pesos" => $precio_unitario
		,"importe_smdf" => $importe_smdf
		,"calcula_iva" => $calcula_iva
		,"cuenta" => $cuenta
		,"vigente" => $vigente
		,"costo_variable" => $costo_variable
	);
	
	$resp = $conceptosPago->updConcepto($id_concepto_pago, $updConcepto);
	
	if(!$resp){
		$json["error"] = true;
		$json["msg"] = "Error #C000: Se presento un error, comuniquese con el administrador";
		$json["debug"] = $conceptosPago->getError();	
		die(json_encode($json));
	}

	$json["error"] = false;
	$json["msg"] = "Se actualizo el concepto <strong>$id_concepto_pago</strong>";

	die(json_encode($json));
	
}


if($opt=="getExcel"){

	
	if($id_concepto_pago==""){ $id_concepto_pago=0; }
	if($id_area==""){ $id_area=0; }

	$lstParam = array();
	if($id_concepto_pago!=0){
		$lstParam["id_concepto_pago"] = $id_concepto_pago;
	}

	
	if($id_area!=0){
		$lstParam["id_area"] = $id_area;
	}
	
	$lstParam["sidx"] = "id_concepto_pago";
	$lstParam["sord"] = "asc";
	
  
	$resp = $conceptosPago->search_conceptos($lstParam);
	
	$excel = "

		<table border='1'>
		<tr>
			<th align='center'>Cve concepto</th>
			<th align='center'>Área</th>
			<th align='center'>Concepto</th>
			<th align='center'>Tipo de concepto</th>
			<th align='center'>Importe</th>
			<th align='center'>Importe SMDF</th>
			<th align='center'>Importe pesos</th>
			<th align='center'>IVA</th>
			<th align='center'>IVA $iva%</th>
			<th align='center'>Costo variable</th>
			<th align='center'>Cuenta</th>
			<th align='center'>Activo</th>
			<th align='center'>Fecha de actualización</th>
		</tr>
	";
	
	//print_r($resp); exit;
	foreach($resp->conceptos as $id => $row){
		
		$importe = calculaMontos($row);
	
		$imp = "$".number_format($importe["importe"],2,'.',',');
		$monto_iva = "$".number_format($importe["monto_iva"],2,'.',',');
		
		$clave = (string)$row->id_concepto_pago;
		$excel .="
			<tr>
				<td>$clave</td>
				<td>$row->nom_area</td>
				<td>$row->nom_concepto_pago</td>
				<td>$row->cve_tipo_con</td>
				<td>$imp</td>
				<td>$row->importe_smdf</td>
				<td>$row->importe_pesos</td>
				<td>$row->calcula_iva_text</td>
				<td>$monto_iva</td>
				<td>$row->costo_variable_text</td>
				<td>$row->cuenta</td>
				<td>$row->vigente_text</td>
				<td>$row->fec_actualizacion</td>
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
