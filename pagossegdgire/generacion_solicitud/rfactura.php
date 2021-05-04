<form id="frmfactura" name="frmfactura">

	<div class="row">
		<label class="col-md-3 control-label">Tipo de solicitante:</label>
		<div class="col-md-3">
			<select id="slcTipoUser" name="slcTipoUser" class="form-control">
				<option value="">Seleccione tipo de usuario</option>
				<option value="usr_reg">Usuario registrado</option>
				<option value="no_usr_reg">Usuario no registrado</option>
			</select>
		</div>
	</div>
	
	<div id="dvNombre" class="row" style="display:none;">
		<label for="nombre_solicitante" class="col-md-3 control-label">Nombre del solicitante:</label>
		<div class="col-md-3">
			<input id="nombre_solicitante" name="nombre_solicitante" class="form-control">
		</div>
	</div>
	
	<div class="row">
		<label for="correo_usuario" class="col-md-3 control-label">Correo del solicitante:</label>
		<div class="col-md-3">
			<input id="correo_usuario" name="correo_usuario" class="form-control">
		</div>
	</div>
	
	<div class="row">
		<label for="ptl_ptl" class="col-md-3 control-label">Asociar tramite a un plantel:</label>
		<div class="col-md-5">
			<select id="ptl_ptl" name="ptl_ptl" class="form-control chosen-select">
				<option value="">seleccione una opción</option>
			</select>
		</div>
	</div>
	
		
	<div class="row" >
		<label class="col-md-3 control-label">Desea facturar:</label>
		<div class="col-md-3">
			<input type="checkbox" id="chFactura" name="chFactura"></input>
		</div>
	</div>
	
	<div id="dvRFC" style="display:none;">
		<div class="row">
			<label class="col-md-3 control-label">RFC de facturación:</label>
			<div class="col-md-3">
				<input id="dvDataRFC" name="dvDataRFC" list="lstRFC" class="chosen-select form-control">
				<datalist id="lstRFC">
				</datalist>
			</div>
		</div>
		
		<div class="row">
			<label class="col-md-3 control-label">Registrar nuevo RFC:</label>
			<div class="col-md-3">
				<input type="checkbox" id="chDataRFC" name="chDataRFC"></input>
			</div>
		</div>
		
	</div>
	
	

	<div id="dvFactura" name="dvFactura" style="display:none;">
    
        <fieldset class="well well-sm">
            <legend class="the-legend">Datos de facturaci&oacute;n del solicitante</legend>

            <div class="panela apanel-info">

                <!--Aqui empieza el formulario registro -->
                <div id="dvregfactura" class="modal-body" style="display:nonew;">
                    
					
					<div class="row form-group">
                        <label class="control-label col-xs-3">Tipo de persona (*):</label>
                        <div class="col-xs-3">
                            <select id="tipo_persona" name="tipo_persona" class="form-control vFac">
								<option value="">Seleccione ... </option>
								<option value="1">Física</option>
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
                        <label class="control-label col-xs-3">Raz&oacute;n Social (*):</label>
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
                            <input type="text" id="id_cp" name="id_cp" size="6" maxlength="5" onchange="" class="form-control vFac" /><span id=""></span>
                        </div>
                        <div class="col-md-1"></div>
                    </div>

                    <div class="row form-group">
                        <label class="control-label col-xs-3">Estado (*):</label>
                        <div class="col-xs-3">
                            <input type="text" id="nom_edo" name="nom_edo" class="form-control" disabled>
                            <input type="text" id="id_edo" name="id_edo" class="form-control" style="display:none;">
                        </div>
                        <div class="col-md-1"></div>
                    </div>

                    <div class="row form-group">
                        <label class="control-label col-xs-3">Ciudad (*):</label>
                        <div class="col-xs-3">
                            <input type="text" id="id_ciudad" name="id_ciudad" class="form-control" style="display:none;">
                            <input type="text" id="nom_ciudad" name="nom_ciudad" class="form-control" disabled>
                        </div>
                        <div class="col-md-1"></div>
                    </div>

                    <div class="row form-group">
                        <label class="control-label col-xs-3">Municipio (*):</label>
                        <div class="col-xs-3">
                            <input type="text" id="id_municipio" name="id_municipio" class="form-control" style="display:none;">
                            <input type="text" id="nom_municipio" name="nom_municipio" class="form-control" disabled>
                        </div>
                        <div class="col-md-1"></div>
                    </div>

                    <div class="row form-group">
                        <label class="control-label col-xs-3">Colonia (*):</label>
                        <div class="col-xs-3">
                            <select name="id_colonia" id="id_colonia" class="form-control"></select>

                        </div>
                        <div class="col-md-1"></div>
                    </div>
					
					<div class="row form-group">
                        <label class="control-label col-xs-3">Marque la casilla si NO aparece su colonia:</label>
                        <div class="col-xs-3">
							<input type="checkbox" id="check_colonia" name="check_colonia"  value="on">
                        </div>
                        <div class="col-md-1"></div>
                    </div>

                    <div class="row form-group" id="datOtraColonia" style="display:none;">
                        <label class="control-label col-xs-3">Escriba su colonia (*) :</label>
                        <div class="col-xs-3">
                            <input type="text" id="txtOtraCol" name="txtOtraCol" size="30" maxlength="30" class="form-control" />
                        </div>
                        <div class="col-md-1"></div>
                    </div>
					
					<div class="row form-group">
                        <label class="control-label col-xs-3">Calle (*):</label>
                        <div class="col-xs-3">
                            <input type="text" id="calle" name="calle" class="form-control vFac" >
                        </div>
                        <div class="col-md-1"></div>
                    </div>
					
					<div class="row form-group">
                        <label class="control-label col-xs-3">Num exterior(*):</label>
                        <div class="col-xs-3">
                            <input type="text" id="num_ext" name="num_ext" class="form-control vFac" >
                        </div>
                        <div class="col-md-1"></div>
                    </div>
					
					<div class="row form-group">
                        <label class="control-label col-xs-3">Num interior:</label>
                        <div class="col-xs-3">
                            <input type="text" id="num_int" name="num_int" class="form-control" >
                        </div>
                        <div class="col-md-1"></div>
                    </div>
					
					
                </div>

            </div>
        </fieldset>
   
	</div>
</form>
<!--Fin formulario -->
