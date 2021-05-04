<?php

session_start();

$id_solicitante = $_SESSION["userData2"]->id_solicitante;


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

$order = $_POST["order"];
$start = $_POST["start"];
$limit = $_POST["length"];
$draw = $_POST["draw"];

$sidx = $order[0]["column"];
$sord = $order[0]["dir"];

$lstHeader = array(
0 => "folio_sol"
,1 => ""
,2 => "cve_edo_pago"
,3 => "rfc"
,4 => "fec_sol"
,5 => ""
,6 => "comentario"
,7 => "referencia_ban"
);

$sidx = $lstHeader[$sidx];


$solicitudes = new clsSolicitudes();

if(!$solicitudes){
	$json["error"] = "si";
	$json["msg"] = "Error #C000: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador";
	$json["debug"] = $solicitudes->getError();
	$solicitudes->close();
	die(json_encode($json));

}

$lstParam = array("getCount" => true, "id_solicitante" => $id_solicitante);
if($folio_sol!=""){
	$lstParam["folio_sol"] = $folio_sol;
}

if($cve_edo_pago!=""){
	$lstParam["cve_edo_pago"] = $cve_edo_pago;
}

$resp = $solicitudes->search_solicitudes($lstParam);


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
	,"id_solicitante" => $id_solicitante
);

if($folio_sol!=""){
	$lstParam["folio_sol"] = $folio_sol;
}

if($cve_edo_pago!=""){
	$lstParam["cve_edo_pago"] = $cve_edo_pago;
}


$resp = $solicitudes->search_solicitudes($lstParam);

$json["draw"] = $draw;
$json["recordsTotal"] = $total;
$json["recordsFiltered"] = $total;


$i=0;
foreach($resp->solicitudes as $id => $row){

	$rfc = "";
	if($row->rfc!=null){
		$rfc= $row->rfc;
	}
	
	$edo_pago = "";
	
	/*Cuando la ficha esta enviada*/
	
	if($row->cve_edo_pago=="FIENV"){
		
		$edo_pago = "<a class='clsValidPago' style='cursor:pointer' folio_sol='".$row->folio_sol."'>".$row->nom_edo_pago."</a>";
		
		
	}
	else{
		$edo_pago = $row->nom_edo_pago;
	}
	
	/*Cuando ya estan pagadas las referencias*/
	
	if($row->factura && $row->cve_edo_pago=="PFIN"){
		
		if($rfc!=null){
				$rfc = "<a class='clsGeneraFac' style='cursor:pointer' folio_sol='".$row->folio_sol."'>".$row->rfc."</a>";
		}
		
	}
	
	if($rfc==null &&  $row->cve_edo_pago=="PFIN" ){
	
		$rfc = "<a class='clsGeneraTicket' style='cursor:pointer' folio_sol='".$row->folio_sol."'>Descargue su ticket</a>";
		
				
	}
	
	
	
	$ref = "";
	if($row->referencia_ban!=null){

		$ref = $row->referencia_ban;
	}
	
	$json["data"][$i++] = array(
		$row->folio_sol
		,'$'.number_format($row->monto_total,2)
		,$edo_pago
		,$rfc
		,$row->fec_sol
		,$row->fec_factura
		,$row->comentario
		,$ref
	);
	
}


die(json_encode($json));

?>
