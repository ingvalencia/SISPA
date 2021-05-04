<?php

include_once ("clsConexion.php"); 
require_once ("config.php");

class acceso extends DBCon{

	public function __construct($linkMysql =NULL, $linkSybase =NULL, $linkSybaseW =NULL ){

		parent::__construct();

		$this->conect($linkMysql, $linkSybase, $linkSybaseW);
	}
	
	
	public function aperturaSistema(){
		global $CONFIG;
		$this->switchMysqlDB($CONFIG['db_conn']['db_sistema']);
		
		$query = "SELECT valor as fech_apertura  FROM parametros_grales where cve_parametro='apertura_sistema'";
		$rest = $this->executeQueryMysql($query);
		$row = mysql_fetch_array($rest);

		$query2 = "SELECT valor as fech_cierre FROM parametros_grales where cve_parametro='cierre_sistema'";
		$rest2 = $this->executeQueryMysql($query2);
		$row2 = mysql_fetch_array($rest2);
		#echo 'S:'.$fech_sistema=date("Y-m-d H:i").'<br>A:'.$row['fech_apertura'].'<br>C:'.$row2['fech_cierre'].'<br>';

		$fecha_sistema = strtotime(date("d-m-Y H:i",time()));
		$fecha_apertura = strtotime($row['fech_apertura']);
		$fecha_cierre = strtotime($row2['fech_cierre']);
		
			if($fecha_sistema > $fecha_apertura and $fecha_sistema < $fecha_cierre){
				$resp = array('accede' =>true);
			}else{
				if($fecha_sistema > $fecha_apertura and $fecha_sistema > $fecha_cierre){
					$resp = array('accede' =>true);
				}else{
					if($fecha_sistema < $fecha_apertura and $fecha_sistema < $fecha_cierre){
						$resp = array('accede' =>true);
					}else{
						$resp = array('accede' =>false);
					}
				}
			}

	return $resp;
	}
	
	
	public function mensajeSuspende(){
		global $CONFIG;
		$this->switchMysqlDB($CONFIG['db_conn']['db_sistema']);

		$query = "SELECT valor as fech_apertura FROM parametros_grales where cve_parametro='apertura_sistema'";
		$rest = $this->executeQueryMysql($query);
		$row = mysql_fetch_array($rest);

		$query2 = "SELECT valor as fech_cierre, descripcion FROM parametros_grales where cve_parametro='cierre_sistema'";
		$rest2 = $this->executeQueryMysql($query2);
		$row2 = mysql_fetch_array($rest2);

		$fecha_sistema = strtotime(date("d-m-Y H:i",time()));
		$fecha_apertura = strtotime($row['fech_apertura']);
		$fecha_cierre = strtotime($row2['fech_cierre']);

		$segundos=$fecha_cierre - $fecha_sistema;
		$diferencia_dias=intval($segundos/60/60/24);
		
		$query3 = "SELECT descripcion,valor FROM parametros_grales where cve_parametro='mensaje_suspende'";
		$rest3 = $this->executeQueryMysql($query3);
		$row3 = mysql_fetch_array($rest3);
			
		if(abs($diferencia_dias) >= 0 and $diferencia_dias <= $row3['valor'] and $fecha_sistema < $fecha_apertura){
			
			$mensaje='<div style="font-family:verdana; font-size:14px; border-radius:10px; border:5px solid #EE872A; text-align:left; padding-left:10px; padding-right:10px; padding-bottom:15px;" id="elemento">';
			$mensaje.=$row3['descripcion'];
			$mensaje.='</div>';
		
			$resp = array('success' => true,'message'=>$mensaje);
		}else{
			$resp = array('error' => true, 'success' => false);
		}
		
	return $resp;
	}


}

	
?>
