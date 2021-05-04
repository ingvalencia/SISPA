<html lang="en">
<?php
/*Encabezado*/

$extraLib = <<<'EOD'
<script src="../js/common_function.js"></script>
<script src="../js/recovery_pwd.js"></script>
EOD;

require_once("../templete/header2.php");
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
                    
					<!--Btn regresar -->
					<div >
						<button id="btnRegresa" name="btnRegresa" style="float:right;" class="btn btn-outline btn-primary center-block clsRegresa "><span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar</button>		   
					</div>
					<!-- -->
					<h4 class="page-header" style="Padding-left:10px; color:#2e82c4;"><b>Solicitar Recuperación de Contraseña</b></h4>
					
                    <div class="bg-success" style="margin-left:15px; margin-right:15px;">
                        
                    </div>
                    
                    
                    <div class="alert bg-success">															 															
                            <div class="col-lg-12">          
                                <h5 style="Padding-left:15px; color:#E46565;"><b>Aviso:</b></h5>
                            </div>								 
                            <p align="justify" style="margin-left:15px;">
                                Para recuperar su contrase&ntilde;a es necesario que escriba el correo electr&oacute;nico capturado en el sistema y responder el mecanismo de seguridad del captcha.
                            </p>                                                        												
                    </div>
                    
                    <!--formulario -->
                    <form id="frmSolicitante">
                        
                        <section class="registro-form">
                            <div class="panel panel-default">          
                                <div class="panel-heading"><b>Datos generales</b></div>
                                    <div class="panel-body">
                                        
                                       
                                        <div  class="row form-group">
                                            <label class="control-label col-xs-3">Correo electrónico:</label>
                                            <div class="col-lg-7">
                                                <input type="text" id="correo_e" name="correo_e" placeholder="Correo electrónico: correo@correo.com" class="form-control" >
                                            </div>
                                        </div>
                        
                                        <div  class="row form-group">
                                            <label class="control-label col-xs-3">Confirmar correo electrónico:</label>
                                            <div class="col-lg-7">
                                                <input type="text" id="confirmar_correo_e" name="confirmar_correo_e" class="form-control" oncopy="return false;" onpaste="return false;" oncut="return false;">
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
                                                                                  
                                </div>
                            </div>
                        </section>
					</form>
                    <!-- -->
                    
                    <!--Btn enviar -->
                    <div class="">
                        <button id="btnRegistrar" name="btnRegistrar" class="btn btn-outline btn-primary center-block "><span class="glyphicon glyphicon-log-out"></span>Enviar</button>		   
                    </div>
                    <!-- -->
						
				</div><!-- Termina dvGrid -->
								
			</div><!-- Termina 1 -->
			
						<!--Mensaje Registro exitoso -->
						<div id="dvConfirm" style="display:none;" >
							
							<div class="alert alert-info">															 
								<div class="row" id="">											
									<div class="col-lg-12">
										<h3 style="Padding-left:15px; color:#2e82c4;">Recuperaci&oacute;n de Contrase&ntilde;a</h3>
									</div>							
									<p align="justify" style="margin-left:30px;">
										<p align="justify">Pr&oacute;ximamente recibir&aacute; un correo a su cuenta <b><label id="correo_e" name="correo_e"></label></b> con un enlace al cual deber&aacute; ingresar para cambiar su contrase&ntilde;a.</p>
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
																																					
							<div class="col-lg-3 col-lg-offset-4">						
								<button id="btnSalir" class="btn btn-outline btn-primary center-block ">
									<span class="glyphicon glyphicon-edit"></span> Salir
								</button>														
							</div>
							<br>
						</div>
						<!-- -->
						
						<!-- Modal -->
						<div class="modal fade" id="myModal" role="dialog">
							<div class="modal-dialog">
								<!-- Modal content-->
								<div class="modal-content">
									
									<div class="modal-header">
										<h4 class="modal-title">Aviso</h4>
									</div>
									
									<div class="modal-body">
										<div class="modal-body"  id="info_correo" title="info_correo"></div>
									</div>
									
									<div class="modal-footer">
										<button type="submit_ok" class="btn btn-info btn-lg" data-dismiss="modal"><span class=""></span> Aceptar</button> 
									</div>
									
								</div>									  
							</div>
						</div>
						<!-- --->
											
		</div><!-- Termina row -->
			
	</div>	<!-- Termina contenido -->
			
</div> <!-- Termina body -->
	

	
