<?php

require_once ("config.php");
require_once ("../libs/email/PHPMailerAutoload.php");

class clsCorreo extends clsConexion{
    
    public function __construct($linkMysql=NULL){

		parent::__construct();

		$this->connect($linkMysql);
	}
    
    #******************************#
		public function enviaCorreoSolicitante($contenido, $datEmail,$referencia){
			
		
			$mail = new PHPMailer();
			$mail->IsSMTP();
            
            # Enable SMTP debugging
            # 0 = off (for production use)
            # 1 = client messages
            # 2 = client and server messages
            $mail->SMTPDebug = 0;
            
            #Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
			
            $mail->Host = "132.248.38.11";
			$mail->Port = 25;

			$mail->SMTPSecure = 'tls';
			$mail->SMTPAuth = true;

			$mail->Username = "sistemas";
			$mail->Password = "Z186hMCe";
	
			$mail->From     = $datEmail['from'];
			$mail->FromName = $datEmail['fromName'];

		
			$mail->CharSet = "utf-8";
            $mail->Encoding = "quoted-printable";
	
			$mail->AddAddress($datEmail['addAddress']);
			
			$mail->AddCC($datEmail['addCC'], $datEmail['addCCName']);
			$mail->AddBCC($datEmail['addBcc'], $datEmail['addBccName']);
						
			$mail->Subject = $datEmail['subject'];

			//$mail->Body    = $contenido;
			$mail->AltBody = $contenido;
			$mail->MsgHTML($contenido);
			
			//$mail->addAttachment = adjunta el archivo
			$mail->addAttachment( "../fichas/doc/$referencia.pdf" );
			//die($referencia);
			//$mail->addAttachment( "../fichas/doc/5510117000688HX70210.pdf" );
			
		
        	// si todos los campos fueron completados enviamos el mail
            $valEmail=$mail->send();
            
            #send the message, check for errors
            if (!$valEmail) {
                $resp = $mail->ErrorInfo;
            } else {
                $resp = $valEmail;
                }

            return $resp;	
		}
    #******************************#
    
	
	#******************************#
		
		public function imprimeFormularioHtml($folio, $id_solicitante){
	 	
			$query = "SELECT * from solicitud_pago where folio_sol = ".$folio." and id_solicitante = ".$id_solicitante;
			$resp = $this->executeQueryMysql($query);
			$row = $resp->fetch_object();
			
			
			$text_e = '';
			
			$text_e.='<table align="center" border="0" cellpadding="0" cellspacing="0" width="600">';
			$text_e.='<tbody>';
			$text_e.='<tr valign="top">';
			$text_e.='<td width="100%">';
			$text_e.='<table align="center" border="0" cellpadding="0" cellspacing="0" style="color:#333333!important;font-family:arial,helvetica,sans-serif;font-size:12px" width="600">';
			$text_e.='<tbody>';
			$text_e.='<tr valign="top">';
			$text_e.='<td>';
			$text_e.='<td valign="middle" align="right">N&uacute;mero de folio : <b>'.$folio.'</b><br>Fecha de Registro : <b>'.$row->fec_sol.'</b></br>';
			$text_e.='<div style="margin-top:5px;clear:both">';
			$text_e.='<table align="center" border="0" cellpadding="0" cellspacing="0" style="clear:both;color:#666666!important;font-family:arial,helvetica,sans-serif;font-size:11px" width="100%">';
			$text_e.='<tbody>';
			$text_e.='<tr>';
			$text_e.='<td style="border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important" width="350" align="center">Clave</td>';
			$text_e.='<td style="border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important" width="100" align="center">Concepto</td>';
			$text_e.='<td style="border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important" width="50" align="center">Cantidad</td>';
			$text_e.='<td style="border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important" width="80" align="center">Importe</td>';
			$text_e.='<td style="border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important" width="80" align="center">Monto</td>';
			$text_e.='</tr>';
			$text_e.='<tr>';
			
			$query1 = "SELECT * from vw_conceptos_sol where folio_sol = ".$folio." and id_solicitante = ".$id_solicitante;
			$resp1=$this->executeQueryMysql($query1);
			
			while($row1 = $resp1->fetch_object()){
				$text_e.='<tr><td align="center">'.$row1->id_concepto_pago.'</td><td align="left">'.($row1->nom_concepto_pago).'</td><td align="center">'.$row1->cant_requerida.'</td><td align="right">'.number_format($row1->importe_concepto_pago,2).'</td><td align="right">'.number_format($row1->monto_tot_conc,2).'</td></tr>';
			}
			$text_e.='</tr>';
			$text_e.='</tbody>';
			$text_e.='</table>';
			
			$text_e.='<table align="left" border="0" cellpadding="0" cellspacing="0" style="border-top:1px solid #ccc;border-bottom:1px solid #ccc;clear:both;color:#666666!important;font-family:arial,helvetica,sans-serif;font-size:11px" width="595">
						<tbody>
							<tr>
								<td>
									<table border="0" cellpadding="0" cellspacing="0" style="color:#666666!important;font-family:arial,helvetica,sans-serif;font-size:11px;margin-top:20px;clear:both;width:100%" align="right">
										<tbody>
											
											<tr>
												<td style="width:390px;text-align:right;padding:0 10px 0 0"><span style="color:#333333!important;font-weight:bold">Total</span></td>
												<td style="width:90px;text-align:right;padding:0 5px 0 0"><b> $ '.number_format($row->monto_total,2).'</b></td>
											</tr>
                        
											<tr>
												<td style="color:#757575;padding-bottom:20px;padding-left:10px">Se ha adjuntado su ficha de dep&oacute;sito.<br></td>
												<td style="width:90px;text-align:right;padding:20px 5px 0 0"></td>
											</tr>
											
											<tr>
												<td style="color:#757575;padding-bottom:20px;padding-left:10px"><b>-----Este correo es de car&aacute;cter informativo le agradecemos no contestarlo-----</b><br></td>
												<td style="width:90px;text-align:right;padding:20px 5px 0 0"></td>
											</tr>
                        
											<tr>
												<td></td>
											</tr>
											
										</tbody>
									</table>
								</td>
							</tr>
					</tbody>
				</table>';
			
			$text_e.='</div>';
			$text_e.='</td>';
			$text_e.='</td>';
			$text_e.='</tr>';
			$text_e.='</tbody>';
			$text_e.='</table>';
			$text_e.='</td>';
			$text_e.='</tr>';
			$text_e.='</tbody>';
			$text_e.="</table>";
			
		 return $result = $text_e;
			
			
			
		}

		#******************************#
		
		
		
		#******************************#
		
		public function imprimeFormularioHtml2($lstParam){
	 	
			$p = (object)$lstParam;
			$folio = $p->folio;
			$fec_sol = $p->fec_sol;
			$monto_total = $p->monto_total;
			$conceptos = $p->conceptos;
				
			$lst_conceptos = "";
			
			foreach($conceptos as $id => $c){
				$lst_conceptos .= "
					<tr>
						<td align='center'>$c->id_concepto_pago</td>
						<td align='left'>$c->nom_concepto_pago</td>
						<td align='center'>$c->cantidad</td>
						<td align='right'>$".number_format($c->importe,2)."</td>
						<td align='right'>$".number_format($c->monto_tot_conc,2)."</td>
					</tr>
				";
			}
			
			
			$footer = "
			<table border='0' cellpadding='0' cellspacing='0' style='color:#666666!important;font-family:arial,helvetica,sans-serif;font-size:11px;margin-top:20px;clear:both;width:100%' align='right'>
				<tr>
					<td style='width:390px;text-align:right;padding:0 10px 0 0'><span style='color:#333333!important;font-weight:bold'>Total</span></td>
					<td style='width:90px;text-align:right;padding:0 5px 0 0'><b>$ $monto_total</b></td>
				</tr>
				
				<tr>
					<td style='color:#757575;padding-bottom:20px;padding-left:10px'>Se ha adjuntado su ficha de dep&oacute;sito.<br></td>
					<td style='width:90px;text-align:right;padding:20px 5px 0 0'></td>
				</tr>
				
				<tr>
					<td style='color:#757575;padding-bottom:20px;padding-left:10px'><b>-----Este correo es de car&aacute;cter informativo le agradecemos no contestarlo-----</b><br></td>
					<td style='width:90px;text-align:right;padding:20px 5px 0 0'></td>
				</tr>
		
			</table>
			";
			
			$contenido = "
			<table align='center' border='0' cellpadding='0' cellspacing='0' style='color:#333333!important;font-family:arial,helvetica,sans-serif;font-size:12px' width='600'>
				<tr valign='top'>
					<td valign='middle' align='right'>N&uacute;mero de folio : <b>$folio</b><br>Fecha de Registro : <b>$fec_sol</b></br>
						<div style='margin-top:5px;clear:both'>
							<table align='center' border='0' cellpadding='0' cellspacing='0' style='clear:both;color:#666666!important;font-family:arial,helvetica,sans-serif;font-size:11px' width='100%'>
								<tr>
									<td style='border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important' width='350' align='center'>Clave</td>
									<td style='border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important' width='100' align='center'>Concepto</td>
									<td style='border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important' width='50' align='center'>Cantidad</td>
									<td style='border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important' width='80' align='center'>Importe</td>
									<td style='border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important' width='80' align='center'>Monto</td>
								</tr>
								$lst_conceptos
							</table>
							<table align='left' border='0' cellpadding='0' cellspacing='0' style='border-top:1px solid #ccc;border-bottom:1px solid #ccc;clear:both;color:#666666!important;font-family:arial,helvetica,sans-serif;font-size:11px' width='595'>
								<tr>
									<td>
										$footer
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
			";
			
		return $result = $contenido;
			
			
			
		}

		#******************************#
		
		#******************************#
		public function enviaCorreoSolicitanteNuevo($contenido,$datEmail){
			
		
			$mail = new PHPMailer();
			$mail->IsSMTP();
            
            # Enable SMTP debugging
            # 0 = off (for production use)
            # 1 = client messages
            # 2 = client and server messages
            $mail->SMTPDebug = 0;
            
            #Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
			
            $mail->Host = "132.248.38.11";
			$mail->Port = 25;

			$mail->SMTPSecure = 'tls';
			$mail->SMTPAuth = true;

			$mail->Username = "sistemas";
			$mail->Password = "Z186hMCe";
	
			$mail->From     = $datEmail['from'];
			$mail->FromName = $datEmail['fromName'];

		
			$mail->CharSet = "utf-8";
            $mail->Encoding = "quoted-printable";
	
			$mail->AddAddress($datEmail['addAddress']);
			
			$mail->AddCC($datEmail['addCC'], $datEmail['addCCName']);
			$mail->AddBCC($datEmail['addBcc'], $datEmail['addBccName']);
						
			$mail->Subject = $datEmail['subject'];

			//$mail->Body    = $contenido;
			$mail->AltBody = $contenido;
			$mail->MsgHTML($contenido);
			
			
        	// si todos los campos fueron completados enviamos el mail
            $valEmail=$mail->send();
            
            #send the message, check for errors
            if (!$valEmail) {
                $resp = $mail->ErrorInfo;
            } else {
                $resp = $valEmail;
                }

            return $resp;	
		}
		
		#******************************#
		
		#******************************#
		public function imprimeFormulariobodyHTML($nomTotal,$url_val){
			
			$text_e = '';
			
			$text_e.='<html>';
			$text_e.='<title>DGIRE - Cuenta Registrada </title>';
			$text_e.='<head>';
			$text_e.='<style>';
			$text_e.='.text{ font-family:Helvetica; font-size:11px; }';
			$text_e.='</style>';
			$text_e.='</head>';
			$text_e.='<body>';
		
	
			$text_e.='<p class="text">Estimado usuario </p>';
			$text_e.='<p class="text">El sistema de pagos de la DGIRE ha creado una cuenta a nombre de <b>'.$nomTotal.'</b></p>';

			$text_e.='<p class="text">Por motivos de seguridad la cuenta se encuentra desactivada hasta que visite el siguiente enlace:<br/></p>';
			
			$text_e.='<a class="text" href="'.$url_val.'" class="text">'.$url_val.'<br/></a>';
			
			$text_e.='<p class="text">Este es un correo de car&aacute;cter informativo, favor de no responderlo.</p>';
			
			$text_e.='</body>';
			$text_e.='</html>';

		 return $result = $text_e;
			
			
			
		}
		#******************************#
		
		#******************************#
		public function imprimeFormularioRecovery($url_val){
	 	
			$text_e.='<html>';
			$text_e.='<title>DGIRE - Recuperar contrase&ntilde;a</title>';
			$text_e.='<head>';
			$text_e.='<style>';
			$text_e.='.text{ font-family:Helvetica; font-size:12px; }';
			$text_e.='</style>';
			$text_e.='</head>';
			$text_e.='<body>';
			$text_e.='<p class="text">Estimado usuario </p>';
			$text_e.='<p class="text">Ha solicitado recuperar su contrase&ntilde;a, para continuar deber&aacute; dar click en el siguiente enlace y seguir las instrucciones que se le indican.</b></p>';
			$text_e.='<a class="text" href="'.$url_val.'" class="text">'.$url_val.'<br/></a>';
			$text_e.='<p class="text">Si usted no solicit&oacute; la recuperaci&oacute;n del password por favor omita este correo.<br/></p>';
			$text_e.='<p class="text">Este es un correo de car&aacute;cter informativo, favor de no responderlo.</p>';
			$text_e.='</body>';
			$text_e.='</html>';

		 return $result = $text_e;
			
		}
		#******************************#
		
		
		
		

    
    
    
}


?>
