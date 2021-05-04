<?php

require_once ("config.php");
require_once ("clsDatosFacturacion.php"); 
	
class clsSolicitantes extends clsDatosFacturacion{

	public function __construct($linkMysql=NULL){

		parent::__construct();

		return $this->connect($linkMysql);
	}
	
	function getIdSolicitantePagos($correo_e){
		
		$query = " 
			SELECT id_solicitante 
			FROM solicitantes_pago where correo_e='$correo_e' 
		";
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		$num = $resp->num_rows;
		
		if($num == 0){
			return (object)array("count" => 0, "id_solicitante" => 0);
		}
		
		$obj = $resp->fetch_object();
		
		$id_solicitante = $obj->id_solicitante;
			
		return (object)array("count" => 1, "id_solicitante" => $id_solicitante);
		
	}
	
	function getSolicitantePagos(){
		
		$query = " SELECT DISTINCT(id_solicitante) as id_solicitante FROM solicitantes_pago where correo_e='pagosdgire@dgire.unam.mx' ";
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		$obj = $resp->fetch_object();
		
		$id_solicitante = $obj->id_solicitante;
			
		return $id_solicitante;
		
	}
	
	
	function getLastIDSolicitante(){
		
		$query = " SELECT MAX(id_solicitante) as max FROM solicitantes_pago ";
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		$obj = $resp->fetch_object();
		
		$max = $obj->max;
			
		return $max;
		
	}
	
	public function agregar_solicitante($lstParam=array()){
		
		if(!count($lstParam)){
			return false;
		}
		
		$lstParam['cadena_valida'] = base_convert(mt_rand(0x1679616, 0x39AA3FF), 12, 36);
		
		$s = (object)$lstParam;
		
		$query = "".
		" INSERT INTO solicitantes_pago (id_perfil, nombre, ap_paterno, correo_e, telefono, passwd, ".
		" fec_registro, cadena_valida, vigente, ap_materno, celular, nom_ptl, ptl_ptl, exp_unam ) ".
		" VALUES (
			$s->id_perfil
			,'$s->nombre'
			,'$s->ap_paterno'
			,'$s->correo_e'
			,'$s->telefono'
			,md5($s->passwd)
			,NOW()
			,'$s->cadena_valida'
			,$s->vigente
		";
		$query.= isset($s->ap_materno) ? ",'$s->ap_materno' " : ",NULL";
		$query.= isset($s->celular) ? ",'$s->celular' " : ",NULL";
		$query.= isset($s->nom_ptl) ? ",'$s->nom_ptl' " : ",NULL";
		$query.= isset($s->ptl_ptl) ? ",'$s->ptl_ptl' " : ",NULL";
		$query.= isset($s->exp_unam) ? ",'$s->exp_unam' " : ",NULL";
		
		$query.=" ) ";
		
		//die($query);
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		return true;
		
	}
	
	
	public function relacionar_rfc($id_solicitante, $rfc){
		
		
		
		return  true;
	}
	
	
	public function update_solicitante($lstParam=array()){
		
		
		$query = " UPDATE solicitantes_pago SET fec_actualizacion = NOW() ";
		
		foreach($lstParam as $campo => $valor){
			
			$query.= " AND $campo = '$valor' ";
		}
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		return true;
	}
	
	public function existe_rfc($id_solicitante, $rfc){
		
		$query = "
		SELECT id_solicitante, rfc 
		FROM datos_facturacion 
		WHERE id_solicitante = $id_solicitante and rfc = '$rfc'
		";
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		$num = $resp->num_rows;
		$resp->close();
		
		return (object)array("count" => $num);
	}
	
	public function searchRFCS($correo, $rfc = NULL){
		
		
		$cond = "";
		if($rfc!=NULL){
			$cond = " AND df.rfc ='$rfc' ";
		}
		
		$query = "
		SELECT
		s.id_solicitante
		,s.correo_e
		,df.rfc
		FROM solicitantes_pago s
		INNER JOIN datos_facturacion df
		ON df.id_solicitante = s.id_solicitante
		WHERE s.correo_e = '$correo' $cond
		";
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		$j=0;
		$lst = array();
		$id_solicitante = 0;
		while ($obj = $resp->fetch_object()){
			
			$id_solicitante = $obj->id_solicitante;
			$lst[] = $obj->rfc;
			$j++;
		}
		
		return (object)array("count" => $j, "id_solicitante" => $id_solicitante, "rfcs" => $lst);
	}
	
	
	
	public function search_solicitantes($lstParam = array()){

		$query = "
		SELECT 
		s.id_solicitante
		, s.id_perfil
		, p.nom_perfil
		, s.nombre
		, s.ap_paterno
		, s.ap_materno
		, s.correo_e
		, s.telefono
		, s.celular
		, s.passwd
		, s.nom_ptl
		, s.ptl_ptl
		, s.exp_unam
		, s.fec_registro
		, s.cadena_valida
		, s.vigente 
		FROM 
		solicitantes_pago s
		, ct_perfil p 
		WHERE s.id_perfil = p.id_perfil 
		";
		
		$where = "";
		foreach($lstParam as $col => $val){
		
			switch($col){
				case "id_solicitante":	$where.= " AND s.$col = '$val' "; break;
				case "id_perfil":		$where.= " AND s.$col = '$val' "; break;
				case "nombre":			$where.= " AND s.$col = '$val' "; break;
				case "fnombre":			$where.= " AND CONCAT(s.nombre, s.ap_paterno, s.ap_materno) like '%$val%' "; break;
				case "ap_paterno":		$where.= " AND s.$col = '$val' "; break;
				case "ap_materno":		$where.= " AND s.$col = '$val' "; break;
				case "correo_e":		$where.= " AND s.$col = '$val' "; break;
				case "fcorreo_e":		$where.= " AND s.correo_e like '%$val%' "; break;
				case "telefono":		$where.= " AND s.$col = '$val' "; break;
				case "celular":			$where.= " AND s.$col = '$val' "; break;
				case "passwd":			$where.= " AND s.$col = '$val' "; break;
				case "nom_ptl":			$where.= " AND p.$col = '$val' "; break;
				case "ptl_ptl":			$where.= " AND s.$col = '$val' "; break;
				case "exp_unam":		$where.= " AND s.$col = '$val' "; break;
				case "fec_registro":	$where.= " AND s.$col = '$val' "; break;
				case "cadena_valida":	$where.= " AND s.$col = '$val' "; break;
				case "vigente":			$where.= " AND s.$col = '$val' "; break;
				
			}
		}
		
		//$query = $where == "" ? $query : $query . " WHERE " . substr($where, 4);
		$query .= $where;
			
		
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
		return (object)array("count" => $j, "solicitantes" => $lst);
	}


}

	
?>
