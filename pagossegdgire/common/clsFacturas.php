<?php

require_once ("config.php");
require_once ("clsConexion.php"); 
	
class clsFacturas extends clsConexion{

	public function __construct($linkMysql=NULL){

		parent::__construct();

		$this->connect($linkMysql);
	}
	



	public function search_rfc($lstParam = array()){

		$query = "".
		" SELECT id_solicitante, rfc, tipo_persona, nombre_fisc, nombre, ap_paterno, ap_materno, calle, ".
		" id_ciudad, id_municipio, id_edo, id_cp, id_colonia, colonia_otra, num_int, num_ext, fec_registro, ".
		" fec_actualizacion, nom_tipo_persona, nombreTot, correo_e, telefono, celular, nombreTotFiscal ".
		" FROM vw_solicitantes_dat_fac ";
		
		$where = "";
		foreach($lstParam as $col => $val){
		
			switch($col){
				
				case "id_solicitante":	$where.=" AND id_solicitante = '$val' "; break;
				case "rfc":	$where.=" AND rfc = '$val' "; break;
				case "tipo_persona":	$where.=" AND tipo_persona = '$val' "; break;
				case "nombre_fisc":	$where.=" AND nombre_fisc = '$val' "; break;
				case "nombre":	$where.=" AND nombre = '$val' "; break;
				case "ap_paterno":	$where.=" AND ap_paterno = '$val' "; break;
				case "ap_materno":	$where.=" AND ap_materno = '$val' "; break;
				case "calle":	$where.=" AND calle = '$val' "; break;
				case "id_ciudad":	$where.=" AND id_ciudad = $val "; break;
				case "id_municipio":	$where.=" AND id_municipio = $val "; break;
				case "id_edo":	$where.=" AND id_edo = $val "; break;
				case "id_cp":	$where.=" AND id_cp = $val "; break;
				case "id_colonia":	$where.=" AND id_colonia = $val "; break;
				case "colonia_otra":	$where.=" AND colonia_otra = '$val' "; break;
				case "num_int":	$where.=" AND num_int = '$val' "; break;
				case "num_ext":	$where.=" AND num_ext = '$val' "; break;
				case "fec_registro":	$where.=" AND fec_registro = '$val' "; break;
				case "fec_actualizacion":	$where.=" AND fec_actualizacion = '$val' "; break;
				case "nom_tipo_persona":	$where.=" AND nom_tipo_persona = '$val' "; break;
				case "nombreTot":	$where.=" AND nombreTot = '$val' "; break;
				case "correo_e":	$where.=" AND correo_e = '$val' "; break;
				case "telefono":	$where.=" AND telefono = '$val' "; break;
				case "celular":	$where.=" AND celular = '$val' "; break;
				case "nombreTotFiscal":	$where.=" AND nombreTotFiscal = '$val' "; break;
				case "fnombre":	$where.=" AND nombre_fisc LIKE '%$val%' "; break;
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



/*




SELECT 
f.id_solicitante
,f.rfc
,f.tipo_persona
,f.nombre_fisc
,f.nombre
,f.ap_paterno
,f.ap_materno
,f.calle
,f.id_ciudad
,c.nom_ciudad
,f.id_municipio
,m.nom_municipio
,f.id_edo
,e.nom_edo
,f.id_cp
,if(isnull(f.id_colonia),0, f.id_colonia) as id_colonia
,(select 
,f.colonia_otra
,f.num_int
,f.num_ext
,f.fec_registro
,f.fec_actualizacion
FROM dgire_pagos2016A.datos_facturacion f, dgire_cat.ct_estados e 
,dgire_cat.ct_municipios m,  dgire_cat.ct_ciudades c
WHERE f.id_edo = e.id_edo AND f.id_municipio = m.id_municipio 
AND f.id_ciudad = c.id_ciudad 









SELECT f.id_colonia
FROM dgire_pagos2016A.datos_facturacion f WHERE f.id_colonia is null

237

SELECT f.id_colonia
FROM dgire_pagos2016A.datos_facturacion f WHERE f.id_colonia is not null

1844

2081

select 2081-237


SELECT IF(f.id_colonia is null, 0, 1) as id.colonia FROM dgire_pagos2016A.datos_facturacion f

SELECT IF(isnull(f.id_colonia), 0, 1) as id.colonia FROM dgire_pagos2016A.datos_facturacion f


select isnull(null)


SELECT if(isnull(f.id_colonia), 0, f.id_colonia) as id_colonia FROM dgire_pagos2016A.datos_facturacion f



SELECT 
f.id_solicitante
,f.id_colonia
,if(isnull(f.id_colonia), 0, f.id_colonia) as nom_colonia
FROM dgire_pagos2016A.datos_facturacion f, dgire_cat.ct_colonias col
WHERE f.id_colonia = col.id_colonia


select * from dgire_cat.ct_colonias




SELECT 
f.id_solicitante
,f.id_colonia
,if(isnull(f.id_colonia), 0, 
select col.nom_colonia from dgire_cat.ct_colonias col WHERE f.id_colonia = col.id_colonia) as nom_colonia
FROM dgire_pagos2016A.datos_facturacion f



SELECT 
f.id_solicitante
,f.id_colonia
,if(isnull(f.id_colonia), 0, 
(select col.nom_colonia from dgire_cat.ct_colonias col WHERE col.id_colonia = f.id_colonia) as ss ) as nom_colonia
FROM dgire_pagos2016A.datos_facturacion f


select col.nom_colonia from dgire_cat.ct_colonias col WHERE col.id_colonia = 1




SELECT if(isnull(f.id_colonia), 0, f.id_colonia) as id_colonia FROM dgire_pagos2016A.datos_facturacion f











SELECT 
f.id_solicitante
,f.rfc
,f.tipo_persona
,f.nombre_fisc
,f.nombre
,f.ap_paterno
,f.ap_materno
,f.calle
,f.id_ciudad
,f.id_municipio
,m.nom_municipio
,f.id_edo
,e.nom_edo
,e.siglas_edo
,f.id_cp
,f.id_colonia
,c.nom_ciudad
,f.colonia_otra
,col.nom_colonia
,f.num_int
,f.num_ext
,f.fec_registro
,f.fec_actualizacion
FROM dgire_pagos2016A.datos_facturacion f, dgire_cat.ct_estados e, 
dgire_cat.ct_municipios m, dgire_cat.ct_ciudades c, dgire_cat.ct_colonias col
WHERE f.id_edo = e.id_edo AND f.id_municipio = m.id_municipio
AND f.id_ciudad = c.id_ciudad AND f.id_colonia = col.id_colonia











SELECT 
f.id_solicitante
,f.rfc
,f.tipo_persona
,f.nombre_fisc
,f.nombre
,f.ap_paterno
,f.ap_materno
,f.calle
,f.id_ciudad
,f.id_municipio
,m.nom_municipio
,f.id_edo
,e.nom_edo
,e.siglas_edo
,f.id_cp
,f.id_colonia
,c.nom_ciudad
,f.colonia_otra
,col.nom_colonia
,f.num_int
,f.num_ext
,f.fec_registro
,f.fec_actualizacion
FROM dgire_pagos2016A.datos_facturacion f, dgire_cat.ct_estados e, 
dgire_cat.ct_municipios m, dgire_cat.ct_ciudades c, dgire_cat.ct_colonias col
WHERE f.id_edo = e.id_edo AND f.id_municipio = m.id_municipio
AND f.id_ciudad = c.id_ciudad AND f.id_colonia = col.id_colonia










SELECT 
f.id_solicitante
,f.rfc
,f.tipo_persona
,f.nombre_fisc
,f.nombre
,f.ap_paterno
,f.ap_materno
,f.calle
,f.id_ciudad
,f.id_municipio
,m.nom_municipio
,f.id_edo
,e.nom_edo
,e.siglas_edo
,f.id_cp
,f.id_colonia
,c.nom_ciudad
,f.colonia_otra
,col.nom_colonia
,f.num_int
,f.num_ext
,f.fec_registro
,f.fec_actualizacion
FROM dgire_pagos2016A.datos_facturacion f, dgire_cat.ct_estados e, 
dgire_cat.ct_municipios m, dgire_cat.ct_ciudades c, dgire_cat.ct_colonias col
WHERE f.id_edo = e.id_edo AND f.id_municipio = m.id_municipio
AND f.id_ciudad = c.id_ciudad AND f.id_colonia = col.id_colonia








SELECT 
f.id_solicitante
,f.rfc
,f.tipo_persona
,f.nombre_fisc
,f.nombre
,f.ap_paterno
,f.ap_materno
,f.calle
,f.id_ciudad
,f.id_municipio
,m.nom_municipio
,f.id_edo
,e.nom_edo
,e.siglas_edo
,f.id_cp
,f.id_colonia
,c.nom_ciudad
,f.colonia_otra
,col.nom_colonia
,f.num_int
,f.num_ext
,f.fec_registro
,f.fec_actualizacion
FROM dgire_pagos2016A.datos_facturacion f, dgire_cat.ct_estados e, 
dgire_cat.ct_municipios m, dgire_cat.ct_ciudades c, dgire_cat.ct_colonias col
WHERE f.id_edo = e.id_edo AND f.id_municipio = m.id_municipio
AND f.id_ciudad = c.id_ciudad AND f.id_colonia = col.id_colonia


*/
	
	
?>
