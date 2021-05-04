<?php

require_once ("clsCorreo.php");

class clsMessage extends clsCorreo{
    
    public function __construct(){
		parent::__construct();
	}
    
	
	
	

	
	#******************************#
		
	public function formulario($solicitud){
		
		$text_detalle = "";
		foreach($solicitud->detalle as $id_concepto_pago => $detalle){
			$text_detalle.="
				<tr>
					<td align='center' class='m_1950253956932142205m_-1841532062777334958bkng-field' style='font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif'>
						$detalle->id_concepto_pago
					</td>
					<td align='left' class='m_1950253956932142205m_-1841532062777334958bkng-field' style='font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif'>
						$detalle->nom_concepto_pago
					</td>
					<td align='center'class='m_1950253956932142205m_-1841532062777334958bkng-field' style='font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif'>
						$detalle->cant_requerida
					</td>
					<td align='center'class='m_1950253956932142205m_-1841532062777334958bkng-field' style='font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif'>
						".number_format($detalle->importe_concepto_pago,2)."
					</td>
					<td align='center'class='m_1950253956932142205m_-1841532062777334958bkng-field' style='font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif'>
						".number_format($row1->monto_tot_conc,2)."
					</td>
				</tr>
			";
		}
			
		$contenido = "
			
			
<table width='100%' border='0' cellspacing='0' cellpadding='0' style='background-color:#0b5ba1'>
    <tbody>
        <tr>
            <td>
                <table width='414' border='0' cellspacing='0' cellpadding='0' align='center' class='m_1950253956932142205m_-1841532062777334958bkng-table' style='padding:1em'>
                    <tbody>
                        <tr>
                        </tr>
                        <tr>
                            <td colspan='4'>
                                <div align='center' class='m_1950253956932142205m_-1841532062777334958bkng-fancy--top' style='background-color:#fff;border-radius:4px 4px 0 0;height:6px;margin:auto;width:360px'></div>
                                <div align='center' class='m_1950253956932142205m_-1841532062777334958bkng-fancy--btm' style='background-color:#fff;border-radius:4px 4px 0 0;height:6px;margin:auto;width:380px'></div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='4' style='background-color:#fff;border-radius:4px 4px 0 0'>
                                <table width='414' border='0' cellspacing='0' cellpadding='0' class='m_1950253956932142205m_-1841532062777334958bkng-table'>
                                    <tbody>
                                        <tr>
                                            <td style='padding:1em 1em .2em 1em'>
                                                <h1 style='font-size:1.4em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:lighter'>Su ficha de depósito</h1>
                                                <hr style='background-color:#3c7cb4;border:0 none;height:4px;margin-left:1em;margin-top:4px;width:70px'>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='padding:0 1em 1em 1em'>
                                                <h2 style='color:#626262;font-size:1.2em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:bold'>Estimado Usuario</h2>
                                                <span style='color:#626262;font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:normal'>Los datos de su solicitud están indicados a continuación.</span> </td>
                                        </tr>
                                        <tr>
                                            <td style='background-color:#f8f8f8;padding:1em'>
                                                <table border='0' cellspacing='0' cellpadding='0' class='m_1950253956932142205m_-1841532062777334958bkng-table'>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <h3 style='font-size:1.2em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:lighter'>
                                                                    Datos de la solicitud de pago</h3>
                                                                <hr style='background-color:#3c7cb4;border:0 none;height:4px;margin-left:1em;margin-top:4px;width:70px'>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <table width='380' border='0' cellspacing='0' cellpadding='0' align='center' class='m_1950253956932142205m_-1841532062777334958bkng-table' style='border:1px solid #e4e4e4;background-color:#fff;border-radius:.2em'>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan='3' style='padding:1em 1em 0 1em'><span class='m_1950253956932142205m_-1841532062777334958bkng-bold' style='font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em'>Número de folio:</span><br>
                                                                                <span class='m_1950253956932142205m_-1841532062777334958bkng-field' style='font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif'>$solicitud->folio</span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan='3' style='padding:0 1em .4em 1em'><span class='m_1950253956932142205m_-1841532062777334958bkng-bold' style='font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em'>Fecha de Registro:</span><br>
                                                                                <span class='m_1950253956932142205m_-1841532062777334958bkng-field' style='font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif'>$solicitud->fec_sol</span>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan='3' style='padding:0 1em .4em 1em'><span class='m_1950253956932142205m_-1841532062777334958bkng-bold' style='font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em'> </span><br>
                                                                                <span class='m_1950253956932142205m_-1841532062777334958bkng-field' style='font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif'></span>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td style='border-right:1px solid #e4e4e4;padding-left:1em'><span class='m_1950253956932142205m_-1841532062777334958bkng-bold' style='font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em'>Clave</span><br>
                                                                            </td>
                                                                            <td style='border-right:1px solid #e4e4e4;padding-left:.6em'><span class='m_1950253956932142205m_-1841532062777334958bkng-bold' style='font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em'>Concepto</span><br>
                                                                            </td>
                                                                            <td style='padding-left:.6em'><span class='m_1950253956932142205m_-1841532062777334958bkng-bold' style='font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em'>Cantidad</span><br>
                                                                            </td>
                                                                            <td style='padding-left:.6em'><span class='m_1950253956932142205m_-1841532062777334958bkng-bold' style='font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em'>Importe</span><br>
                                                                            </td>
                                                                            <td style='padding-left:.6em'><span class='m_1950253956932142205m_-1841532062777334958bkng-bold' style='font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em'>Monto</span><br>
                                                                            </td>

                                                                        </tr>
                                                                        $text_detalle
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan='4' style='background-color:#fff;border-radius:0 0 4px 4px'>
                                <table width='414' border='0' cellspacing='0' cellpadding='0' class='m_1950253956932142205m_-1841532062777334958bkng-table'>
                                    <tbody>
                                        <tr>
                                            <td style='padding:1em'>
                                                <table border='0' cellspacing='0' cellpadding='0' class='m_1950253956932142205m_-1841532062777334958bkng-table' width='380'>
                                                    <tbody>
                                                        <tr>
                                                            <td style='color:#626262;font-size:1.2em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:bold'>
                                                                Total del depósito:</td>
                                                            <td width='110' class='m_1950253956932142205m_-1841532062777334958bkng-row'>&nbsp;</td>
                                                            <td style='color:#626262;font-size:1.2em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:bold'>$".number_format($soliciutd->monto_total,2)."</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan='3' style='text-align:center'>
                                                                <hr style='background-color:#3c7cb4;border:0 none;height:4px;margin-top:4px'>
                                                                <span style='color:#626262;font-size:.7em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;text-align:center'>Se ha adjuntado su ficha de depósito.</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='background-color:#f8f8f8'>
                                                <table border='0' cellspacing='0' cellpadding='0' class='m_1950253956932142205m_-1841532062777334958bkng-table'>
                                                    <tbody>
                                                        <tr>
                                                            <td style='padding:1em'>
                                                                <table border='0' cellspacing='0' cellpadding='0' class='m_1950253956932142205m_-1841532062777334958bkng-table'>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
																				<span style='color:#626262;font-size:0.7em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:normal'><br></span>
																			</td>
                                                                        </tr>
                                                                        <tr>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <h2 style='color:#626262;font-size:1.2em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:bold'>
                                                                                    Notas del Servicio</h2>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><span style='color:#626262;font-size:0.7em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:normal'>-----Este correo es de carácter informativo le agradecemos no contestarlo-----</span>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
			
		";
	 	
		 return $contenido;
			
			
			
		}

		#******************************#
		
		#******************************#
		
		public function imprimeFormularioHtmlTicket($folio, $id_solicitante){
	 	
			
			$query = "SELECT * from solicitud_pago where folio_sol = ".$folio." and id_solicitante = ".$id_solicitante;
			$resp = $this->executeQueryMysql($query);
			$row = $resp->fetch_object();
			
			
			$text_e = '';
			
			$text_e.= '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color:#0b5ba1">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td>';
			$text_e.= '<table width="414" border="0" cellspacing="0" cellpadding="0" align="center" class="m_1950253956932142205m_-1841532062777334958bkng-table" style="padding:1em">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td colspan="4">';
			$text_e.= '<div align="center" class="m_1950253956932142205m_-1841532062777334958bkng-fancy--top" style="background-color:#fff;border-radius:4px 4px 0 0;height:6px;margin:auto;width:360px"></div>';
			$text_e.= '<div align="center" class="m_1950253956932142205m_-1841532062777334958bkng-fancy--btm" style="background-color:#fff;border-radius:4px 4px 0 0;height:6px;margin:auto;width:380px"></div>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td colspan="4" style="background-color:#fff;border-radius:4px 4px 0 0">';
			$text_e.= '<table width="414" border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td style="padding:1em 1em .2em 1em">';
			$text_e.= '<h1 style="font-size:1.4em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:lighter">Su Ticket</h1>';
			$text_e.= '<hr style="background-color:#3c7cb4;border:0 none;height:4px;margin-left:1em;margin-top:4px;width:70px">';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td style="padding:0 1em 1em 1em">';
			$text_e.= '<h2 style="color:#626262;font-size:1.2em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:bold">Estimado Usuario</h2>';
			$text_e.= '<span style="color:#626262;font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:normal">Los datos de su compra están indicados a continuación.</span> </td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td style="background-color:#f8f8f8;padding:1em">';
			$text_e.= '<table border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td>';
			$text_e.= '<h3 style="font-size:1.2em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:lighter">';
			$text_e.= 'Datos de la solicitud de pago</h3>';
			$text_e.= '<hr style="background-color:#3c7cb4;border:0 none;height:4px;margin-left:1em;margin-top:4px;width:70px">';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td>';
			$text_e.= '<table width="380" border="0" cellspacing="0" cellpadding="0" align="center" class="m_1950253956932142205m_-1841532062777334958bkng-table" style="border:1px solid #e4e4e4;background-color:#fff;border-radius:.2em">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td colspan="3" style="padding:1em 1em 0 1em"><span class="m_1950253956932142205m_-1841532062777334958bkng-bold" style="font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em">Número de folio:</span><br>';
			$text_e.= '<span class="m_1950253956932142205m_-1841532062777334958bkng-field" style="font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif">'.$folio.' </span>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td colspan="3" style="padding:0 1em .4em 1em"><span class="m_1950253956932142205m_-1841532062777334958bkng-bold" style="font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em">Fecha de Registro:</span><br>';
			$text_e.= '<span class="m_1950253956932142205m_-1841532062777334958bkng-field" style="font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif">'.$row->fec_sol.' </span>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td colspan="3" style="padding:0 1em .4em 1em"><span class="m_1950253956932142205m_-1841532062777334958bkng-bold" style="font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em"> </span><br>';
			$text_e.= '<span class="m_1950253956932142205m_-1841532062777334958bkng-field" style="font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif"></span>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td style="border-right:1px solid #e4e4e4;padding-left:1em"><span class="m_1950253956932142205m_-1841532062777334958bkng-bold" style="font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em">Clave</span><br>';
			$text_e.= '</td>';
			$text_e.= '<td style="border-right:1px solid #e4e4e4;padding-left:.6em"><span class="m_1950253956932142205m_-1841532062777334958bkng-bold" style="font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em">Concepto</span><br>';
			$text_e.= '</td>';
			$text_e.= '<td style="padding-left:.6em"><span class="m_1950253956932142205m_-1841532062777334958bkng-bold" style="font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em">Cantidad</span><br>';
			$text_e.= '</td>';
			$text_e.= '<td style="padding-left:.6em"><span class="m_1950253956932142205m_-1841532062777334958bkng-bold" style="font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em">Importe</span><br>';
			$text_e.= '</td>';
			$text_e.= '<td style="padding-left:.6em"><span class="m_1950253956932142205m_-1841532062777334958bkng-bold" style="font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em">Monto</span><br>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.='<tr>';
			
			$query1 = "SELECT * from vw_conceptos_sol where folio_sol = ".$folio." and id_solicitante = ".$id_solicitante;
			$resp1=$this->executeQueryMysql($query1);
			while($row1 = $resp1->fetch_object()){
				$text_e.='<tr><td align="center"class="m_1950253956932142205m_-1841532062777334958bkng-field" style="font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif">'.$row1->id_concepto_pago.'</td><td align="left" class="m_1950253956932142205m_-1841532062777334958bkng-field" style="font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif">'.($row1->nom_concepto_pago).'</td><td align="center"class="m_1950253956932142205m_-1841532062777334958bkng-field" style="font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif">'.$row1->cant_requerida.'</td><td align="right"class="m_1950253956932142205m_-1841532062777334958bkng-field" style="font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif">'.number_format($row1->importe_concepto_pago,2).'</td><td align="right"class="m_1950253956932142205m_-1841532062777334958bkng-field" style="font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif">'.number_format($row1->monto_tot_conc,2).'</td></tr>';
			
			}
		
			$text_e.='</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td colspan="4" style="background-color:#fff;border-radius:0 0 4px 4px">';
			$text_e.= '<table width="414" border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td style="padding:1em">';
			$text_e.= '<table border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table" width="380">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td style="color:#626262;font-size:1.2em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:bold">';
			$text_e.= 'Total de la Compra:</td>';
			$text_e.= '<td width="110" class="m_1950253956932142205m_-1841532062777334958bkng-row">&nbsp;</td>';
			$text_e.= '<td style="color:#626262;font-size:1.2em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:bold">$'.number_format($row->monto_total,2).'</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td colspan="3" style="text-align:center">';
			$text_e.= '<hr style="background-color:#3c7cb4;border:0 none;height:4px;margin-top:4px">';
			$text_e.= '<span style="color:#626262;font-size:.7em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;text-align:center">Se ha adjuntado su Ticket.</span>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td style="background-color:#f8f8f8">';
			$text_e.= '<table border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td style="padding:1em">';
			$text_e.= '<table border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td><span style="color:#626262;font-size:0.7em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:normal"><br>';
			$text_e.= '</span></td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td>';
			$text_e.= '<h2 style="color:#626262;font-size:1.2em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:bold">';
			$text_e.= 'Notas del Servicio</h2>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td><span style="color:#626262;font-size:0.7em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:normal">-----Este correo es de carácter informativo le agradecemos no contestarlo-----</span>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			
			
		 return $result = $text_e;
			
			
			
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
			
			$text_e.= '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color:#0b5ba1">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td>';
			$text_e.= '<table width="414" border="0" cellspacing="0" cellpadding="0" align="center" class="m_1950253956932142205m_-1841532062777334958bkng-table" style="padding:1em">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td colspan="4">';
			$text_e.= '<div align="center" class="m_1950253956932142205m_-1841532062777334958bkng-fancy--top" style="background-color:#fff;border-radius:4px 4px 0 0;height:6px;margin:auto;width:360px"></div>';
			$text_e.= '<div align="center" class="m_1950253956932142205m_-1841532062777334958bkng-fancy--btm" style="background-color:#fff;border-radius:4px 4px 0 0;height:6px;margin:auto;width:380px"></div>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td colspan="4" style="background-color:#fff;border-radius:4px 4px 0 0">';
			$text_e.= '<table width="414" border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td style="padding:1em 1em .2em 1em">';
			$text_e.= '<h1 style="font-size:1.4em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:lighter">Cuenta Registrada</h1>';
			$text_e.= '<hr style="background-color:#3c7cb4;border:0 none;height:4px;margin-left:1em;margin-top:4px;width:70px">';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td style="padding:0 1em 1em 1em">';
			$text_e.= '<h2 style="color:#626262;font-size:1.2em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:bold">Estimado Usuario</h2>';
			$text_e.= '<span style="color:#626262;font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:normal">El sistema de pagos de la DGIRE ha creado una cuenta a nombre de <b>'.$nomTotal.'</b></span> </td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td style="background-color:#f8f8f8;padding:1em">';
			$text_e.= '<table border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td>';
			$text_e.= '<table width="380" border="0" cellspacing="0" cellpadding="0" align="center" class="m_1950253956932142205m_-1841532062777334958bkng-table" style="border:1px solid #e4e4e4;background-color:#fff;border-radius:.2em">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td>';
			$text_e.= '<h3 style="font-size:1.2em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:lighter">';
			$text_e.= 'Por motivos de seguridad la cuenta se encuentra desactivada hasta que visite el siguiente enlace</h3>';
			$text_e.= '<hr style="background-color:#3c7cb4;border:0 none;height:4px;margin-left:1em;margin-top:4px;width:70px">';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td colspan="3" style="padding:1em 1em 0 1em"><span class="m_1950253956932142205m_-1841532062777334958bkng-bold" style="font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em">Enlace:</span><br>';
			$text_e.= '<span class="m_1950253956932142205m_-1841532062777334958bkng-field" style="font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif"><a class="text" href="'.$url_val.'" class="text">'.$url_val.'<br/></a></span>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td colspan="4" style="background-color:#fff;border-radius:0 0 4px 4px">';
			$text_e.= '<table width="414" border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td style="padding:1em">';
			$text_e.= '<table border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table" width="380">';
			$text_e.= '<tbody>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td style="background-color:#f8f8f8">';
			$text_e.= '<table border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td style="padding:1em">';
			$text_e.= '<table border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td><span style="color:#626262;font-size:0.7em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:normal"><br>';
			$text_e.= '</span></td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td>';
			$text_e.= '<h2 style="color:#626262;font-size:1.2em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:bold">';
			$text_e.= 'Notas del Servicio</h2>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td><span style="color:#626262;font-size:0.7em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:normal">-----Este correo es de carácter informativo le agradecemos no contestarlo-----</span>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			
		
		 return $result = $text_e;
			
			
			
		}
		#******************************#
		
		#******************************#
		public function imprimeFormularioRecovery($url_val){
	 	
			$text_e = '';
			
			$text_e.= '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color:#0b5ba1">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td>';
			$text_e.= '<table width="414" border="0" cellspacing="0" cellpadding="0" align="center" class="m_1950253956932142205m_-1841532062777334958bkng-table" style="padding:1em">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td colspan="4">';
			$text_e.= '<div align="center" class="m_1950253956932142205m_-1841532062777334958bkng-fancy--top" style="background-color:#fff;border-radius:4px 4px 0 0;height:6px;margin:auto;width:360px"></div>';
			$text_e.= '<div align="center" class="m_1950253956932142205m_-1841532062777334958bkng-fancy--btm" style="background-color:#fff;border-radius:4px 4px 0 0;height:6px;margin:auto;width:380px"></div>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td colspan="4" style="background-color:#fff;border-radius:4px 4px 0 0">';
			$text_e.= '<table width="414" border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td style="padding:1em 1em .2em 1em">';
			$text_e.= '<h1 style="font-size:1.4em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:lighter">Recuperar contrase&ntilde;a</h1>';
			$text_e.= '<hr style="background-color:#3c7cb4;border:0 none;height:4px;margin-left:1em;margin-top:4px;width:70px">';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td style="padding:0 1em 1em 1em">';
			$text_e.= '<h2 style="color:#626262;font-size:1.2em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:bold">Estimado Usuario</h2>';
			$text_e.= '<span style="color:#626262;font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:normal">Ha solicitado recuperar su contrase&ntilde;a, para continuar deber&aacute; dar click en el siguiente enlace y seguir las instrucciones que se le indican.</span> </td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td style="background-color:#f8f8f8;padding:1em">';
			$text_e.= '<table border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td>';
			$text_e.= '<table width="380" border="0" cellspacing="0" cellpadding="0" align="center" class="m_1950253956932142205m_-1841532062777334958bkng-table" style="border:1px solid #e4e4e4;background-color:#fff;border-radius:.2em">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td colspan="3" style="padding:1em 1em 0 1em"><span class="m_1950253956932142205m_-1841532062777334958bkng-bold" style="font-weight:bold;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:1em">Enlace:</span><br>';
			$text_e.= '<span class="m_1950253956932142205m_-1841532062777334958bkng-field" style="font-size:.8em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif"><a class="text" href="'.$url_val.'" class="text">'.$url_val.'<br/></a></span>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.='</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td colspan="4" style="background-color:#fff;border-radius:0 0 4px 4px">';
			$text_e.= '<table width="414" border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td style="padding:1em">';
			$text_e.= '<table border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table" width="380">';
			$text_e.= '<tbody>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td style="background-color:#f8f8f8">';
			$text_e.= '<table border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td style="padding:1em">';
			$text_e.= '<table border="0" cellspacing="0" cellpadding="0" class="m_1950253956932142205m_-1841532062777334958bkng-table">';
			$text_e.= '<tbody>';
			$text_e.= '<tr>';
			$text_e.= '<td><span style="color:#626262;font-size:0.7em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:normal"><br>';
			$text_e.= '</span></td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td>';
			$text_e.= '<h2 style="color:#626262;font-size:1.2em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:bold">';
			$text_e.= 'Notas del Servicio</h2>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '<tr>';
			$text_e.= '<td><span style="color:#626262;font-size:0.7em;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;margin:0;font-weight:normal">-----Este correo es de carácter informativo le agradecemos no contestarlo-----</span>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			$text_e.= '</td>';
			$text_e.= '</tr>';
			$text_e.= '</tbody>';
			$text_e.= '</table>';
			
		 return $result = $text_e;
			
		}
		#******************************#
		
		
		
		

    
    
    
}


?>
