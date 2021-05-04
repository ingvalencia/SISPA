<?php

require_once ("config.php");
require_once ("clsConexion.php"); 
require_once ("clsCatalogos.php"); 
	
class clsDatosFacturacion extends clsCatalogos{

	public function __construct($linkMysql=NULL){

		parent::__construct();

		$this->connect($linkMysql);
	}
	

	public function exist_email($email){
		
		$query = " SELECT COUNT(id_solicitante) as cont FROM solicitantes_pago WHERE correo_e = '$email'";

		//die($query);
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		$obj = $resp->fetch_object();
		
		$flag=false;
		if($obj->cont){
			$flag=true;
		}
		
		return (object)array("exist" => $flag);
		
	}
	
	public function exist_rfc($rfc){
		
		$query = " SELECT COUNT(id_solicitante) as cont FROM datos_facturacion WHERE rfc = '$rfc'";
		

		//die($query);
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		$obj = $resp->fetch_object();
		
		$flag=false;
		if($obj->cont){
			$flag=true;
		}
		
		return (object)array("exist" => $flag);
		
	}

	public function agregar_datos_facturacion($lstParam=array()){
		
		$f = (object)$lstParam;
		
		$query = "
		INSERT INTO datos_facturacion (id_solicitante, rfc, tipo_persona, calle, id_ciudad, 
		id_municipio, id_edo, id_cp, num_int, fec_registro, nombre_fisc, nombre, ap_paterno,
		ap_materno, id_colonia, colonia_otra, num_ext) VALUES (
		$f->id_solicitante
		,'$f->rfc'
		,'$f->tipo_persona'
		,'$f->calle'
		,$f->id_ciudad
		,$f->id_municipio
		,$f->id_edo
		,$f->id_cp 
		,'$f->num_int'
		,NOW()
		";
		
		if($f->tipo_persona==1){
			$query.= " ,NULL ";
			$query.= " ,'$f->nombre' ";
			$query.= " ,'$f->ap_paterno' ";
			if($f->ap_paterno!=""){
				$query.= " ,'$f->ap_materno' ";
			}
			else{
				$query.= " ,NULL ";
			}
		
		}
		else{
			$query.= " ,'$f->nombre_fisc' ";
			$query.= " ,NULL ";
			$query.= " ,NULL ";
			$query.= " ,NULL ";
		}
		

		if($f->id_colonia!=""){
			$query.= " ,$f->id_colonia "; 
			$query.= " ,NULL ";
		}
		else{
			$query.= " ,NULL ";
			$query.= " ,'$f->colonia_otra' ";
		}
		
		if($f->num_ext!=""){
			$query.= " ,'$f->num_ext' ";
		}
			
		$query.= ")";
		
		$resp = $this->executeQueryMysql($query);
		
		
		//die($query);
		
		if(!$resp){
			return false;
		}
		
		return true;
	}
	
	public function update_datos_facturacion($latParam=array()){
		
		$query = " INSERT INTO datos_facturacion SET fec_actualizacion = NOW() ";
		
		foreach($lstParam as $col => $val){
			$query.= " AND $col = '$val' ";
		}
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		return true;
		
	}
	
	public function delete_datos_facturacion($id_solicitante, $rfc){
		
		$query = " DELETE FROM datos_facturacion WHERE id_solicitante = $id_solicitante AND rfc = '$rfc' ";
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		return true;
		
	}
	
	public function quitar_acentos($cadena){
		$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ';
		$modificadas = 'AAAAAAACEEEEIIIIDNOOOOOOUUUUYBSAAAAAAACEEEEIIIIDNOOOOOOUUUYYBY';
		$cadena = utf8_decode($cadena);
		$cadena = strtr($cadena, utf8_decode($originales), $modificadas);
		return utf8_encode($cadena);
	}

	public function getDatosFacturacion($id_solicitante, $rfc){
		
			$lstParam = array(
				"id_solicitante" => $id_solicitante
				,"rfc" => $rfc
			);
			
			$resp_datos = $this->search_datos_facturacion($lstParam);
		
			if(!$resp_datos){
				return false;
			}
			
			if(!$resp_datos->count){
				return (object)array("count" => 0, "datos" => array());
			}
		
			$dFac = $resp_datos->rfc[0];
			
			/*informacion de la persona*/
			
			$rfc = $dFac->rfc;
			$nombre =$dFac->nombre;
			$a_paterno =$dFac->ap_paterno;
			$a_materno =$dFac->ap_materno;
			$razon_soc =$nombre.' '.$a_paterno.' '.$a_materno;
			$estado =$dFac->nom_edo;
			$delmunicipio =$dFac->nom_municipio;
			$cp =$dFac->id_cp;
			$colonia =$dFac->nom_colonia;
			$calle =$dFac->calle;
			$num_ext =$dFac->num_ext;
			$num_int =$dFac->num_int;
			
			$datos = [
				'rfc' => $rfc
				,'razon_soc' => $this->quitar_acentos($razon_soc)
				,'nombre' => $this->quitar_acentos($nombre)
				,'a_paterno' => $this->quitar_acentos($a_paterno)
				,'a_materno' => $this->quitar_acentos($a_materno)
				,'estado' => $this->quitar_acentos($estado)
				,'delmunicipio' => $this->quitar_acentos($delmunicipio)
				,'cp' => $cp
				,'colonia' => $this->quitar_acentos($colonia)
				,'calle' => $this->quitar_acentos($calle)
				,'num_ext' => $this->quitar_acentos($num_ext)
				,'num_int' => $this->quitar_acentos($num_int)
				,'email' => ""
			];
			
			return (object)array("count" => 1, "datos" => $datos);
		
	}
	
	public function search_datos_facturacion($lstParam = array()){

		$query = "
		SELECT 
		f.id_solicitante
		,f.rfc
		,f.tipo_persona
		,if(f.tipo_persona=1, 'Física', 'Moral') as nom_tipo_persona
		,f.nombre_fisc
		,f.nombre
		,f.ap_paterno
		,f.ap_materno
		,s.correo_e
		,f.calle
		,f.id_ciudad
		,c.nom_ciudad
		,f.id_municipio
		,m.nom_municipio
		,f.id_edo
		,e.nom_edo
		,e.siglas_edo
		,f.id_cp
		,f.id_colonia
		,col.nom_colonia
		,f.colonia_otra
		,f.num_int
		,f.num_ext
		,f.fec_registro
		,f.fec_actualizacion
		FROM datos_facturacion f
		INNER JOIN dgire_cat.ct_estados e ON f.id_edo = e.id_edo
		INNER JOIN dgire_cat.ct_municipios m ON f.id_municipio = m.id_municipio
		INNER JOIN dgire_cat.ct_ciudades c ON f.id_ciudad = c.id_ciudad
		INNER JOIN solicitantes_pago s ON f.id_solicitante = s.id_solicitante
		LEFT OUTER JOIN dgire_cat.ct_colonias col ON col.id_colonia = f.id_colonia 
		";
		
		$where = "";
		
		foreach($lstParam as $col => $val){
		
			switch($col){
				
				case "id_solicitante":	$where.=" AND f.id_solicitante = '$val' "; break;
				case "rfc":	$where.=" AND f.rfc = '$val' "; break;
				case "tipo_persona":	$where.=" AND f.tipo_persona = '$val' "; break;
				case "nombre_fisc":	$where.=" AND f.nombre_fisc = '$val' "; break;
				case "nombre":	$where.=" AND f.nombre = '$val' "; break;
				case "fnombre":	$where.=" AND concat(f.nombre, ' ', f.ap_paterno, ' ', f.ap_materno) like '%$val%' "; break;
				case "ap_paterno":	$where.=" AND f.ap_paterno = '$val' "; break;
				case "ap_materno":	$where.=" AND f.ap_materno = '$val' "; break;
				case "correo_e":	$where.=" AND s.correo_e = '$val' "; break;
				case "fcorreo_e":	$where.=" AND s.correo_e like '%$val%' "; break;
				case "calle":	$where.=" AND f.calle = '$val' "; break;
				case "id_ciudad":	$where.=" AND f.id_ciudad = $val "; break;
				case "id_municipio":	$where.=" AND f.id_municipio = $val "; break;
				case "id_edo":	$where.=" AND f.id_edo = $val "; break;
				case "id_cp":	$where.=" AND f.id_cp = $val "; break;
				case "id_colonia":	$where.=" AND f.id_colonia = $val "; break;
				case "colonia_otra":	$where.=" AND f.colonia_otra = '$val' "; break;
				case "num_int":	$where.=" AND f.num_int = '$val' "; break;
				case "num_ext":	$where.=" AND f.num_ext = '$val' "; break;
				case "fec_registro":	$where.=" AND f.fec_registro = '$val' "; break;
				case "fec_actualizacion":	$where.=" AND f.fec_actualizacion = '$val' "; break;
				//case "nombreTot":	$where.=" AND nombreTot = '$val' "; break;
				//case "correo_e":	$where.=" AND correo_e = '$val' "; break;
				//case "telefono":	$where.=" AND telefono = '$val' "; break;
				//case "celular":	$where.=" AND celular = '$val' "; break;
				//case "nombreTotFiscal":	$where.=" AND nombreTotFiscal = '$val' "; break;
			}
		}
		
		$query = $where == "" ? $query : $query . " WHERE " . substr($where, 4);
			
		
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
			return (object)array("count" => $num, "rfcs" => array());
		}
		
		$j=0;
		$lst = array();
		while ($obj = $resp->fetch_object()){
			
			$lst[] = $obj;
			$j++;
		}
		
		$resp->close();
		return (object)array("count" => $j, "rfc" => $lst);
	}
	
	

}

	
?>
