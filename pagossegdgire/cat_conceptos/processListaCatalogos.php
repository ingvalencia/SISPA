<?php


require_once ("../common/config.php");
require_once ("../common/clsConceptosPago.php");
require_once ("../common/clsParametros.php");
require_once ("validData_grid.php");


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


function getIVA($row){
	
}


if($id_concepto_pago==""){ $id_concepto_pago=0; }
if($id_area==""){ $id_area=0; }

$limit = $length;
$sidx = $order[0]["column"];
$sord = $order[0]["dir"];


$lstHeader = array(
0 => "c.id_concepto_pago"
,1 => "a.nom_area"
,2 => "c.nom_concepto_pago"
,3 => "t.nombre"
,4 => ""
,5 => "c.importe_smdf"
,6 => "c.importe_pesos"
,7 => "c.calcula_iva"
,8 => ""
,9 => "c.costo_variable"
,10 => "c.cuenta"
,11 => "c.vigente"
,12 => "c.fec_actualizacion"
);

$sidx = $lstHeader[$sidx];


$conceptosPago = new clsConceptosPago();

if(!$conceptosPago){
	$json["error"] = "si";
	$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
	$json["debug"] = $conceptosPago->getError();
	$conceptosPago->close();
	die(json_encode($json));

}

//print_r($conceptosPago->getGrupo(11)); exit;
$lstParam = array("getCount" => true);
if($id_concepto_pago!=0){
	$lstParam["id_concepto_pago"] = $id_concepto_pago;
}

if($id_area!=0){
	$lstParam["id_area"] = $id_area;
}

//print_r($lstParam); exit;

$resp = $conceptosPago->search_conceptos($lstParam);

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

if($id_concepto_pago!=0){
	$lstParam["id_concepto_pago"] = $id_concepto_pago;
}

if($id_area!=0){
	$lstParam["id_area"] = $id_area;
}


//print_r($lstParam); exit;

//echo "sss"; 
$resp = $conceptosPago->search_conceptos($lstParam);

$json["draw"] = $draw;
$json["recordsTotal"] = $total;
$json["recordsFiltered"] = $total;

//print_r($resp); exit;

$p = new clsParametros($conceptosPago->getLinkMysql());
$parametros = $p->search_parametros();
//print_r($parametros);
$smdf = $parametros->parametros["smdf"]->valor;
$iva = $parametros->parametros["iva"]->valor;
$xiva = $iva/100;

$i=0;
foreach($resp->conceptos as $id => $row){
	
	$importe = calculaMontos($row);
	
	//print_r($importe); exit;

	$json["data"][$i++] = array(
		$row->id_concepto_pago
		,$row->nom_area
		,$row->nom_concepto_pago
		,$row->nombre
		,"$".number_format($importe["importe"],2,'.',',')
		,$row->importe_smdf
		,"$".number_format($row->importe_pesos,2,'.',',')
		,$row->calcula_iva_text
		,"$".number_format($importe["monto_iva"],2,'.',',')
		,$row->costo_variable_text
		,$row->cuenta
		,$row->vigente_text
		,$row->fec_actualizacion
	);
}


die(json_encode($json));

?>