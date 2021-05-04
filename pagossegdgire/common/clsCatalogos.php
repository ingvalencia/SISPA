<?php

require_once ("config.php");
require_once ("clsConexion.php"); 
	
class clsCatalogos extends clsConexion{

	public function __construct($linkMysql=NULL){

		parent::__construct();

		$this->connect($linkMysql);
	}
	
	
	public function getEstadoPago(){
		
		$query = " SELECT cve_edo_pago, nom_edo_pago, descripcion FROM ct_estado_pago ";
		
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
		return (object)array("count" => $j, "estados" => $lst);
		
	}
	
	public function getEstadoSolicitud(){
		
		$query = " SELECT cve_edo_sol, nom_edo_sol, descripcion FROM ct_estado_sol ";
		
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
		return (object)array("count" => $j, "estados" => $lst);
		
	}
	
	public function getAreas(){
		
		$query = " SELECT id_area, id_subdireccion, nom_area, vigente FROM ct_area ORDER BY nom_area";
		
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
		return (object)array("count" => $j, "areas" => $lst);
	}
	
	public function getConceptosPago(){
		
		$query = " SELECT id_concepto_pago, nom_concepto_pago FROM ct_concepto_pago order by id_concepto_pago ";
		
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
		return (object)array("count" => $j, "conceptos" => $lst);
	}
	
	public function getTiposConceptos(){
		
		$query = " SELECT cve_tipo_con, nombre FROM ct_tipo_conceptos order by cve_tipo_con ";
		
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
		return (object)array("count" => $j, "tipos_conceptos" => $lst);
	}
	
	public function getPerfil(){
		
		$query = " SELECT id_perfil, nom_perfil, vigente FROM ct_perfil ";
		
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
		return (object)array("count" => $j, "perfiles" => $lst);
		
	}
	
	public function getRoles(){
		
		$query = " SELECT id_rol, nom_rol, descripcion, vigente FROM ct_rol ORDER BY nom_rol ";
		
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
		return (object)array("count" => $j, "roles" => $lst);
		
	}
	
	
	public function getSubdirecciones(){
		
		$query = " SELECT id_subdireccion, nom_sub, vigente FROM ct_subdireccion ";
		
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
		return (object)array("count" => $j, "subdirecciones" => $lst);
		
	}
	
	public function getColonia($id_cp){
		
		$query = "
		SELECT c.id_cp, c.id_colonia, c.nom_colonia, c.id_ciudad, city.nom_ciudad, 
		c.id_municipio, m.nom_municipio, c.id_edo, e.nom_edo 
		FROM dgire_cat.ct_colonias c, dgire_cat.ct_ciudades city,
		dgire_cat.ct_municipios m, dgire_cat.ct_estados e
		WHERE c.vigente = 1 AND c.id_ciudad = city.id_ciudad 
		AND c.id_municipio = m.id_municipio AND c.id_edo = e.id_edo AND c.id_cp = '$id_cp' ";
		
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
		return (object)array("count" => $j, "colonias" => $lst);
		
	}
	
}

	
?>
