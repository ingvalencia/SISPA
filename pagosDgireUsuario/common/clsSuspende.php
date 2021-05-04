<?php

require_once ("config.php");
require_once ("clsConexion.php"); 


class clsSuspende extends clsConexion{
    
    public function __construct($linkMysql=NULL){

		parent::__construct();

		$this->connect($linkMysql);
	}
    
    #******************************#
    public function mensajeSuspende(){
		

		$query3 = "SELECT descripcion,valor FROM parametros_grales where cve_parametro='mensaje_suspende'";
		$rest3 = $this->executeQueryMysql($query3);
        $row3 = $rest3->fetch_object();
		
        $mensaje='
                    <div class="panel panel-default" id="parp">
                        <div class="panel-body" style="font-family:verdana;font-size:14px;border-radius:10px;border:5px solid #043c6d;text-align:left; padding-left:20px;padding-right:20px;padding-bottom:15px;">
                 ';
        
        $mensaje.=$row3->descripcion;
        
    	$mensaje.='
                        </div>
                    </div>           
                
                ';
        
		/**************/
		
		$resp='';
		
		$query = "SELECT valor as fech_apertura FROM parametros_grales where cve_parametro='apertura_sistema'";
		$rest = $this->executeQueryMysql($query);
        $row = $rest->fetch_object();

		$query2 = "SELECT valor as fech_cierre, descripcion FROM parametros_grales where cve_parametro='cierre_sistema'";
		$rest2 = $this->executeQueryMysql($query2);
        $row2 = $rest2->fetch_object();

		$fecha_sistema = strtotime(date("d-m-Y H:i",time()));
		$fecha_apertura = strtotime($row->fech_apertura);
		$fecha_cierre = strtotime($row2->fech_cierre);

    
		$segundos=$fecha_cierre - $fecha_sistema;
		$diferencia_dias=intval($segundos/60/60/24);
		
		if(abs($diferencia_dias) >= 0 and $diferencia_dias <= $row3->valor and $fecha_sistema < $fecha_apertura){
			$resp = array('success' => true,'message'=>$mensaje);
		}
		
		return $resp;
		}
    
    #******************************#
    
    #******************************#
    public function aperturaSistema(){
		

		$query = "SELECT valor as fech_apertura  FROM parametros_grales where cve_parametro='apertura_sistema'";
		$rest = $this->executeQueryMysql($query);
		$row = $rest->fetch_object();
        
		/**************/
		$query2 = "SELECT valor as fech_cierre FROM parametros_grales where cve_parametro='cierre_sistema'";
		$rest2 = $this->executeQueryMysql($query2);
        $row2 = $rest2->fetch_object();
		#echo 'S:'.$fech_sistema=date("Y-m-d H:i").'<br>A:'.$row['fech_apertura'].'<br>C:'.$row2['fech_cierre'].'<br>';

		$fecha_sistema = strtotime(date("d-m-Y H:i",time()));
		$fecha_apertura = strtotime($row->fech_apertura);
		$fecha_cierre = strtotime($row2->fech_cierre);

			if(($fecha_sistema <= $fecha_apertura and $fecha_sistema <= $fecha_cierre and $fecha_sistema != $fecha_cierre) or ($fecha_sistema >= $fecha_apertura)){
				$accede=1;
			}
	
			if($fecha_sistema <= $fecha_apertura and $fecha_sistema >= $fecha_cierre and $fecha_sistema != $fecha_apertura){
				$accede=0;
			}

		return $accede;
		}
    #******************************#
    
 
}


?>

