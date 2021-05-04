
<form id="frmrfactura" name="frmrfactura" >
	<div class="row form-horizontal">
		<fieldset class="panel">
			<legend class="the-legend">Datos de facturación de la solicitud</legend>
							
				<!--div que muestra que si hay RFC -->
				<div id="SiRFC" style="display:none;">
					
					<div class="row form-group">
						<label class="control-label col-xs-3">RFC de facturación:</label>
						<div class="col-xs-3">
							<!--class="chosen-select"-->
							<select id="dvDataRFC" name="dvDataRFC" data-placeholder="Seleccione un concepto...">
								<option value="">Seleccione un concepto...</option>
							</select>
						</div>
					</div>
					
				</div>
				
				<!--div que muestra que si no hay RFC -->
				<div id="NoRFC" style="display:none;">
					<div class="alert alert-danger">
						<div class="row">
							No cuenta con un RFC registrado.Marque la casilla <b>"Agregar RFC"</b> para registrar su RFC.				
						</div>
					</div>
				</div>
				
				<div class="row form-group">
					<label class="control-label col-xs-3" >Agregar nuevo RFC:</label>
					<div class="col-xs-1">
						<input type="checkbox" id="addRFC" name="addRFC"  value="1"/>
					</div>						
					<div class="col-xs-1"></div>		
				</div>
		
				<!--Aqui empieza el formulario registro -->
				<div id="dvregfactura" class="modal-body" style="display:none;">
					<div class="row form-group">
						<label class="control-label col-xs-3">Tipo de persona (*):</label>
						<div class="col-xs-3">
							<select id="tipo_persona" name="tipo_persona" class="form-control">
								<option value="">Seleccione ... </option>
								<option value="1">F&iacute;sica</option>
								<option value="2">Moral</option>
							</select>
						</div>
						<div class="col-md-1"></div>
					</div>
									
					<div class="row form-group">
						<label class="control-label col-xs-3" >RFC (*):</label>
						<div class="col-xs-3">
							<input id="rfc" name="rfc" class="form-control" type="text" maxlength="13">
						</div>
						<div class="col-md-1"></div>
					</div>
									
					<div class="row form-group" id="cedulaMoral" style="display:none;">
						<label class="control-label col-xs-3" >Raz&oacute;n Social (*):</label>
						<div class="col-xs-3">
							<input type="text" id="nombre_fisc" name="nombre_fisc" size="30" maxlength="120" class="form-control">
						</div>
						<div class="col-md-1"></div>
					</div>
	
					<div id="cedulaPerfisica" style="display:none;">
						<div class="row form-group">
							<label class="control-label col-xs-3">Nombre (*):</label>
							<div class="col-xs-3">
								<input type="text" id="fnombre" name="fnombre" size="30" maxlength="50" class="form-control" placeholder="Nombre">
							</div>
							<div class="col-md-1"></div>
						</div>
		
						<div class="row form-group">
							<label class="control-label col-xs-3">Apellido Paterno (*):</label>
							<div class="col-xs-3">
								<input type="text" id="fap_paterno" name="fap_paterno" size="30" maxlength="50" class="form-control" placeholder="Apellido Paterno">
							</div>
							<div class="col-md-1"></div>
						</div>
		
						<div class="row form-group">
							<label class="control-label col-xs-3">Apellido Materno :</label>
							<div class="col-xs-3">
								<input type="text" id="fap_materno" name="fap_materno" size="30" maxlength="50" class="form-control" placeholder="Apellido Materno">
							</div>
							<div class="col-md-1"></div>
						</div>
	
					</div>
									
					<div class="row form-group">
						<label class="control-label col-xs-3">C&oacute;digo Postal (*):</label>
						<div class="col-xs-3">
							<input type="text" id="id_cp" name="id_cp" size="6" maxlength="5" onchange="" class="form-control"/><span id=""></span>
						</div>
						<div class="col-md-1"></div>
					</div>
									
					<div class="row form-group">
						<label class="control-label col-xs-3">Estado (*):</label>
						<div class="col-xs-3">
  							<input type="text" id="nom_edo" name="nom_edo" class="form-control" disabled>
							<input type="text" id="id_edo" name="id_edo" class="form-control" style="display:none;" >
						</div>
						<div class="col-md-1"></div>
					</div>
									
					<div class="row form-group">
						<label class="control-label col-xs-3">Ciudad (*):</label>
						<div class="col-xs-3">
							<input type="text" id="id_ciudad" name="id_ciudad" class="form-control" style="display:none;" >
							<input type="text" id="nom_ciudad" name="nom_ciudad" class="form-control" disabled >
						</div>
						<div class="col-md-1"></div>
					</div>
									
					<div class="row form-group">
						<label class="control-label col-xs-3">Municipio (*):</label>
						<div class="col-xs-3">
  							<input type="text" id="id_municipio" name="id_municipio" class="form-control" style="display:none;" >
							<input type="text" id="nom_municipio" name="nom_municipio" class="form-control" disabled >
						</div>
						<div class="col-md-1"></div>
					</div>
									
					<div class="row form-group">
						<label class="control-label col-xs-3">Colonia (*):</label>
						<div class="col-xs-3">
							<select name="id_colonia" id="id_colonia" class="form-control"  ></select>
												
						</div>
						<div class="col-md-1"></div>
					</div>
									
					<div class="row form-group">
						<label class="control-label col-xs-3" >Marque la casilla si NO aparece su colonia.</label>
						<div class="col-xs-3">
							<input type="checkbox" id="check_colonia" name="check_colonia"  value="on">
						</div>
						<div class="col-md-1"></div>
					</div>
									
					<div class="row form-group" id="datOtraColonia" style="display:none;">
						<label class="control-label col-xs-3">Escriba su colonia (*) :</label>
						<div class="col-xs-3">		
							<input type="text" id="txtOtraCol" name="txtOtraCol" size="30" maxlength="30" class="form-control"/>
						</div>
						<div class="col-md-1"></div>
					</div>
					
					<div class="row form-group">
						<label class="control-label col-xs-3">Calle (*):</label>
						<div class="col-xs-3">
							<input type="text" id="calle" name="calle" size="30"  maxlength="80" class="form-control">
						</div>
						<div class="col-md-1"></div>
					</div>
	
					<div class="row form-group">
						<label class="control-label col-xs-3">N&uacute;mero Ext. (*):</label>
						<div class="col-xs-3">
							<input type="text" id="num_ext" name="num_ext" size="20" maxlength="20" class="form-control" >
						</div>
						<div class="col-md-1"></div>
					</div>
							
					<div class="row form-group">
						<label class="control-label col-xs-3">N&uacute;mero Int. :</label>
						<div class="col-xs-3">
							<input type="text" id="num_int" name="num_int" size="20" maxlength="20" class="form-control" >
						</div>
						<div class="col-md-1"></div>
					</div>
				</div>
																							
				
		</fieldset>
	</div>	    
</form>
<!--Fin formulario -->		
