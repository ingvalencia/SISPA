<?php

require_once ("config.php");
require_once ("clsDatosFacturacion.php");
require_once ("clsCorreo.php");
require_once ("clsConexion.php");
	
class clsSolicitantes extends clsDatosFacturacion{

	public function __construct($linkMysql=NULL){

		parent::__construct();

		return $this->connect($linkMysql);
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
			,md5('$s->passwd')
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
	
	/*############################################################*/
	
	public function send_activation($correo_e, $cadena_valida){
		
			
			$query = "SELECT * FROM solicitantes_pago where correo_e='".$correo_e."'";
			$result=$this->executeQueryMysql($query);
			$row=mysqli_fetch_row($result);

			//print_r($row);exit;

			if($row[14] == 0){

                $query = "SELECT count(*) FROM solicitantes_pago where correo_e='".$correo_e."' and cadena_valida='".$cadena_valida."'";
				$result=$this->executeQueryMysql($query);
				$row=mysqli_fetch_row($result);

				if($row[0] == 1){
					
					$fieldsSolicitante = array( "vigente" => 1, "cadena_valida" => "{$cadena_valida}");
					$fieldsSolicitanteCond = array("correo_e ='$correo_e'" , "cadena_valida = '$cadena_valida'");
					$act_solicitante=$this->genericUpdate('solicitantes_pago', $fieldsSolicitante, $fieldsSolicitanteCond);

					if($act_solicitante){
						
						$resp = array('success' => true, 'message' => "<p>La cuenta para ingresar al sistema de pagos de la DGIRE ha sido activada con &eacute;xito. Ya puede acceder al sistema.</p>");
						
					}else {#Fin de la insersion de la solicitud
						$resp = array('error' => true, 'message' => '<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>Error al activar la cuenta intentelo nuevamente.');
					}

				}else{
					$resp = array('error' => true,'message' => '<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>La cuenta no puede ser activada.');
				}
			
			}else{
					$resp = array('error' => true,'message' => '<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>La cuenta ya ha sido activada con anterioridad.');
				}

		return $resp;
		
	}
		
	/*############################################################*/
	
	
	/*############################################################*/
	
	public function checkCorreoSolRegistrado($correo_e){

		$query = " SELECT COUNT(id_solicitante) as cont FROM solicitantes_pago WHERE correo_e = '$correo_e'";

		$resp = $this->executeQueryMysql($query);
		
		if(!$resp){
			return false;
		}
		
		$obj = $resp->fetch_object();
		
		$flag=true;
		if($obj->cont){
			$flag=false;
		}
		
		return (object)array("exist" => $flag);
			
	}
	
	/*############################################################*/
	
	
	/*############################################################*/
		public function recoveryPassword($correo_e,$baseUrlUsuario){
			
			$query = " SELECT * FROM solicitantes_pago where correo_e = '$correo_e'";
			$resp = $this->executeQueryMysql($query);
			$row = $resp->fetch_object();

            $url_val = $baseUrlUsuario."recovery_pwd/indexRecovery.php?task=recoveryPwd&"."email={$row->correo_e}&cadena_val={$row->cadena_valida}";
			
			$enviacorreo = new clsCorreo();
		
			$correo_solicitante = $row->correo_e;
			
			$contenido=$enviacorreo->imprimeFormularioRecovery($url_val);

            global $CONFIG;

            $from= $CONFIG->mail->from;

			
			$datEmail=array(
						'addAddress'=>$correo_solicitante
						,'from'=>$from
						,'subject'=>'Recuperar contraseña sistema de pagos DGIRE.'
						,'fromName'=>'Sistemas'
						,'addBcc'=>''
						,'addBccName'=>''
						,'addCC'=>''
						,'addCCName'=>''
						,'$contenido'=>$contenido
						
						);
			
			$resp = $enviacorreo->enviaCorreoSolicitanteNuevo($contenido,$datEmail);
		
	
		
			if(!$resp){
				$json["error"]=true;
				$json["msg"] = "Error #005 Problema enviar correo";
				$solicitantes->rollbackMysql();
				die(json_encode($json));
			}
						
			if(!$resp){
				return false;
			}
			
			return true;
			
	}
	
	/*############################################################*/
	
	
	/*############################################################*/
	public function changePasswordValid($email, $cadena_val){
		

			$query = "SELECT count(*) FROM solicitantes_pago where correo_e='".$email."' and cadena_valida='".$cadena_val."'";
			$resp = $this->executeQueryMysql($query);
			$row=mysqli_fetch_row($resp);
			
			if($row[0] > 0){
				$resp = array('success' => true);
			}else{
				$resp = array('error' => true, 'message' => '<div class="alert alert-danger"><p align="justify" style="margin-left:15px;">La contrase&ntilde;a ya fue actualizada anteriormente. <br> Solicite el cambio de contrase&ntilde;a nuevamente.</p></div>');
			}
		
		return $resp;
	}
	
	/*############################################################*/
	
	
	/*############################################################*/
	public function changePassword($correo_e,$cadena_valida,$passwd){

			$query = "SELECT count(*) FROM solicitantes_pago where correo_e='".$correo_e."' and cadena_valida='".$cadena_valida."'";
			$resp = $this->executeQueryMysql($query);
			$row=mysqli_fetch_row($resp);
			
			if($row[0] != 0){
				
				$cadena_valida1=base_convert(mt_rand(0x1679616, 0x39AA3FF), 12, 36);

				$passMD5 = md5($passwd);
				
				$fieldsSolicitante = array( "passwd" => "{$passMD5}", "cadena_valida" => "{$cadena_valida1}");
				$fieldsSolicitanteCond = array("correo_e = '$correo_e'", "cadena_valida = '$cadena_valida'");
				$act_solicitante=$this->genericUpdate('solicitantes_pago', $fieldsSolicitante, $fieldsSolicitanteCond);

				if($act_solicitante){
							
					$resp ='La contrase&ntilde;a para ingresar al sistema de pagos de la DGIRE ha sido actualizada con &eacute;xito. Ya puede acceder al sistema';
							
				}else {#Fin de la insersion de la solicitud
					$resp ='Error al actualizar el password intentelo nuevamente';
					
				}
				
			}else{
				
				$resp ='El password ya fue actualizado anteriormente solicite el cambio de contrase&ntilde;a nuevamente.';
			
			}
			
		
		return $resp;


	}
	
	
	/*############################################################*/
	

	public function search_solicitantes($lstParam = array()){

		$query = "".
		" SELECT id_solicitante, id_perfil, nombre, ap_paterno, ap_materno, correo_e, telefono, celular, ".
		" passwd, nom_ptl, ptl_ptl, exp_unam, fec_registro, cadena_valida, vigente FROM solicitantes_pago ";
		
		$where = "";
		foreach($lstParam as $campo => $valor){
		
			switch($campo){
				case "id_solicitante":	$where.= " AND $col = '$val' "; break;
				case "id_perfil":		$where.= " AND $col = '$val' "; break;
				case "nombre":			$where.= " AND $col = '$val' "; break;
				case "ap_paterno":		$where.= " AND $col = '$val' "; break;
				case "ap_materno":		$where.= " AND $col = '$val' "; break;
				case "correo_e":		$where.= " AND $col = '$val' "; break;
				case "telefono":		$where.= " AND $col = '$val' "; break;
				case "celular":			$where.= " AND $col = '$val' "; break;
				case "passwd":			$where.= " AND $col = '$val' "; break;
				case "nom_ptl":			$where.= " AND $col = '$val' "; break;
				case "ptl_ptl":			$where.= " AND $col = '$val' "; break;
				case "exp_unam":		$where.= " AND $col = '$val' "; break;
				case "fec_registro":	$where.= " AND $col = '$val' "; break;
				case "cadena_valida":	$where.= " AND $col = '$val' "; break;
				case "vigente":			$where.= " AND $col = '$val' "; break;
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
