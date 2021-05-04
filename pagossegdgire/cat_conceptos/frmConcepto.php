<div id="dvFrmConcepto" class="modal-body" style="display:none;">
		<form id="frmConcepto" name="frmConcepto"  data-toggle="validator">
			<div class="row form-group">
				<label class="col-md-3 control-label">Clave del concepto:</label>
				<div class="col-md-2">
					<input id="id_concepto_pago" name="id_concepto_pago" class="form-control" type="text">
				</div>
				<div class="col-md-7"></div>
			</div>
			<div class="row form-group">
				<label class="col-md-3 control-label">Nombre del concepto:</label>
				<div class="col-md-9">
					<input id="nom_concepto_pago" name="nom_concepto_pago" class="form-control" type="text">
				</div>
			</div>
			<div class="row form-group">
				<label class="col-md-3 control-label">Área correspondiente:</label>
				<div class="col-md-6">
					<select id="id_area" name="id_area" class="form-control">
						<option value="">Seleccione un área</option>
					</select>
				</div>
				<div class="col-md-3"></div>
			</div>
			<div class="row form-group">
				<label class="col-md-3 control-label">Tipo de concepto:</label>
				<div class="col-md-4">
					<select id="cve_tipo_con" name="cve_tipo_con" class="form-control">
						<option value="">Seleccione un concepto</option>
					</select>
				</div>
				<div class="col-md-5"></div>
			</div>
			<div class="row form-group">
				<label class="col-md-3 control-label">Costo variable:</label>
				<div class="col-md-2">
					<select id="costo_variable" name="costo_variable" class="form-control">
						<option value="0">No</option>
						<option value="1">Si</option>
					</select>
				</div>
				<div class="col-md-7"></div>
			</div>
			<div class="row form-group">
				<label class="col-md-3 control-label">Importe pesos:</label>
				<div class="col-md-3">
					<div class="input-group">
						<div class="input-group-addon">$</div>
						<input id="importe_pesos" name="importe_pesos" value="0" type="text" class="form-control" />
					</div>
				</div>
				<div class="col-md-6"></div>
			</div>
			<div class="row form-group">
				<label class="col-md-3 control-label">Número de SM:</label>
				<div class="col-md-2">
					<input id="importe_smdf" name="importe_smdf" class="form-control" value="0" type="text">
				</div>
				<div class="col-md-7"></div>
			</div>
			<div class="row form-group">
				<label class="col-md-3 control-label">Precio unitario en pesos:</label>
				<div class="col-md-3">
					<div class="input-group">
						<div class="input-group-addon">$</div>
						<input id="precio_unitario" name="precio_unitario" type="text" class="form-control" disabled />
					</div>
				</div>
				<div class="col-md-6"></div>
			</div>
			<div class="row form-group">
				<label class="col-md-3 control-label">Precio unitario en SM:</label>
				<div class="col-md-3">
					<div class="input-group">
						<div class="input-group-addon">$</div>
						<input id="precio_smdf" type="text" class="form-control" disabled />
					</div>
				</div>
				<div class="col-md-6"></div>
			</div>
			<div class="row form-group">
				<label class="col-md-3 control-label">Calcula IVA:</label>
				<div class="col-md-2">
					<select id="calcula_iva" name="calcula_iva" class="form-control">
						<option value="0">Tasa 0</option>
						<option value="1">Si</option>
						<option value="2">Exento</option>
					</select>
				</div>
				<div class="col-md-7"></div>
			</div>
			<div class="row form-group">
				<label id="lblIVA" class="col-md-3 control-label">IVA 16%:</label>
				<div class="col-md-3">
					<div class="input-group">
						<div class="input-group-addon">$</div>
						<input id="monto_iva" type="text" class="form-control" disabled />
					</div>
				</div>
				<div class="col-md-6"></div>
			</div>
			
			<div class="row form-group">
				<label class="col-md-3 control-label">Cuenta:</label>
				<div class="col-md-2">
					<input id="cuenta" name="cuenta" class="form-control" type="text">
				</div>
				<div class="col-md-7"></div>
			</div>
			<div class="row form-group">
				<label class="col-md-3 control-label">Activar:</label>
				<div class="col-md-2">
					<select id="vigente" name="vigente" class="form-control">
						<option value="1">Si</option>
						<option value="0">No</option>
					</select>
				</div>
				<div class="col-md-7"></div>
			</div>
        
		</form>
    
		<div class="modal-footer">
			<button id="btnSaveConcepto" class="btn btn-outline btn-primary">Guardar</button>
			<button id="btnUpdConcepto" class="btn btn-outline btn-primary">Actualizar</button>
			<button id="btnCancelConcepto" class="btn btn-outline btn-danger" >Cancelar</button>
		</div>
	</div>