<?php

require_once('nusoap.php');
require_once('../common/config.php');
require_once('../common/clsConceptosPago.php');
require_once('../common/clsSolicitudes.php');
require_once('../common/clsSolicitantes.php');
require_once('../common/clsParametros.php');
include_once('../common/clsSolicitantes.php');
require_once('../common/clsDatosFacturacion.php');
include_once('../common/clsCorreo.php');
require_once('validData.php');
require_once('messages.php');





function generarFacturaTicket($id_solicitante, $folio_sol){
	
	$solicitudes = new clsSolicitudes();
	
	$resp = $solicitudes->verificarPago($folio_sol, $id_solicitante);
	
	//$resp = $solicitudes->generarFacturaTicket($id_solicitante, $folio_sol);
	
	return $resp;
	
}

ob_clean();

$resp = generarFacturaTicket(11371, 20170311643);
	
print_r($resp);