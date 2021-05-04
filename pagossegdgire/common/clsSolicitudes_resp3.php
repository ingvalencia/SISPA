<?php

require_once ("config.php");
require_once ("clsConceptosPago.php");
include_once ("clsParametros.php");
require_once ("clsCatalogos.php"); 
require_once ("class.WSPagos.php");
require_once ("clsDatosFacturacion.php");
include_once ("clsCorreo.php");


	
class clsSolicitudes extends clsConceptosPago{

	public function __construct($linkMysql=NULL){

		parent::__construct();

		return $this->connect($linkMysql);
	}
	
	private function getLastFolio(){
	
		$query = "SELECT MAX(id_folio) AS max FROM folio";
		
		$resp = $this->executeQueryMysql($query);
		
		$row = $resp->fetch_object();

		$max = $row->max;
		
		return $max;
		
	} 
	
	public function getfolio($id_solicitante){
	
		$year = date('Y', time());
		
		$query = "INSERT INTO folio (id_solicitante, anio) VALUES ($id_solicitante, $year)";
	
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}		
		
		$max = $this->getLastFolio();
		
		$con = str_pad($max, "5", "0", STR_PAD_LEFT); 
		
		$folio = $year.date('m', time()).$con;
		
		return $folio;
	}
	

	public function exist_solicitud($folio_sol){
		
		$query = "SELECT folio_sol FROM solicitud_pago WHERE folio_sol = $folio_sol";
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		if($resp->num_rows == 0){
			return (object)array("count" => 0);
		}
		
		return (object)array("count" => 1);
		
	}
	
	
	public function process_solicitud($lstParam = array()){
		
		if(count($lstParam) == 0){
			$json = (object)array(
				"error" => true
				,"msg" => "EPS000: No se pudo crear la solicitud, si el problema persiste comuniquese con el administrador"
			);
			return $json;
		}
		
		$xData = (object)$lstParam;
		$id_solicitante = $xData->id_solicitante;
		$lstConceptos = $xData->lstConceptos;
		$ptl_ptl = $xData->ptl_ptl;
		$rfc = $xData->rfc;
		$nombre_solicitante = $xData->nombre_solicitante;
		$correo_rtfc = $xData->correo_rfc;
		
		$ficha = new WSPagos();
		$parametros = new clsParametros($this->getLinkMysql());
		$p = $parametros->search_parametros();
		$sm = $p->parametros["smdf"]->valor;
		$iva = $p->parametros["iva"]->valor/100;
		
		/*procesar folio*/
		$monto_total = 0;
		$monto_tot_conc = 0;
		$monto_total_iva = 0;
		$monto_total_sin_iva = 0;
		
		$detalles = array();
		
		$claves= array();
		
		/* Procesamos los conceptos */
		foreach($lstConceptos as $i => $x_concepto){
			
			$claves[] = $x_concepto->clave;
			
			$resp_concepto = $this->getConcepto($x_concepto->clave);
				
			if(!$resp_concepto){
				$json = (object)array(
					"error" => true
					,"msg" => "EPS001: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador"
				);
				return $json;
			}
			
			if(!$resp_concepto->count){
				$json = (object)array(
					"error" => true
					,"msg" => "EPS002: El concepto $x_concepto->clave no existe"
				);
				return $json;
			}
				
			$concepto = $resp_concepto->concepto;
			
			if(!$concepto){
				$json = (object)array(
					"error" => true
					,"msg" => "ESP003: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador"
				);
				return $json;
			}
			
			$id_concepto_pago= $concepto->id_concepto_pago;
			$calcula_iva= $concepto->calcula_iva;
			$importe_smdf=$concepto->importe_smdf;
			$importe_pesos=$concepto->importe_pesos;
			$costo_variable=$concepto->costo_variable;
			
			$precio_variable = 0;
			
			if(isset($x_concepto->precio_variable)){
				$precio_variable = $x_concepto->precio_variable;
			}
			
			
			$MontosConcepto = 
			$this->getMontosConcepto(	 
				$id_concepto_pago
				,$x_concepto->cantidad
				,$sm
				,$calcula_iva
				,$iva
				,$importe_smdf
				,$importe_pesos
				,$costo_variable
				,$precio_variable
			);
			//die("dddd");
			$MontosConcepto->clave = $concepto->id_concepto_pago;
			$MontosConcepto->id_concepto_pago = $concepto->id_concepto_pago;
			$MontosConcepto->importe_pesos = $MontosConcepto->importe_sin_iva;
			$MontosConcepto->nom_concepto_pago = $concepto->nom_concepto_pago;
			$MontosConcepto->precio_variable = $precio_variable;
			$MontosConcepto->costo_variable = $concepto->costo_variable;
				
			$detalles[$x_concepto->clave] = $MontosConcepto;
			
			$monto_total+= $MontosConcepto->monto_tot_conc;
			$monto_total_iva+= $MontosConcepto->iva_total;
			$monto_total_sin_iva+= $MontosConcepto->importe_sin_iva;
			//die("eqqqqeqwwwwwww");
		}
		//die("eqeqwwqqqqwww");
		/* Obtenemos el folio de la solicitu */
		$folio = $this->getFolio($id_solicitante);
		
		if(!$folio){
			$resp = (object)array(
				"error" => true
				,"msg" => "EPS004: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador"
			);
				
			return $json;
		}
		//die("dddd");
		/*crear datos de facturacion*/
		
		$monto_total = number_format($monto_total, 2, '.', '');
		$monto_total_iva = number_format($monto_total_iva, 2, '.', '');
		$monto_total_sin_iva = number_format($monto_total_sin_iva, 2, '.', '');
	
		$array_claves = implode(",", $claves);
		
		$info = array(
			'tipoPago' => "EF"
			,'importe' => $monto_total
			,'concepto' => $array_claves
		);
		//die("dddd");
		$lstParam = array(
			"id_solicitante" => $id_solicitante
			,"folio_sol" => $folio
			,"serie_fac" => $cveserie
			,"monto_total_iva" => number_format($monto_total_iva, 2, '.', '')
			,"monto_total_sin_iva" => number_format($monto_total_sin_iva, 2, '.', '')
			,"monto_tot_conc" => $monto_total
			,"referencia_ban" => ""
			,"ptl_ptl" => is_null($ptl_ptl) ? "" : $ptl_ptl
			,"nombre_solicitante" => is_null($nombre_solicitante) ? "" : $nombre_solicitante
			,"rfc"=> is_null($rfc) ? "" : $rfc
			,"correo_rfc" => is_null($correo_rfc) ? "" : $correo_rfc
			,"factura"=> is_null($rfc) ? 0 : 1
			
		);
		
		$resp = $this->agregar_solicitud($lstParam);
		
		if(!$resp){
			$json = (object)array(
				"error" => true
				,"msg" => "EPS005: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador"
				,"query" => $this->getLastQuery()
				,"debug" => $this->getError()
			);
			return $json;
		}
		
		foreach($detalles as $clave => $det){
		
			$resp = $this->agregar_detalle($id_solicitante, $folio, $det );
					
			if(!$resp){
				$json = (object)array(
					"error" => true
					,"msg" => "EPS006: No se pudo conectarse con el servidor, si el problema persiste comuniquese con el administrador"
					,"query" => $this->getLastQuery()
					,"debug" => $this->getError()
				);
				return $json;
			}
		}
		
		$result = $ficha->getFichaDeposito($info);
		
		if($result == WSP_SUCCESS) {
			
			$fecha = $ficha->getResponse()->fecha;
			$referencia = $ficha->getResponse()->referencia;
		}
		else{
			/*-------------------------------------------------------------------------------------------------------------------*/
			#Error genera ficha en la bitacora
			
				$error_result = $ficha->getResponse()->error;
				
				if($result != 200 ) {
					
					$salto="\r\n";
					$referencia=$valor['ref'];
					
					$log = new clsLog($logfile='error:WS_genera_ficha.log');
					$log_message = ' El usuario <'.$usr_solicitante.'-'.utf8_decode($nom_solicitante).'>'.' con folio '.'<'.$folio.'>'.' No pudo generar su ficha de referencia'.$salto.'Error Web Service: ('.$result.' '.$error_result.')'.$salto;
					$log->writeLog($log_message);
				
					$json = (object)array(
						"error" => true
						,"valid" => true
						,"msg" => "EPS007: No se pudo generar su ficha de referencia, si el problema persiste comuniquese con el administrador"
						,"debug" => $result
					);
					
					return $json;
			
				}
				
			/*-------------------------------------------------------------------------------------------------------------------*/
	
		}
		
		/*Actualizamos la solicitud*/
		$resp = $this->update_solicitud($id_solicitante, $folio, array("referencia_ban" => $referencia ));
	
		$resp = (object)array(
			"error" => false
			,"msg" => "Se creo la solicitud"
			,"folio" => $folio
			,"referencia" => $referencia
			,"monto_total" => $monto_total
			,"fecha" => $fecha
			,"conceptos" => $array_claves
		);
		
		return $resp;
	
	}
	
	public function agregar_solicitud($lstParam=array()){
	
		$s = (object)$lstParam;
		
		if(!isset($s->rfc)){
			$s->rfc="";
		}
		
		if(!isset($s->correo_rfc)){
			$s->correo_rfc="";
		}
		
		$query = " INSERT solicitud_pago (
		id_solicitante
		,folio_sol
		,referencia_ban
		,serie_fac
		,monto_total_iva
		,monto_total_sin_iva
		,monto_total
		,folio_fac
		,folio_ticket
		,fec_sol
		,fec_ref_env
		,fec_deposito_env
		,fec_factura
		,cve_edo_sol
		,cve_edo_pago
		,comentario
		,factura
		,ptl_ptl
		,nombre_solicitante
		,rfc
		,correo_rfc
		,fec_actualizacion
		,fec_termino_sol
		) values (
		 $s->id_solicitante
		,$s->folio_sol
		,'$s->referencia_ban'
		,'$s->serie_fac'
		,$s->monto_total_iva
		,$s->monto_total_sin_iva
		,$s->monto_tot_conc
		,NULL
		,NULL
		,NOW()
		,NOW()
		,NOW()
		,NULL
		,'ENPR'
		,'FIENV'
		,NULL
		,$s->factura
		,'$s->ptl_ptl'
		,'$s->nombre_solicitante'
		,'$s->rfc'
		,'$s->correo_rfc'
		,NOW()
		,NULL
		)
		";
		
		//die($query);
		$resp = $this->executeQueryMysql($query);
		
		
		if(!$resp){
			return false;
		}
		
		return true;
			
	}
	
	
	public function agregar_detalle($id_solicitante, $folio_sol, $detalle){
  
		if(!$detalle){
			return false;
			//return -1;
		}
		
		//print_r($detalle);
		
		$query = "INSERT INTO det_sol_pagos (
		id_solicitante
		,folio_sol
		,id_concepto_pago
		,cant_requerida
		,monto
		,monto_iva
		,monto_tot_conc
		) VALUES (
		$id_solicitante
		,$folio_sol
		,'$detalle->id_concepto_pago'
		,$detalle->cantidad
		,$detalle->importe_pesos
		,$detalle->iva
		,$detalle->monto_tot_conc
		)";
	
		$resp = $this->executeQueryMysql($query);
		
		//print_r($query);exit;
		
		if(!$resp){
			//return -2;
			return false;
		}
		
		return true;
	}
	
	
	public function  update_solicitud($id_solicitante, $folio_sol, $lstParam=array()){
	
		$query = " UPDATE solicitud_pago SET fec_actualizacion = NOW() ";
		
		foreach($lstParam as $col => $val){
			if($val == "NOW()"){
				$query .= ", $col = $val";
			}
			else{
				$query .= ", $col = '$val'";
			}
			
		}
		
		$query .= " WHERE id_solicitante = $id_solicitante AND folio_sol = $folio_sol ";
		
		//die($query);
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		return true;
	}
	
	
	public function getSolicitud($folio_sol, $id_solicitante = NULL){
		
		$lstParam = array("folio_sol" => $folio_sol);
		if($id_solicitante!=NULL){
			$lstParam["id_solicitante"] = $id_solicitante;
			}
		
		$sol = $this->search_solicitudes($lstParam);
		
		if(!$sol){
			return $sol;
		}
		
		if(!$sol->count){
			return (object)array("count" => 0, "solicitud" => array());
		}
		$detalle = $this->getDetalleSolicitud($folio_sol, $id_solicitante);
		
		return (object)array("count" => 1, "solicitud" => (object)array("solicitud" => $sol->solicitudes[0], "detalle" => $detalle->detalles));
		
	}
	
	
	
	
	public function search_solicitudes($lstParam = array()){

		$query = "".
		" select 
		s.id_solicitante
		,sp.nombre
		,sp.ap_paterno
		,sp.ap_materno
		,sp.correo_e
		,sp.ptl_ptl
		,CONCAT(sp.nombre, ' ', sp.ap_paterno, ' ', sp.ap_materno) as nombre_completo
		,sp.telefono
		,s.folio_sol
		,s.referencia_ban
		,s.serie_fac
		,s.monto_total_iva
		,s.monto_total_sin_iva
		,s.monto_total
		,s.folio_fac
		,s.folio_ticket
		,s.fec_sol
		,s.fec_ref_env
		,s.fec_deposito_env
		,s.fec_factura
		,s.cve_edo_sol
		,es.nom_edo_sol
		,s.cve_edo_pago
		,e.nom_edo_pago
		,s.comentario
		,if(s.cve_edo_pago = 'ACDP', 'Si', 'No') as aclaracion
		,s.factura
		,if(s.factura = 0, 'No', 'Si') as factura_text
		,s.ptl_ptl
		,s.nombre_solicitante
		,s.rfc
		,s.correo_rfc
		,s.ft_generada
		,s.fec_actualizacion
		,s.fec_termino_sol
		from solicitud_pago s, ct_estado_pago e, ct_estado_sol es, solicitantes_pago sp
		where e.cve_edo_pago = s.cve_edo_pago AND es.cve_edo_sol = s.cve_edo_sol
		AND s.id_solicitante = sp.id_solicitante
    ";
		
		$where = "";
		foreach($lstParam as $col => $val){
		
			switch($col){
				case "folio_sol":
					$where.= " AND s.$col like '%$val%' "; break;
				case "id_solicitante":
				case "referencia_ban":
				case "serie_fac":
				case "monto_total_iva":
				case "monto_total_sin_iva":
				case "monto_total":
				case "folio_fac":
				case "folio_ticket":
				case "fec_sol":
				case "fec_ref_env":
				case "fec_deposito_env":
				case "fec_factura":
				case "cve_edo_sol":
				case "cve_edo_pago":
				case "comentario":
				case "factura":
				case "ptl_ptl":
				case "nombre_solicitante":
				case "rfc":
				case "correo_rfc":
				case "ft_generada":
				case "fec_actualizacion":
				case "fec_termino_sol":
					$where.= " AND s.$col = '$val' "; break;
				case "fnombre":		
					$where.= " AND CONCAT(sp.nombre, ' ', sp.ap_paterno, ' ', sp.ap_materno) like '%$val%' "; 
					break;
			}
		}
		
		$query = $where == "" ? $query : $query . $where;
		//$query = $where == "" ? $query : $query . $where;
			
		
		
		//die($query);
		$lstParam = (object)$lstParam;
		if(isset($lstParam->sidx)){
			//echo "dsds";
			if(($lstParam->sidx != "")&&(($lstParam->sord == "asc")||($lstParam->sord == "desc"))){
				$query .= " ORDER BY ".$lstParam->sidx." ".$lstParam->sord;
			}
		}

		//print_r($lstParam);
		if((isset($lstParam->limit))&&(isset($lstParam->start))){
			if(((is_numeric($lstParam->start))&&(is_numeric($lstParam->limit)))&&(($lstParam->start>=0)&&($lstParam->limit>=0))){
				$query .= " LIMIT ".$lstParam->start.",".$lstParam->limit;
				
				//print_r($lstParam);
				//echo "__________";
				//die($query);
			}
		} 
		
		//die($query);
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		
		if(isset($lstParam->getCount)){
			$num = $resp->num_rows;
			$resp->close();
			return (object)array("count" => $num, "conceptos" => array());
		}
		
		$j=0;
		$lst = array();
		while ($obj = $resp->fetch_object()){
			$lst[] = $obj;
			$j++;
		}
		
		$resp->close();
		return (object)array("count" => $j, "solicitudes" => $lst);
	}


	function getDetalleSolicitud($folio_sol, $id_solicitante = NULL){
		
		
		$and = "";
		if($id_solicitante!=NULL){
			$and = " AND d.id_solicitante = $id_solicitante";
		}
		
		$query = "".
		"
		SELECT 
		d.id_solicitante
		,d.folio_sol
		,d.id_concepto_pago
		,c.nom_concepto_pago
		,c.id_area
		,a.nom_area
		,d.cant_requerida
		,d.entregado
		,if(ISNULL(d.entregado), 'No', 'Si') as entregado_text
		,d.comentario_resp
		,d.comentario_UA
		,(d.monto + d.monto_iva) as importe
		,(d.monto*d.cant_requerida) as precio_unitario
		,(d.monto_iva*d.cant_requerida) as iva
		,d.monto
		,d.monto_iva
		,d.monto_tot_conc
		,d.id_usuario_entrego
		,d.fec_entrega
		FROM det_sol_pagos d, ct_concepto_pago c, ct_area a
		WHERE c.id_concepto_pago = d.id_concepto_pago AND a.id_area = c.id_area
		AND d.folio_sol = $folio_sol $and
		";
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		$j=0;
		$lst = array();
		while ($obj = $resp->fetch_object()){
			
			$lst[] = $obj;
			$j++;
		}
		
		$resp->close();
		return (object)array("count" => $j, "detalles" => $lst);
		
	}

	
	
	public function search_detalle_solicitud($lstParam=array()){
    
    $query = "".
    "
    SELECT
    s.id_solicitante
    ,s.id_perfil
    ,s.nombre
    ,s.ap_paterno
    ,s.ap_materno
    ,s.correo_e
    ,s.telefono
    ,s.celular
    ,s.nom_ptl
    ,s.ptl_ptl
    ,s.exp_unam
    ,sp.folio_sol
    ,sp.referencia_ban
    ,sp.serie_fac
    ,sp.monto_total_iva
    ,sp.monto_total_sin_iva
    ,sp.monto_total
	,sp.nombre_solicitante
	,sp.factura
	,sp.rfc
	,sp.correo_rfc
	,sp.ft_generada
    ,sp.folio_fac
    ,sp.folio_ticket
    ,sp.fec_sol
    ,sp.fec_ref_env
    ,sp.fec_deposito_env
    ,sp.fec_factura
    ,sp.cve_edo_sol
    ,es.nom_edo_sol
    ,sp.cve_edo_pago
    ,ep.nom_edo_pago
    ,sp.fec_actualizacion
    ,sp.fec_termino_sol
    ,d.id_concepto_pago
    ,cp.nom_concepto_pago
    ,cp.cuenta
    ,cp.id_area
    ,a.nom_area
    ,d.cant_requerida
    ,d.entregado
    ,if(ISNULL(d.entregado), 'No', 'Si') as entregado_text
    ,d.monto
    ,(d.monto + d.monto_iva) as importe
    ,(d.monto*d.cant_requerida) as precio_unitario
    ,(d.monto_iva*d.cant_requerida) as iva
    ,d.monto_iva
    ,d.monto_tot_conc
    ,d.id_usuario_entrego
    ,d.fec_entrega
    ,d.comentario_resp
    ,d.comentario_UA
    FROM solicitantes_pago s, solicitud_pago sp, ct_estado_pago ep,
    ct_estado_sol es, det_sol_pagos d, ct_concepto_pago cp, ct_area a
    WHERE s.id_solicitante = sp.id_solicitante AND sp.cve_edo_pago = ep.cve_edo_pago
    AND sp.cve_edo_sol = es.cve_edo_sol AND sp.folio_sol = d.folio_sol
    AND cp.id_concepto_pago = d.id_concepto_pago AND cp.id_area = a.id_area
    ";
    //AND d.folio_sol = $folio_sol
    //";
    
    $where = "";
    foreach($lstParam as $col => $val){
    
      switch($col){
        case "id_solicitante":
        case "id_perfil":
        case "correo_e":
        case "telefono":      
        case "celular":      
        case "nom_ptl":
        case "ptl_ptl":
        case "exp_unam":
			$where.= " AND s.$col = '$val' "; break;
		case "fnombre":
			$where.= " AND CONCAT(s.nombre, s.ap_paterno, s.ap_materno) like '%$val%' "; break;
		case "fcorreo_e":
			$where.= " AND s.$col like '%$val%' "; break;
		case "nombre":
        case "ap_paterno":
        case "ap_materno":
			$where.= " AND s.$col like '%$val%' "; break;
        case "folio_sol":
        case "referencia_ban": 
        case "serie_fac":
        case "monto_total_iva":
        case "monto_total_sin_iva":
        case "monto_total":
		case "nombre_solicitante":
		case "rfc":
		case "correo_rfc":
		case "ft_generada":
        case "folio_fac":
        case "folio_ticket":
        case "fec_sol":
        case "fec_ref_env":
        case "fec_deposito_env":
        case "fec_factura":
        case "cve_edo_sol":
        case "nom_edo_sol":
        case "cve_edo_pago":
        case "nom_edo_pago":
        case "fec_actualizacion":
        case "fec_termino_sol":
			$where.= " AND sp.$col = '$val' "; break;
		case "id_usuario_entrego":
        case "fec_entrega":
        case "id_concepto_pago":  
		case "cant_requerida":
        case "entregado":
        case "monto":
			$where.= " AND d.$col = '$val' "; break;
        case "nom_concepto_pago":
        case "cuenta":
        case "id_area":
			$where.= " AND cp.$col = '$val' "; break;
        case "nom_area":      
			$where.= " AND a.$col = '$val' "; break;
        case "importe":
        case "precio_unitario":
        case "iva":
        case "monto_iva":
        case "monto_tot_conc":
			$where.= " AND $col = '$val' "; break;
        
      }
    }
    
    //$query = $where == "" ? $query : $query . " WHERE " . substr($where, 4);
    $query = $where == "" ? $query : $query . $where;
      
    
    //die($query);
    $lstParam = (object)$lstParam;
    if(isset($lstParam->sidx)){
      //echo "dsds";
      if(($lstParam->sidx != "")&&(($lstParam->sord == "asc")||($lstParam->sord == "desc"))){
        $query .= " ORDER BY ".$lstParam->sidx." ".$lstParam->sord;
      }
    }

    //print_r($lstParam);
    if((isset($lstParam->limit))&&(isset($lstParam->start))){
      if(((is_numeric($lstParam->start))&&(is_numeric($lstParam->limit)))&&(($lstParam->start>=0)&&($lstParam->limit>=0))){
        $query .= " LIMIT ".$lstParam->start.",".$lstParam->limit;
        
        //print_r($lstParam);
        //echo "__________";
        //die($query);
      }
    } 
    
    //die($query);
    $resp = $this->executeQueryMysql($query);
    
    if(!$resp){
      return false;
    }
    
    //die("dasd");
    if(isset($lstParam->getCount)){
      //die("aaadasd");
      $num = $resp->num_rows;
      $resp->close();
      return (object)array("count" => $num, "detalles" => array());
    }
    //die("bbbbdasd");
    
    $j=0;
    $lst = array();
    while ($obj = $resp->fetch_object()){
      
      $lst[] = $obj;
      $j++;
    }
    
    $resp->close();
    return (object)array("count" => $j, "detalles" => $lst);
    
  }


  public function detalle_solicitud($lstParam=array()){

    
    $query = "".
    "
    select 
    id_solicitante
    ,folio_sol
    ,id_concepto_pago
    ,cuenta
    ,nom_concepto_pago
    ,cant_requerida
    ,monto
    ,monto_iva
    ,monto_tot_conc
    ,monto_total
    ,monto_total_iva
    ,monto_total_sin_iva
    ,id_usuario_entrego
    ,fec_sol
    ,fec_entrega
    ,nom_persona_sol
    ,ptl_ptl
    ,folio_fac
    ,referencia_ban
    ,folio_ticket
    ,cve_edo_pago
    ,nom_edo_pago
    ,comentario_resp
    ,nom_area
    ,nom_sub
    ,id_area
    ,id_subdireccion
    ,entregado
    ,clvEntregado
    ,nom_usr
    ,fecha_sol
    ,fec_factura
    FROM vw_conceptos_sol_area
    ";
    //AND d.folio_sol = $folio_sol
    //";
    
    $where = "";
    foreach($lstParam as $col => $val){
    
      switch($col){
        CASE "id_solicitante":    $where.= " AND $col = '$val' "; break;
        CASE "folio_sol":     $where.= " AND $col like '%$val%' "; break;
        CASE "id_concepto_pago":  $where.= " AND $col = '$val' "; break;
        CASE "cuenta":        $where.= " AND $col = '$val' "; break;
        CASE "nom_concepto_pago": $where.= " AND $col = '$val' "; break;
        CASE "cant_requerida":    $where.= " AND $col = '$val' "; break;
        CASE "monto":       $where.= " AND $col = '$val' "; break;
        CASE "monto_iva":     $where.= " AND $col = '$val' "; break;
        CASE "monto_tot_conc":    $where.= " AND $col = '$val' "; break;
        CASE "monto_total":     $where.= " AND $col = '$val' "; break;
        CASE "monto_total_iva":   $where.= " AND $col = '$val' "; break;
        CASE "monto_total_sin_iva": $where.= " AND $col = '$val' "; break;
        CASE "id_usuario_entrego":  $where.= " AND $col = '$val' "; break;
        CASE "fec_sol":       $where.= " AND $col = '$val' "; break;
        CASE "fec_entrega":     $where.= " AND $col = '$val' "; break;
        CASE "nom_persona_sol":   $where.= " AND $col like '%$val%' "; break;
        CASE "ptl_ptl":       $where.= " AND $col = '$val' "; break;
        CASE "folio_fac":     $where.= " AND $col = '$val' "; break;
        CASE "referencia_ban":    $where.= " AND $col = '$val' "; break;
        CASE "folio_ticket":    $where.= " AND $col = '$val' "; break;
        CASE "cve_edo_pago":    $where.= " AND $col = '$val' "; break;
        CASE "nom_edo_pago":    $where.= " AND $col = '$val' "; break;
        CASE "comentario_resp":   $where.= " AND $col = '$val' "; break;
        CASE "nom_area":      $where.= " AND $col = '$val' "; break;
        CASE "nom_sub":       $where.= " AND $col = '$val' "; break;
        CASE "id_area":       $where.= " AND $col = '$val' "; break;
        CASE "id_subdireccion":   $where.= " AND $col = '$val' "; break;
        CASE "entregado":     $where.= " AND $col = '$val' "; break;
        CASE "clvEntregado":    $where.= " AND $col = '$val' "; break;
        CASE "nom_usr":       $where.= " AND $col = '$val' "; break;
        CASE "fecha_sol":     $where.= " AND $col = '$val' "; break;
        CASE "fec_factura":     $where.= " AND $col = '$val' "; break;
        //busqueda
        CASE "fec_sol1":      $where.= " AND fec_sol >= '$val' "; break;
        CASE "fec_sol2":      $where.= " AND fec_sol <= '$val' "; break;
        CASE "fec_actualizacion1":  $where.= " AND fec_actualizacion >= '$val' "; break;
        CASE "fec_actualizacion2":  $where.= " AND fec_actualizacion <= '$val' "; break;
        CASE "fec_factura1":    $where.= " AND fec_factura >= '$val' "; break;
        CASE "fec_factura2":    $where.= " AND fec_factura <= '$val' "; break;
        CASE "folio_sol1":      $where.= " AND folio_sol >= '$val' "; break;
        CASE "folio_sol2":      $where.= " AND folio_sol <= '$val' "; break;

        
      }
    }
    
    $query = $where == "" ? $query : $query . " WHERE " . substr($where, 4);
    //$query = $where == "" ? $query : $query . $where;
      
    
    //die($query);
    $lstParam = (object)$lstParam;
    if(isset($lstParam->sidx)){
      //echo "dsds";
      if(($lstParam->sidx != "")&&(($lstParam->sord == "asc")||($lstParam->sord == "desc"))){
        $query .= " ORDER BY ".$lstParam->sidx." ".$lstParam->sord;
      }
    }

    //print_r($lstParam);
    if((isset($lstParam->limit))&&(isset($lstParam->start))){
      if(((is_numeric($lstParam->start))&&(is_numeric($lstParam->limit)))&&(($lstParam->start>=0)&&($lstParam->limit>=0))){
        $query .= " LIMIT ".$lstParam->start.",".$lstParam->limit;
        
        //print_r($lstParam);
        //echo "__________";
        //die($query);
      }
    } 
    
    //die($query);
    $resp = $this->executeQueryMysql($query);
    
    if(!$resp){
      return false;
    }
    
    //die("dasd");
    if(isset($lstParam->getCount)){
      //die("aaadasd");
      $num = $resp->num_rows;
      $resp->close();
      return (object)array("count" => $num, "detalles" => array());
    }
    //die("bbbbdasd");
    
    $j=0;
    $lst = array();
    while ($obj = $resp->fetch_object()){
      
      $lst[] = $obj;
      $j++;
    }
    
    $resp->close();
    return (object)array("count" => $j, "detalles" => $lst);
    
  }
  
  /*verificar pago*/
  
	public function quitar_acentos($cadena){
		$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
		$modificadas = 'AAAAAAACEEEEIIIIDNOOOOOOUUUUYBSAAAAAAACEEEEIIIIDNOOOOOOUUUYYBY';
		$cadena = utf8_decode($cadena);
		$cadena = strtr($cadena, utf8_decode($originales), $modificadas);
		return utf8_encode($cadena);
	}
  
	
	public function verificarPago($folio_sol, $id_solicitante = NULL) {	

		$lstParam = array("folio_sol" => $folio_sol);
		if($id_solicitante!=NULL){
			$lstParam["id_solicitante"] = $id_solicitante;
		}
	
		$resp = $this->search_solicitudes($lstParam);
	
		if(!$resp){
			return false;
		}
	
		if(!$resp->count){
			return (object)array("count" => 0, "pagado" => 0, "factura" => 0);
		}
	
		$id_solicitante = $resp->solicitudes[0]->id_solicitante;
		$estado = $resp->solicitudes[0]->cve_edo_pago;
		$ref = $resp->solicitudes[0]->referencia_ban;
	
		if($estado == "PFIN"){
			return $this->generarFacturaTicket($id_solicitante, $folio_sol);
		}
	
		if($estado != "FIENV"){
			return (object)array("count" => 1, "pagado" => 0, "factura" => 0);
		}

		$ficha = new WSPagos();
	
		$resp =  $ficha->getStatus($ref);	

		if($resp == WSP_SUCCESS) {
			
			$lstParam=array("cve_edo_sol" => 'FINZ',"cve_edo_pago" => 'PFIN');
			
			$resp = $this->update_solicitud($id_solicitante, $folio_sol, $lstParam);
			
			if(!$resp){
				return false;
			}
			
			return $this->generarFacturaTicket($id_solicitante, $folio_sol);
		}
	
		return (object)array("count" => 1, "pagado" => 1, "factura" => 1);
	
	}
	
	
	public function generarFacturaTicket($id_solicitante, $folio_sol){
	
		$lstParam = array(
			"id_solicitante" => $id_solicitante
			,"folio_sol" => $folio_sol
		);
		
		$resp = $this->search_detalle_solicitud($lstParam);
	
		if(!$resp){
			return false;
		}
		
		if(!$resp->count){
			return false;
		}
		
		$cve_edo_sol = $resp->detalles[0]->cve_edo_sol;
		$cve_edo_pago = $resp->detalles[0]->cve_edo_pago;
		
		if($cve_edo_pago != "PFIN"){
			return (object)array("count" => 1, "pagado" => 0, "factura" => 1);
		}
		
		if($resp->detalles[0]->ft_generada){
			return (object)array("count" => 1, "pagado" => 1, "factura" => 1);
		}
		
		$monto_total = 0;
		$email_e = "";
		$email_rfc = "";
		$rfc = "";
		$factura = 0;
		
		
		foreach($resp->detalles as $i => $valor) {
		
			$email_e = $valor->correo_e;
			$email_rfc = $valor->correo_rfc;
			$monto_total = $valor->monto_total;
			$rfc = $valor->rfc;
			$folio_fac = $valor->folio_fac;
			$factura = $valor->factura;
			
			$productos[] = array(
				'cuenta' => "202"
				,'cantidad' => $valor->cant_requerida
				,'unidad' => "NO APLICA"
				,'concepto' => $this->quitar_acentos($valor->nom_concepto_pago)
				,'importe' =>  $valor->importe
				,'iva' => '16'
				,'importe_iva' => $valor->monto_iva
				,'descto' => 0
				,'descto_impte' => 0.00
				,'total' => $valor->monto_tot_conc
			);
	
		}
	
		$datos_fac = "";
		if($rfc != ""){
			$datosFacturacion = new clsDatosFacturacion($this->getLinkMysql());
		
			$resp = $datosFacturacion->getDatosFacturacion($id_solicitante, $rfc);
		
			if(!$resp){
				return false;
			}
			
			if(!$resp->count){
				return false;
			}
		
			if($email_rfc != null){
				$email = $email_rfc;
			}
			else{
				$email = $email_e;
			}
			
			$email = "ammoises@gmail.com";
			
			$datos_fac = $resp->datos;
			$datos_fac["email"] = $email;
		
		}
	
		$observaciones = 'Pruebas';
	
		$ficha = new WSPagos();
		
		//echo $ficha->getUser();
		//print_r($ficha); exit;
		
		$result = $ficha->GenerateInvoice(
			$datos_fac
			, $productos
			, $monto_total
			, $observaciones
			, $factura);
		
		
		$resp_ws = get_object_vars($ficha->getResponse());
		
		if($result == WSP_SUCCESS) {
			
			if($factura){
				$num_folio_fac=$resp_ws[2];
			}
			else{
				$num_folio_tic=$resp_ws[2];
				$cantidad_tic=$resp_ws[3];
				$fec_tic=$resp_ws[4];
			}
	
		
			if($factura){
				$lstParam=array(
					"folio_fac" =>$num_folio_fac
					,"folio_ticket" =>0
					,"fec_factura" => "NOW()"
					,"fec_termino_sol" => "NOW()"
					,"ft_generada" => 1
				);
			}
			else{
				$lstParam=array(
					 "folio_fac" =>0
					,"folio_ticket" =>$num_folio_tic
					,"fec_factura" =>$fec_tic
					,"fec_termino_sol" =>$fec_tic
					,"ft_generada" => 1
				);
			}
			
			$resp = $this->update_solicitud($id_solicitante, $folio_sol, $lstParam);
			
			if(!$resp){
				return false;
			}
			
			return (object)array("count" => 1, "pagado" => 1, "factura" => 1);
		
		}
		else{
			
			$men_error1=$resp_ws[1];
			$men_error2=$resp_ws[4];
			
		}
	
		return (object)array("count" => 1, "pagado" => 0, "factura" => 0);
	
	}



  


}



	
?>
