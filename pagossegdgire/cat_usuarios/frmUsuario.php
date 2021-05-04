<div id="dvFrmUsuario" style="display:none;">
	<form id="frmUsuario" name="frmUsuario"  data-toggle="validator">
		<div class="row form-group">
			<label class="col-md-3 control-label">Nombre:</label>
			<div class="col-md-9">
				<input id="nombre_usr" name="nombre_usr" class="form-control" type="text">
			</div>
		</div>
		<div class="row form-group">
			<label class="col-md-3 control-label">Apellido paterno:</label>
			<div class="col-md-9">
				<input id="ap_pat_usr" name="ap_pat_usr" class="form-control" type="text">
			</div>
		</div>
		<div class="row form-group">
			<label class="col-md-3 control-label">Apellido materno:</label>
			<div class="col-md-9">
				<input id="ap_mat_usr" name="ap_mat_usr" class="form-control" type="text">
			</div>
		</div>
		<div class="row form-group">
			<label class="col-md-3 control-label">Login:</label>
			<div class="col-md-9">
				<input id="login" name="login" class="form-control" type="text">
			</div>
			<div class="col-md-5"></div>
		</div>
		<div id="dvPasswd_ch" class="row form-group" style="display:none;">
			<label class="col-md-3 control-label">Cambiar password:</label>
			<div class="col-md-2">
				<input id="passwd_ch" name="passwd_ch" type="checkbox">
			</div>
			<div class="col-md-7"></div>
		</div>
		<div id="dvPasswd">
			<div class="row form-group">
				<label class="col-md-3 control-label">Password:</label>
				<div class="col-md-2">
					<input id="passwd" name="passwd" class="form-control" type="password">
				</div>
				<div class="col-md-7"></div>
			</div>
			<div class="row form-group">
				<label class="col-md-3 control-label">Confirmar password:</label>
				<div class="col-md-2">
					<input id="confirm_passwd" name="confirm_passwd" class="form-control" type="password">
				</div>
				<div class="col-md-7"></div>
			</div>
		</div>
		<div class="row form-group">
			<label class="col-md-3 control-label">Área DGIRE:</label>
			<div class="col-md-5">
				<select id="id_area" name="id_area" class="form-control">
				</select>
			</div>
			<div class="col-md-4"></div>
		</div>
		<div class="row form-group">
			<label class="col-md-3 control-label">Rol:</label>
			<div class="col-md-3">
				<select id="id_rol" name="id_rol" class="form-control">
				</select>
			</div>
			<div class="col-md-6"></div>
		</div>
		<div class="row form-group">
			<label class="col-md-3 control-label">Activar:</label>
			<div class="col-md-2">
				<select id="vigente" name="vigente" class="form-control">
					<option value="">-Seleccione-</option>
					<option value="1">SI</option>
					<option value="0">NO</option>
				</select>
			</div>
			<div class="col-md-7"></div>
		</div>
       
	</form>
    
	<div class="modal-footer">
		<button id="btnUpdUsuario" class="btn btn-outline btn-primary">Actualizar</button>
		<button id="btnSaveUsuario" class="btn btn-outline btn-primary">Guardar</button>
		<button id="btnCloseUsuario" class="btn btn-outline btn-danger" >Cancelar</button>
	</div>
</div>