<form id="frmfactura" name="frmfactura">

<div class="row">
    <label class="col-md-3 control-label">Tipo de usuario:</label>
    <div class="col-md-3">
        <select id="slcTipoUser" name="slcTipoUser" class="form-control">
			<option value="">Seleccione tipo de usuario</option>
			<option value="usr_reg">Usuario registrado</option>
			<option value="no_usr_reg">Usuario no registrado</option>
		</select>
    </div>
</div>

<div id="dvSlcRFC" name="dvSlcRFC" style="display:none;">
	<div class="row">
		<label for="correo_usuario" class="col-md-3 control-label">Correo del usuario:</label>
		<div class="col-md-3">
			<input id="correo_usuario" name="correo_usuario" class="form-control">
		</div>
		<div class="col-md-3">
			<button id="btnSearchRFC" class="btn btn-success">Buscar</button>
		</div>
	
	</div>
	
	<div id="dvLstRFC" class="row" >
		<label class="col-md-3 control-label">Seleccione el RFC con el que desea facturar:</label>
		<div class="col-md-3">
			<select id="slcOrigenRFC" name="slcOrigenRFC" class="form-control">
			</select>
		</div>
	</div>
</div>

<div id="dvfactura" name="dvfactura" style="display:none;">
    <div class="row form-horizontal">
        <fieldset class="well well-sm">
            <legend class="the-legend">Datos de facturaci&oacute;n de la solicitud</legend>

            <div class="panela apanel-info">

                <!--Aqui empieza el formulario registro -->
                <div id="dvregfactura" class="modal-body" style="display:nonew;">
                    
					<div class="row form-group">
                        <label class="control-label col-xs-3">Correo (*):</label>
                        <div class="col-xs-3">
                            <input type="text" id="correo_rfc" name="correo_rfc" class="form-control vFac">
                        </div>
                        <div class="col-md-1"></div>
                    </div>
					
					<div class="row form-group">
                        <label class="control-label col-xs-3">Tipo de persona (*):</label>
                        <div class="col-xs-3">
                            <select id="tipo_persona" name="tipo_persona" class="form-control vFac">
								<option value="">Seleccione ... </option>
								<option value="1">F&iacute;sica</option>
								<option value="2">Moral</option>
							</select>
                        </div>
                        <div class="col-md-1"></div>
                    </div>

                    <div class="row form-group">
                        <label class="control-label col-xs-3">RFC (*):</label>
                        <div class="col-xs-3">
                            <input id="rfc" name="rfc" class="form-control vFac" type="text" maxlength="13">
                        </div>
                        <div class="col-md-1"></div>
                    </div>

                    <div class="row form-group" id="cedulaMoral" style="display:none;">
                        <label class="control-label col-xs-3">Raz&oacute;n Social (*):</label>
                        <div class="col-xs-3">
                            <input type="text" id="nombre_fisc" name="nombre_fisc" size="30" maxlength="120" class="form-control vFac">
                        </div>
                        <div class="col-md-1"></div>
                    </div>

                    <div id="cedulaPerfisica" style="display:none;">
                        <div class="row form-group">
                            <label class="control-label col-xs-3">Nombre (*):</label>
                            <div class="col-xs-3">
                                <input type="text" id="fnombre" name="fnombre" size="30" maxlength="50" class="form-control vFac" placeholder="Nombre">
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <div class="row form-group">
                            <label class="control-label col-xs-3">Apellido Paterno (*):</label>
                            <div class="col-xs-3">
                                <input type="text" id="fap_paterno" name="fap_paterno" size="30" maxlength="50" class="form-control vFac" placeholder="Apellido Paterno">
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                        <div class="row form-group">
                            <label class="control-label col-xs-3">Apellido Materno :</label>
                            <div class="col-xs-3">
                                <input type="text" id="fap_materno" name="fap_materno" size="30" maxlength="50" class="form-control vFac" placeholder="Apellido Materno">
                            </div>
                            <div class="col-md-1"></div>
                        </div>

                    </div>

                    <div class="row form-group">
                        <label class="control-label col-xs-3">Calle (*):</label>
                        <div class="col-xs-3">
                            <input type="text" id="calle" name="calle" size="30" maxlength="80" class="form-control vFac">
                        </div>
                        <div class="col-md-1"></div>
                    </div>

                    <div class="row form-group">
                        <label class="control-label col-xs-3">N&uacute;mero Ext. (*):</label>
                        <div class="col-xs-3">
                            <input type="text" id="num_ext" name="num_ext" size="20" maxlength="20" class="form-control vFac">
                        </div>
                        <div class="col-md-1"></div>
                    </div>

                    <div class="row form-group">
                        <label class="control-label col-xs-3">N&uacute;mero Int. :</label>
                        <div class="col-xs-3">
                            <input type="text" id="num_int" name="num_int" size="20" maxlength="20" class="form-control vFac">
                        </div>
                        <div class="col-md-1"></div>
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

                </div>

            </div>
        </fieldset>
    </div>
	</div>
</form>
<!--Fin formulario -->