<?php

require_once ("config.php");
require_once ("clsCatalogos.php"); 
	

#Inicia clase Registra Solicitud

class clsRegistraSolicitud extends clsCatalogos{

	//protected $id_usuario;

	public function __construct($linkMysql=NULL){

		parent::__construct();

		return $this->connect($linkMysql);
	}

	
	
		
	#******************************#
	/*
	public function addSolUsu($id_solicitante, $folio_sol, $pago_digitalizado, $referencia_ban, $serie_fac, $monto_total, $folio_fac, $folio_ticket, $fec_sol, $fec_ref_env, $fec_deposito_env, $fec_factura, $cve_edo_sol, $cve_edo_pago, $comentario, $factura, $rfc, $fec_actualizacion, $fec_termino_sol){

		$query = "".
		" INSERT INTO solicitud_pago (id_solicitante, folio_sol, pago_digitalizado, referencia_ban, serie_fac, monto_total, folio_fac, folio_ticket, fec_sol, fec_ref_env, fec_deposito_env, fec_factura, cve_edo_sol, cve_edo_pago, comentario, factura, rfc, fec_actualizacion, fec_termino_sol) VALUE ".
		" ($id_solicitante, $folio_sol, $pago_digitalizado, $referencia_ban, $serie_fac, $monto_total, $folio_fac, $folio_ticket, $fec_sol, $fec_ref_env, $fec_deposito_env, $fec_factura, $cve_edo_sol, $cve_edo_pago, $comentario, $factura, $rfc, $fec_actualizacion, $fec_termino_sol) ";
		
		$resp = $this->executeQueryMysql($query);

		if(!$resp){
			return false;
		}
		
		return true;
	}
	*/

	#******************************#







}#Termina clase Registra Solicitud

?>