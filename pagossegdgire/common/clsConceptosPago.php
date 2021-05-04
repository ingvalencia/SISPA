<?php

require_once ("config.php");
require_once ("clsCatalogos.php"); 
	
class clsConceptosPago extends clsCatalogos{

	public function __construct($linkMysql=NULL){

		parent::__construct();

		return $this->connect($linkMysql);
	}	
	
	public function getMontosConcepto($id_concepto_pago,$cantidad,$smdf,$calcula_iva,$iva,$num_smdf,$precio_unitarioT,$costo_variable,$precio_variable){
	
	
		if($precio_variable <= 0){
			$precio_variable = 1;
			
		}
		
		$monto_iva = 0;
		$iva_unitario = 0;
		$iva_total = 0;
		
		$precio_unitario = $precio_unitarioT;
		
		if($num_smdf!=0){
			 $precio_unitario = ($num_smdf)*$smdf;
		}
		
		if($costo_variable==1){
			$precio_unitario = $precio_variable;
		}
		
		if($calcula_iva!=0){

			$iva_unitario = $precio_unitario*$iva;
			$importe =$precio_unitario+$iva_unitario;
			$monto_tot_conc = $importe*$cantidad;

		}
		
		$iva_total=($iva_unitario*$cantidad);
		$iva=$iva_unitario;
		$importe =($precio_unitario+$iva_unitario);
		$monto_tot_conc = ($importe*$cantidad);
		
		$resp = array(
			"importe" => number_format($importe, 2, '.', '')
			,"cantidad" => $cantidad
			,"importe_sin_iva" => number_format($precio_unitario*$cantidad, 2, '.', '')
			,"iva_total" => number_format($iva_total, 2, '.', '')
			,"iva" => number_format($iva, 2, '.', '')
			,"monto_tot_conc" => number_format($monto_tot_conc, 2, '.', '')
			,"monto_iva" => number_format($monto_iva, 2, '.', '')
			,"importe_unitario" => $precio_unitario
			);
		
		return (object)$resp;
		
	}
	
	
	public function getConcepto($id_concepto_pago){
			
		$resp = $this->search_conceptos(array("id_concepto_pago" => $id_concepto_pago));
		
		if(!$resp){
			return $resp;
		}
		
		if($resp->count==0){
			return (object)array("count" => 0, "concepto" => NULL);
		}
	
		if(!$resp->count){
			return (object)array("count" => 0, "concepto" => NULL);
		}
		
		return (object)array("count" => 1, "concepto" => $resp->conceptos[0]);
		
	}

	
	public function addConcepto($lstParam = array()){
		
		$c = (object)$lstParam;
		
		$query = "".
		" INSERT INTO ct_concepto_pago (id_concepto_pago, id_area, cve_tipo_con, nom_concepto_pago, importe_pesos, ".
		" importe_smdf, calcula_iva, cuenta, vigente, costo_variable, fec_actualizacion) VALUES ( ".
		" '$c->id_concepto_pago', $c->id_area, '$c->cve_tipo_con', '$c->nom_concepto_pago', $c->precio_unitario, ".
		" $c->importe_smdf, $c->calcula_iva, $c->cuenta, $c->vigente, $c->costo_variable, NOW())";
		
		//die($query);
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		return true;
		
	}
	
	
	public function updConcepto($id_concepto_pago, $lstParam = array()){
		
		$c = (object)$lstParam;
		
		$query = " UPDATE ct_concepto_pago SET fec_actualizacion = NOW() ";
		
		foreach($lstParam as $col => $val){
			switch($col){
				case "id_area": $query.= ", id_area = $val "; break;
				case "cve_tipo_con": $query.= ", cve_tipo_con = '$val' "; break;
				case "nom_concepto_pago": $query.= ", nom_concepto_pago = '$val' "; break;
				case "importe_pesos": $query.= ", importe_pesos = $val "; break;
				case "importe_smdf": $query.= ", importe_smdf = $val "; break;
				case "calcula_iva": $query.= ", calcula_iva = $val "; break;
				case "cuenta": $query.= ", cuenta = $val "; break;
				case "vigente": $query.= ", vigente = $val "; break;
				case "costo_variable": $query.= ", costo_variable = $val "; break;
			}
		}
		
		$query .= " WHERE id_concepto_pago = '$id_concepto_pago'";
		
		//die($query);
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		return true;
		
	}

	public function search_conceptos($lstParam = array()){

		$query = "
		SELECT 
c.id_concepto_pago
, c.id_area
, a.id_subdireccion
, a.nom_area, s.nom_sub
, c.cve_tipo_con
, t.nombre
, c.nom_concepto_pago
, c.importe_pesos
, c.importe_smdf
, c.calcula_iva
, (case 
	when(c.calcula_iva = 0) then 'Tasa 0' 
	when(c.calcula_iva = 1) then 'Si' 
	when(c.calcula_iva = 2) then 'Exento' 
	end) AS calcula_iva_text
, c.cuenta
, c.costo_variable
, (case 
	when(c.costo_variable = 0) then 'No' 
	when(c.costo_variable = 1) then 'Si' 
	end) AS costo_variable_text
, c.fec_actualizacion
, c.vigente
, (case 
	when(c.vigente = 0) then 'No' 
	when(c.vigente = 1) then 'Si' 
	end) AS vigente_text 
FROM ct_concepto_pago c
, ct_area a
, ct_subdireccion s
, ct_tipo_conceptos t 
WHERE c.id_area = a.id_area 
AND a.id_subdireccion = s.id_subdireccion 
AND c.cve_tipo_con = t.cve_tipo_con 
";
		
		//print_r($lstParam); exit;
		
		$where = "";
		foreach($lstParam as $campo => $valor){
		
			switch($campo){
				case "id_area": $where.= " AND c.id_area = $valor "; break;
				case "id_concepto_pago": $where.= " AND c.id_concepto_pago = '$valor' "; break;
				case "nom_area": $where.= " AND a.nom_area = $valor "; break;
				case "nom_concepto_pago": $where.= " AND c.nom_concepto_pago = $valor "; break;
				case "importe_pesos": $where.= " AND c.importe_pesos = $valor "; break;
				case "importe_smdf": $where.= " AND c.importe_smdf = $valor "; break;
				case "cve_tipo_con": $where.= " AND c.cve_tipo_con = $valor "; break;
				case "nombre": $where.= " AND t.nombre = $valor "; break;
				case "calcula_iva": $where.= " AND c.calcula_iva = $valor "; break;
				case "cuenta": $where.= " AND c.cuenta = $valor "; break;
				case "vigente": $where.= " AND c.vigente = $valor "; break;
				case "costo_variable": $where.= " AND c.costo_variable = $valor "; break;
				case "fec_actualizacion": $where.= " AND c.fec_actualizacion = $valor "; break;
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
		else{
			$query .= " ORDER BY c.id_concepto_pago";
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
		$lstGroup = array();
		$lstConceptos = array();
		while ($obj = $resp->fetch_object()){
			
			$lst[] = $obj;
			if(!isset($lstGroup[$obj->id_area])){
				$lstGroup[$obj->id_area]=array();
				$lstGroup[$obj->id_area]["nom_area"] = $obj->nom_area;
				$lstGroup[$obj->id_area]["conceptos"] = array();
			}
			$lstGroup[$obj->id_area]["conceptos"][] = $obj;
			$lstConceptos[$obj->id_concepto_pago] = $obj;
			$j++;
		}
		
		$resp->close();
		return (object)array("count" => $j, "conceptos" => $lst, "lstGrupos" => $lstGroup, "lstConceptos" => $lstConceptos );
	}

	
	public function existClave($clave){
		
		$query = " SELECT id_concepto_pago FROM ct_concepto_pago where id_concepto_pago = '$clave' ";
		
		//die($query);
		
		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}

		$num = $resp->num_rows;
		
		if(!$num){
			return (object)array("exist" => false);
		}
		
		$resp->close();
		return (object)array("exist" => true);
	}
	

}



?>

