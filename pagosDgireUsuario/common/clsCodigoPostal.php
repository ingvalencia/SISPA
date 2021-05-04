<?php

require_once ("config.php");

class clsCodigoPostal extends clsConexion{
    
    public function __construct($linkMysql=NULL){

		parent::__construct();

		$this->connect($linkMysql);
	}
    
    #******************************#
		public function getDatosCP($cp){
		
		
		if($cp!=''){
			$query = "SELECT * FROM dgire_cat.ct_colonias WHERE id_cp=".$cp;
            $resp = $this->executeQueryMysql($query);
			$row = $resp->fetch_object();
            
            
			$result=$this->executeQuery($query);
			$result2=$this->executeQuery($query);

			$row = mysql_fetch_assoc($result);
			
			if(mysql_num_rows($resp) > 0){
				$resp = array('ciudad'=>$this->getCiudad($row));
                //$resp = array('success' => true,'colonia' => $this-> createSelectMysql($result2,'id_colonia', 'nom_colonia'), 'ciudad'=>$this->getCiudad($row), 'municipio'=>$this->getMunicipio($row), 'estado'=>$this->getEstado($row), 'message'=>'<img src="../img/btn/ok.png">');
						
            }else{
				$resp = array('error' => true,'message' => '<img src="../img/false.png">No v&aacute;lido');
			}
		
		}else{
			$resp = array('error' => true,'message' => '<img src="../img/ok.png">');
		}
			
		return $resp;
		}
    #******************************#
    
    #*******#
		
		public function getCiudad($form){
           
    
                $query = "SELECT id_ciudad, nom_ciudad FROM dgire_cat.ct_ciudades WHERE id_ciudad = ".$form['id_ciudad'].
                    " AND id_municipio = ".$form['id_municipio'].
                    " AND id_edo = ".$form['id_edo'];
                $result=$this->executeQuery($query);
            
                return $this-> createSelectMysqlS($result,'id_ciudad', 'nom_ciudad');
		}
		
		#*******#
    
	
	#******************************#
		
		public function imprimeFormularioHtml($folio, $id_solicitante){
	 	
			$query = "SELECT * from solicitud_pago where folio_sol = ".$folio." and id_solicitante = ".$id_solicitante;
			$resp = $this->executeQueryMysql($query);
			$row = $resp->fetch_object();
			
			
			$text_e.='<table border="0">';
			$text_e.='<tr><td align="left">N&uacute;mero de folio : </td><td colspan="4" align="left"><b>'.$folio.'</b></td></tr>';
			$text_e.='<tr><th>Clave</th><th>Concepto</th><th>Cantidad</th><th align="center">Importe</th><th align="center">Monto</th></tr>';
			
			
			$query1 = "SELECT * from vw_conceptos_sol where folio_sol = ".$folio." and id_solicitante = ".$id_solicitante;
			$resp1=$this->executeQueryMysql($query1);
			
			while($row1 = $resp1->fetch_object()){
				$text_e.='<tr><td align="center">'.$row1->id_concepto_pago.'</td><td align="left">'.utf8_encode($row1->nom_concepto_pago).'</td><td align="center">'.$row1->cant_requerida.'</td><td align="right">'.number_format($row1->importe_concepto_pago,2).'</td><td align="right">'.number_format($row1->monto_tot_conc,2).'</td></tr>';
			}
			
			$text_e.='<tr><td align="right" colspan="5">Total : <b> $ '.number_format($row->monto_total,2).'</b></td></tr>';
			
			
			if($row->factura ==1){ $factura='Si'; }else{ $factura='No'; }
			
			
			$text_e.='<tr><td align="left">Requiere factura : </td><td colspan="4" align="left"><b>'.$factura.'</b></td></tr>';
			$text_e.='<tr><td align="left">Fecha de Registro : </td><td colspan="4" align="left"><b>'.$row->fec_sol.'</b><br></td></tr>';
			$text_e.='<tr><td colspan="5" align="left"><b>Se ha adjuntado su ficha de dep&oacute;sito.</b><br></td></tr>';
			$text_e.='<tr><td colspan="5" align="left"><b>-----Este correo es de car&aacute;cter informativo le agradecemos no contestarlo-----</b></td></tr>';	
			
			$text_e.="</table>";
		

		 return $result = $text_e;
			
			
			
		}

		#******************************#

    
    
    
}


?>