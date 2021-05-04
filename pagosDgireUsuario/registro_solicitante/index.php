<html lang="en">
<?php
/*Encabezado*/
$rd = rand();

$extraLib = <<<"EOD"
<script src="../js/common_function.js?$rd"></script>
<script src="../js/registro_solicitante.js?$rd"></script>
EOD;

require_once("../templete/header2.php");
require_once('../js/recaptcha/recaptchalib.php');
require_once '../common/config.php';
   
?>

<!-- inicia body -->
<div id="Wrapper">
		 
	<!-- contenido -->
	<div class="container-fluid">
		<!-- row -->	
		<div class="row">
					
			<!-- 1 -->
			<div class="col-lg-12">
						
				<!-- dvGrid -->
				<div id="dvGrid">
					<h4 class="page-header" style="Padding-left:10px; color:#2e82c4;"><b>Registro de solicitud de pago</b></h4>
					<!-- form -->		
					<form id="frmSolicitante">
									
						<div class="col-lg-6">
							<?php require_once("frmSolicitante.php"); ?>					
						</div>
				
						<div class="col-lg-6">
							<div id="dvFactura">
								<?php require_once("frmFactura.php"); ?>		
							</div>
						</div>
											   
					</form>
								
					<br><br>
					<!--Btn Input -->
					<div class="col-lg-3 col-lg-offset-4">
						<button id="btnRegistrar" name="btnRegistrar" class="btn btn-outline btn-primary center-block "><span class="glyphicon glyphicon-log-out"></span> Registrar</button>		   
					</div>
					<!-- -->
									
							
				</div><!-- Termina dvGrid -->
								
			</div><!-- Termina 1 -->
				
				
			<!-- Modal -->
			<div class="modal fade" id="myModal" role="dialog">
				<div class="modal-dialog modal-lg">
					<!-- Modal content-->
					<div class="modal-content">									
						<div class="modal-body"  id="terminos" title="terminos"></div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-primary btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-ok"></span> Acepto</button> 
							</div>
					</div>									  
				</div>
			</div>
			<!-- --->
			
			<!--Mensaje Registro exitoso -->
			<div id="dvConfirm" style="display:none;" >
				<div class="alert alert-info">
					
					<div class="row" id="imprimeRegistro">											
						<div class="col-lg-12">
							<h3 style="Padding-left:15px; color:#2e82c4;">Registro Exitoso</h3>
						</div>							
						<p align="justify" style="margin-left:30px;">
							<p align="justify">Le confirmamos que su registro al sistema de pagos se ha realizado satisfactoriamente.</p>
							<p>Pr&oacute;ximamente recibir&aacute; un correo a su cuenta: <b><label id="correo_e" name="correo_e"></label></b> con un enlace al cual deber&aacute; ingresar para activarla.</p>
							<p>En cuanto su cuenta este activa podr&aacute; ingresar al sistema de pagos para solicitar fichas de dep&oacute;sito de cualquier servicio.</p>  
							<br><br>
						</p>							
						<div class="col-lg-12">          
							<h4 style="Padding-left:15px; color:#E46565;">Nota:</h4>
						</div>								 
						<p align="justify" style="margin-left:30px;">
							<p> Si no recibe el correo aseg&uacute;rese de revisar su bandeja de correos no deseados o de ser necesario contactarnos a los correos
							<A HREF="mailto:pagosdgire@dgire.unam.mx">pagosdgire@dgire.unam.mx</A> o <A HREF="mailto:sistemas@dgire.unam.mx">sistemas@dgire.unam.mx</A></p>
							<br>
							<br>
						</p>
																									
					</div>
					<br>
																			
				</div>
																			
				<div class="col-lg-2 col-lg-offset-2">					
					<button class="btn btn-outline btn-primary center-block" name="btnImp" id="btnImp">
						<!--<a href="javascript:imprSelec('imprimeRegistro')" ><span class="glyphicon glyphicon-save-file"></span> Imprimir</a>
						-->
						<span class="glyphicon glyphicon-save-file"></span> Imprimir
						
					</button>
																			
				</div>
																			
				<div class="col-lg-3 col-lg-offset-3">							
					<button id="btnSalir" class="btn btn-outline btn-primary center-block ">
						<span class="glyphicon glyphicon-edit"></span> Salir
					</button>														
				</div>
				<br>
				
			</div>
					
		</div><!-- Termina row -->
			
	</div>	<!-- Termina contenido -->
			
</div> <!-- Termina body -->
	

	
	    
