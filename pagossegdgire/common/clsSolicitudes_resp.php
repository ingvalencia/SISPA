<?php

require_once ("config.php");
require_once ("clsConceptosPago.php");
require_once ("clsCatalogos.php"); 
	
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
		,'$s->rfc'
		,'$s->correo_rfc'
		,NOW()
		,NULL
		)
		";
		
		
		$resp = $this->executeQueryMysql($query);
		
		
		if(!$resp){
			return false;
		}
		
		return true;
			
	}
	
	
	public function agregar_detalle($id_solicitante, $folio_sol, $detalle){
  
    if(!$detalle){
      return false;
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
      return false;
    }
    
    return true;
  }
	
	
	public function update_solicitud($id_solicitante, $folio_sol, $lstParam=array()){
	
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
	
	
	public function getSolicitud($folio_sol){
		
		$sol = $this->search_solicitudes(array("folio_sol" => $folio_sol));
		
		$detalle = $this->getDetalleSolicitud($folio_sol);
		
		return (object)array("solicitud" => $sol->solicitudes[0], "detalle" => $detalle->detalles);
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
		,s.rfc
		,s.correo_rfc
		,s.fec_actualizacion
		,s.fec_termino_sol
		from solicitud_pago s, ct_estado_pago e, ct_estado_sol es, solicitantes_pago sp
		where e.cve_edo_pago = s.cve_edo_pago AND es.cve_edo_sol = s.cve_edo_sol
		AND s.id_solicitante = sp.id_solicitante
    ";
		
		$where = "";
		foreach($lstParam as $col => $val){
		
			switch($col){
				case "id_solicitante":		$where.= " AND s.$col = '$val' "; break;
				case "folio_sol":			$where.= " AND s.$col like '%$val%' "; break;
				case "referencia_ban":		$where.= " AND s.$col = '$val' "; break;
				case "serie_fac":			$where.= " AND s.$col = '$val' "; break;
				case "monto_total_iva":		$where.= " AND s.$col = '$val' "; break;
				case "monto_total_sin_iva":	$where.= " AND s.$col = '$val' "; break;
				case "monto_total":			$where.= " AND s.$col = '$val' "; break;
				case "folio_fac":			$where.= " AND s.$col = '$val' "; break;
				case "folio_ticket":		$where.= " AND s.$col = '$val' "; break;
				case "fec_sol":				$where.= " AND s.$col = '$val' "; break;
				case "fec_ref_env":			$where.= " AND s.$col = '$val' "; break;
				case "fec_deposito_env":	$where.= " AND s.$col = '$val' "; break;
				case "fec_factura":			$where.= " AND s.$col = '$val' "; break;
				case "cve_edo_sol":			$where.= " AND s.$col = '$val' "; break;
				case "cve_edo_pago":		$where.= " AND s.$col = '$val' "; break;
				case "comentario":			$where.= " AND s.$col = '$val' "; break;
				case "factura":				$where.= " AND s.$col = '$val' "; break;
				case "rfc":					$where.= " AND s.$col = '$val' "; break;
				case "fec_actualizacion":	$where.= " AND s.$col = '$val' "; break;
				case "fec_termino_sol":		$where.= " AND s.$col = '$val' "; break;
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


	function getDetalleSolicitud($folio_sol){
		
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
		AND d.folio_sol = $folio_sol
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
        case "id_solicitante":    $where.= " AND s.$col = '$val' "; break;
        case "id_perfil":     $where.= " AND s.$col = '$val' "; break;
        case "nombre":        $where.= " AND s.$col like '%$val%' "; break;
        case "ap_paterno":    $where.= " AND s.$col like '%$val%' "; break;
        case "ap_materno":    $where.= " AND s.$col like '%$val%' "; break;
		case "fnombre":		  $where.= " AND CONCAT(s.nombre, s.ap_paterno, s.ap_materno) like '%$val%' "; break;
		case "fcorreo_e":     $where.= " AND s.$col like '%$val%' "; break;
        case "correo_e":      $where.= " AND s.$col = '$val' "; break;
        case "telefono":      $where.= " AND s.$col = '$val' "; break;
        case "celular":       $where.= " AND s.$col = '$val' "; break;
        case "nom_ptl":       $where.= " AND s.$col = '$val' "; break;
        case "ptl_ptl":       $where.= " AND s.$col = '$val' "; break;
        case "exp_unam":      $where.= " AND s.$col = '$val' "; break;
        case "folio_sol":     $where.= " AND sp.$col = '$val' "; break;
        case "referencia_ban":    $where.= " AND sp.$col = '$val' "; break;
        case "serie_fac":     $where.= " AND sp.$col = '$val' "; break;
        case "monto_total_iva":   $where.= " AND sp.$col = '$val' "; break;
        case "monto_total_sin_iva": $where.= " AND sp.$col = '$val' "; break;
        case "monto_total":     $where.= " AND sp.$col = '$val' "; break;
        case "folio_fac":     $where.= " AND sp.$col = '$val' "; break;
        case "folio_ticket":    $where.= " AND sp.$col = '$val' "; break;
        case "fec_sol":       $where.= " AND sp.$col = '$val' "; break;
        case "fec_ref_env":     $where.= " AND sp.$col = '$val' "; break;
        case "fec_deposito_env":  $where.= " AND sp.$col = '$val' "; break;
        case "fec_factura":     $where.= " AND sp.$col = '$val' "; break;
        case "cve_edo_sol":     $where.= " AND sp.$col = '$val' "; break;
        case "nom_edo_sol":     $where.= " AND es.$col = '$val' "; break;
        case "cve_edo_pago":    $where.= " AND sp.$col = '$val' "; break;
        case "nom_edo_pago":    $where.= " AND ep.$col = '$val' "; break;
        case "fec_actualizacion": $where.= " AND sp.$col = '$val' "; break;
        case "fec_termino_sol":   $where.= " AND sp.$col = '$val' "; break;
        case "id_concepto_pago":  $where.= " AND d.$col = '$val' "; break;
        case "nom_concepto_pago": $where.= " AND cp.$col = '$val' "; break;
        case "cuenta":        $where.= " AND cp.$col = '$val' "; break;
        case "id_area":       $where.= " AND cp.$col = '$val' "; break;
        case "nom_area":      $where.= " AND a.$col = '$val' "; break;
        case "cant_requerida":    $where.= " AND d.$col = '$val' "; break;
        case "entregado":     $where.= " AND d.$col = '$val' "; break;
        case "monto":       $where.= " AND d.$col = '$val' "; break;
        case "importe":       $where.= " AND $col = '$val' "; break;
        case "precio_unitario":   $where.= " AND $col = '$val' "; break;
        case "iva":         $where.= " AND $col = '$val' "; break;
        case "monto_iva":     $where.= " AND $col = '$val' "; break;
        case "monto_tot_conc":    $where.= " AND $col = '$val' "; break;
        case "id_usuario_entrego":  $where.= " AND d.$col = '$val' "; break;
        case "fec_entrega":     $where.= " AND d.$col = '$val' "; break;
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





}



	
?>
