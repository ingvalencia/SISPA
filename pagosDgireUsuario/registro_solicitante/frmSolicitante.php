
<section class="registro-form">
	
	<div class="panel panel-default">
                    
		<div class="panel-heading"><b>Datos de usuario</b></div>
			
			<div class="panel-body">
				
			   <div class="row form-group">
					<label class="control-label col-xs-3" >Perfil:</label>
					<div class="col-lg-6">
						<select id="id_perfil" name="id_perfil" class="form-control" ></select>
					</div>
				</div>

				<div id="dv_ptl_ptl"  class="row form-group" style="display:none;">
					<label class="control-label col-xs-3">Clave plantel:</label>
					<div class="col-lg-6">
						<input type="text" id="ptl_ptl" name="ptl_ptl" class="form-control" >
					</div>
				</div>

				<div id="dv_exp_unam"  class="row form-group" style="display:none;">
					<label class="control-label col-xs-3">Número expediente:</label>
					<div class="col-lg-6">
						<input type="text" id="exp_unam" name="exp_unam" class="form-control" >
					</div>
				</div>

				<div class="row form-group">
					<label class="control-label col-xs-3">Nombre:</label>
					<div class="col-lg-6">
						<input type="text" id="nombre" name="nombre" class="form-control letras" maxlength="30" placeholder="Nombre" >
					</div>
				</div>
				
				<div  class="row form-group">
					<label class="control-label col-xs-3">Apellido paterno:</label>
					<div class="col-lg-6">
						<input type="text" id="ap_paterno" name="ap_paterno" class="form-control letras" maxlength="30" placeholder="Apellido paterno" >
					</div>
				</div>

				<div class="row form-group">
					<label class="control-label col-xs-3">Apellido materno:</label>
					<div class="col-lg-6">
						<input type="text" id="ap_materno" name="ap_materno" class="form-control letras" maxlength="30" placeholder="Apellido materno" >
					</div>
				</div>

				<div  class="row form-group">
					<label class="control-label col-xs-3">Correo electrónico:</label>
					<div class="col-lg-6">
						<input type="text" id="correo_e" name="correo_e" class="form-control" >
					</div>
				</div>

				<div  class="row form-group">
					<label class="control-label col-xs-3">Confirmar correo electrónico:</label>
					<div class="col-lg-6">
						<input type="text" id="confirmar_correo_e" name="confirmar_correo_e" class="form-control" oncopy="return false;" onpaste="return false;" oncut="return false;" >
					</div>
				</div>

				<div  class="row form-group">
					<label class="control-label col-xs-3">Teléfono:</label>
					<div class="col-lg-6">
						<input type="text" id="telefono" name="telefono" class="form-control" placeholder="Ej (5556226040)" >
					</div>
				</div>

				<div  class="row form-group">
					<label class="control-label col-xs-3">Celular:</label>
					<div class="col-lg-6">
						<input type="text" id="celular" name="celular" class="form-control" placeholder="Ej (5556226040)" >
					</div>
				</div>

				<div  class="row form-group">
					<label class="control-label col-xs-3">Contraseña:</label>
					<div class="col-lg-6">
						<input type="password" id="passwd" name="passwd" class="form-control" maxlength="15" >
					</div>
				</div>

				<div  class="row form-group">
					<label class="control-label col-xs-3">Confirmar contraseña:</label>
					<div class="col-lg-6">
						<input type="password" id="confirmar_passwd" name="confirmar_passwd" class="form-control" maxlength="15" oncopy="return false;" onpaste="return false;" oncut="return false;" >
					</div>
				</div>

				<br>

				<div class="rows form-group">
					<label class="col-lg-11">Marque la casilla de verificación si desea ingresar sus datos de facturación</label>
					<div class="col-lg-1">
						<input type="checkbox" id="chFactura" name="chFactura" value="1" >
					</div>
				</div>
				
				<br><br>
				
				<!--Captcha -->
				
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

				<!--Opci?n checkbox -->
				<div class="col-lg-12"> 
					<div class="rows form-group">
						<label class="col-lg-11">
							<input type="checkbox" id="chTerminos" name="agree" value="agree" /> Confirme que usted acepta los Términos y Condiciones del <a>Aviso de Privacidad</a>.
						</label>
													  
					</div>
				</div>
				
			</div>
	</div>
		                
</section>
    	
