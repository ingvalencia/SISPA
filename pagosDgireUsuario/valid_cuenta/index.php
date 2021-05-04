<html lang="en">
<?php
/*Encabezado*/

$extraLib = <<<'EOD'
<script src="../js/common_function.js"></script>

EOD;

require_once("../templete/header2.php");
require_once '../common/config.php';
   
?>


<!-- inicia body -->
<div id="page-wrapper">
		 
	<!-- contenido -->
	<div class="container-fluid">
		<!-- row -->	
		<div class="row">
					
			<!-- 1 -->
			<div class="col-lg-12">
						
				<!-- dvGrid -->
				<div id="dvGrid">
					
					<h3 class="page-header" style="Padding-left:15px; color:#2e82c4;">Registro de solicitud de pago</h3>
					
					<div class="row text-center" id="error" title="Error" style="display:none; font-size:12px;"></div>
					<div class="row text-center" id="ok" title="OK" style="display:none; font-size:12px;"></div>
					
					<!-- Modal -->
					<div class="modal fade" id="myModal" role="dialog">
						<div class="modal-dialog">
							<!-- Modal content-->
							<div class="modal-content">
								
								<div class="modal-header">
									<h4 class="modal-title">Aviso</h4>
								</div>
								
								<div class="modal-body">
									<div class="modal-body"  id="error" title="error"></div>
								</div>
								
								<div class="modal-footer">
									<button type="submit_error" class="btn btn-danger btn-default clsRedirecciona" data-dismiss="modal"><span class=""></span>Aceptar</button> 
								</div>
								
							</div>									  
						</div>
					</div>
					<!-- --->
					
					<!-- Modal -->
					<div class="modal fade" id="myModal1" role="dialog">
						<div class="modal-dialog">
							<!-- Modal content-->
							<div class="modal-content">
								
								<div class="modal-header">
									<h4 class="modal-title">Aviso</h4>
								</div>
								
								<div class="modal-body">
									<div class="modal-body"  id="ok" title="ok"></div>
								</div>
								
								<div class="modal-footer">
									<button type="submit_ok" class="btn btn-info btn-lg clsRedirecciona" data-dismiss="modal"><span class=""></span> Aceptar</button> 
								</div>
								
							</div>									  
						</div>
					</div>
					<!-- --->
					
				
					<script language="JavaScript" type="text/javascript">
						
							$(document).ready(function() {
								verificaCuentaUsuario();
							});
							
							//Boton redirecciona
							$(document).on('click','.clsRedirecciona',function(){
						
								setTimeout("window.location.href='../acceso/index.php';",0);
						
							});
							
							
							$( "#error" ).dialog({
								open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog).hide(); },
								autoOpen: false,
								resizable: true,
								height:180,
								width:460,	
								modal: true,
								
							});
						
							$( "#ok" ).dialog({
								open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog).hide(); },
								autoOpen: false,
								resizable: true,
								height:180,
								width:460,	
								modal: true,
								
							});
							
							function verificaCuentaUsuario() {
							
								$.blockUI({ theme: true, title: 'Procesando informaci&oacute;n',  message: '<table><tr><td valign=\"middle\" width=\"230\">Espere un momento . . .</td></tr></table>' });
								
								$.ajax({
									type: "POST",
									url: "processRegistro.php",
									data: "task=<?php echo $_REQUEST['task']; ?>&txtEmail=<?php echo $_REQUEST['email']; ?>&cadena_val=<?php echo $_REQUEST['cadena_val']; ?>",
									
							  dataType: "json",
									success: function(resp) {
										if(resp.error){
											$.unblockUI();
											$("#myModal").modal();
											$("#error").html(resp.message);
										}else{
											$.unblockUI();
											$("#myModal1").modal();
											$("#ok").html(resp.message);
										}
									}
								});
						
							}
	
	
					</script>		
							
				</div><!-- Termina dvGrid -->
								
			</div><!-- Termina 1 -->
					
		</div><!-- Termina row -->
			
	</div>	<!-- Termina contenido -->
			
</div> <!-- Termina body -->
	

	