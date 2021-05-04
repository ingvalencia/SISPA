<?php

include_once ("clsConexion.php"); 

class clsParametros extends clsConexion{
	
	/*
    * Constructor de la clase
	*
    * @param 		obj			linkMysql		Conexion a mysql
    * 
    */
	public function __construct($linkMysql=NULL, $linkSybase = NULL){

		parent::__construct();

		$this->connect($linkMysql, $linkSybase);
	}
	
	
	
	/**
    * Actualiza los datos de un parametro con cve_parametro = $clave
	*
    * @param 		string			$clave			Clave del parametro a actualizar
    * @param 		array			$lstParam		Lista de parametros a actualizar de la siguiente forma
	*												array("columna1" => valor, "columan2" => valor, ...)
    *
	* @return		object							Regresa un objeto con los siguientes parametros
	*								
	*				bool			error			True si presento un error, false de lo contrario													
	*				string			msg				El mensaje de error que arroja mysql en caso de que exista error	
	}
	*/

	 /*Conexiones a MYSQL*/


	public function update_parametro($clave, $lstParam=NULL){
		
		if($lstParam == NULL){ return (object)array("error" => false); }
		
		if(count($lstParam)==0){ return (object)array("error" => false); }
		
		$query = "UPDATE parametros_grales SET ";
		$set = "";
		
		foreach($lstParam as $col => $val){
			
			if($set!=""){ $set.=", "; }
			
			$set .= $col." = '$val'";
			
		}
		
		$query.=$set." WHERE cve_parametro='$clave' ";
		
		$result = $this->executeQueryMysql($query);
		if(!$result){
			return false;
		}
		
		return true;
	}
	
	public function agregar_parametro($clave, $valor, $desc){
		
		$query = "INSERT INTO parametros_grales (cve_parametro, valor, descripcion) ";
		$query.= " VALUES ('$clave', '$valor', '$desc') ";
		
		$result = $this->executeQueryMysql($query);
		if(!$result){
			return false;
		}
		
		return true;
	}
	
	public function delete_parametro($clave){
		
		$query = "DELETE FROM parametros_grales WHERE cve_parametro = '$clave'";
		
		$result = $this->executeQuery($query);
		if(!$result){
			return false;
		}
		
		return true;
	}
	
	public function search_parametros($lstParam=array()){

		$query  = " SELECT cve_parametro, valor, descripcion FROM parametros_grales WHERE editable = 1 ";

		$cond = "";
		foreach($lstParam as $campo => $valor){
			
			if($campo=="getCount"){ continue; }
				
			if($cond != "" ) { $cond .= " AND "; }
			
			switch($campo){
				case "cve_parametro": $query .= " AND cve_parametro = '$valor' "; break;
			}
		}
		
		$lstParam = (object)$lstParam;
		if(isset($lstParam->sidx)){
			if(($lstParam->sidx != "")&&(($lstParam->sord == "asc")||($lstParam->sord == "desc"))){
				$query .= " ORDER BY ".$lstParam->sidx." ".$lstParam->sord;
			}
		}
		else{
			$query .= " ORDER BY cve_parametro ";
		}

		if((isset($lstParam->limit))&&(isset($lstParam->start))){
			if(((is_numeric($lstParam->start))&&(is_numeric($lstParam->limit)))&&(($lstParam->start>=0)&&($lstParam->limit>=0))){
				$query .= " LIMIT ".$lstParam->start.",".$lstParam->limit;
			}
		}	

		
		$resp = $this->executeQueryMysql($query);
		if(!$resp){
			return false;
		}
		
		if(isset($lstParam->getCount)){
			return (object)array("count" => mysql_num_rows ( $result ), "parametros" => array());
		}
		
		$j=0;
		$lstParam = array();
		
		while ($row = $resp->fetch_object()){
			$lstParam[$row->cve_parametro] = $row;
			$j++;
		}
		
		return (object)array("count" => $j, "parametros" => $lstParam);
	}
	

	function get_terminos_condiciones(){
		
		$query = "SELECT descripcion_aviso FROM Mercurioz_dgirecat.ct_avisos where valor='privacidad'";
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		$row = $resp->fetch_object();
			
		return (object)array("count" => 1, "terminos" => $row->descripcion_aviso);
		
	}


	/*Conexiones a Sybase*/
	
	public function searchPlantelSybase(){

		$query = " SELECT 
		DISTINCT ptl.ptl_ptl 
		,ptl.ptl_ptl 
		,ptl.ptl_nombre 
		FROM 
		unamsi.dbo.planteles ptl 
		WHERE ptl.ptl_vig = 'S' 
		ORDER BY ptl.ptl_nombre
		";

	
		$resp = $this->result = $this->executeQueryUnamsi($query);

		if(!$resp){
			return false;
		}

		$c=0;
		$lstPtl=[];
		while($row = $resp->fetchObject($result)){
			$c++;
			
			$lstPtl[$c] = array(
							"ptl" => $row->ptl_ptl
							,"nombre" => utf8_encode($row->ptl_nombre)
						);
		}

		return (object)array("cont" => $c, "lstPtl" => $lstPtl);

		
	}
	
	
	
}
	
?>
