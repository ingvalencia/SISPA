<html lang="en">
<?php
/*Encabezado*/

include_once ("../common/config.php");
include_once ("../common/clsSolicitantes.php");

$extraLib = <<<'EOD'
<script src="../js/common_function.js"></script>
<script src="../js/recovery_pwd.js"></script>
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
					
					<?php
					$solicitantes = new clsSolicitantes();
					if($_REQUEST['task']!='' and $_REQUEST['task']=='recoveryPwd')
					{
						$validLink=$solicitantes->changePasswordValid($_REQUEST['email'], $_REQUEST['cadena_val']);
						
						if($validLink['success']!=0)
						{
						
                    ?>
									<h3 class="page-header" style="Padding-left:15px; color:#2e82c4;">Cambio de contraseña</h3>
									
									<div class="bg-success" style="margin-left:15px; margin-right:15px;"></div>
									
									
									<div class="alert bg-success">															 													 
											<p align="justify" style="margin-left:15px;">
												Por favor digite la nueva contraseña.
											</p>											
									</div>
												
									<!--formulario -->
									<form id="frmSolicitanteA">
										
										<section class="registro-form">
											<div class="panel panel-default">          
												<div class="panel-heading"><b>Datos generales</b></div>
													<div class="panel-body">
														
													   
														<div  class="row form-group">
															<label class="control-label col-xs-3">Contrase&ntilde;a:</label>
															<div class="col-lg-6">
																<input type="password" id="passwd" name="passwd" class="form-control" >
															</div>
														</div>
				
														<div  class="row form-group">
															<label class="control-label col-xs-3">Confirmar contrase&ntilde;a:</label>
															<div class="col-lg-6">
																<input type="password" id="confirmar_passwd" name="confirmar_passwd" class="form-control" oncopy="return false;" onpaste="return false;" oncut="return false;" >
															</div>
														</div>
																
														<div class="row ">
															
															<div class="col-sm-4 alert alert-info">
																<div class="checkbox">
																	<label class="cbx-label" >
																		<input type="checkbox" id="Chcaptcha" name="Chcaptcha"/> 
																	  No soy un robot.
																	</label>
																</div>
															</div>
																	  
															<div class="col-sm-8">
																<div  class="row form-group" id="captcha_">
																	<label class="control-label col-xs-4" id="captchaOperation"></label>
							
																	<div class="col-lg-5">
																	  <input type="text" class="form-control" id="captcha" name="captcha" />
																	</div>
							
																	<div class="col-lg-2">
																	  <button id="btnRegenera" name="btnRegenera" class="btn btn-outline btn-primary center-block "><span class="glyphicon glyphicon-repeat"></span> Recargar</button>  
																	</div>
																</div>   
															</div>
													  </div>
													
														<!-- -->
														<input type="hidden" value="<?php echo $_REQUEST['email']; ?>" name="email" />
														<input type="hidden" value="<?php echo $_REQUEST['cadena_val']; ?>" name="cadena_val" />												  
													
													</div>
											</div>
										</section>
									</form>
									<!-- -->
									
									<!--Btn regresar -->
									<div class=" ">
										<button id="btnRegistrarA" name="btnRegistrarA" class="btn btn-outline btn-primary center-block "><span class="glyphicon glyphicon-log-out"></span>Enviar</button>		   
									</div>
									<!-- -->
					<?php
						}else{
							 echo $validLink['message'];
							 
							 echo '<div class=" ">
										<button id="btnSolicita" name="btnSolicita" class="btn btn-outline btn-primary center-block "><span class="glyphicon glyphicon-log-out"></span>Solicitar</button>		   
								   </div>';
							 
							 
						}
					}
					?>
						
				</div><!-- Termina dvGrid -->
								
			</div><!-- Termina 1 -->
			
						<!-- Modal -->
						<div class="modal fade" id="myModal" role="dialog">
							<div class="modal-dialog">
								<!-- Modal content-->
								<div class="modal-content">
									
									<div class="modal-header">
										<h4 class="modal-title">Aviso</h4>
									</div>
									
									<div class="">
										<div class="modal-body"  id="info_pwd" title="info_pwd"></div>
									</div>
									
									<div class="modal-footer">
										<button type="submit_ok" id="btnSolicita" class="btn btn-info btn-lg" data-dismiss="modal"><span class=""></span> Aceptar</button> 
									</div>
									
								</div>									  
							</div>
						</div>
						<!-- --->
											
		</div><!-- Termina row -->
			
	</div>	<!-- Termina contenido -->
			
</div> <!-- Termina body -->



	
