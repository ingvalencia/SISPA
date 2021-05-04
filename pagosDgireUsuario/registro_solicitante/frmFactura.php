
<section class="registro-form">

	<div class="panel panel-default">
		<div class="panel-heading"><b>Datos de facturación</b></div>
			<div class="panel-body">
			                      
				<div class="row form-group">
					<label class="control-label col-xs-3">Tipo de persona:</label>
					<div class="col-lg-6">
						<select id="tipo_persona" name="tipo_persona" class="form-control" >
							<option value="">Seleccione ... </option>
							<option value="1">F&iacute;sica</option>
							<option value="2">Moral</option>
						</select>
					</div>
				</div>
							
				<div class="row form-group">
					<label class="control-label col-xs-3">RFC:</label>
					<div class="col-lg-6">
						<input type="text" id="rfc" name="rfc" size="20" maxlength="13" class="form-control" >
					</div>
				</div>

				<div class="row fisica form-group">
					<label class="control-label col-xs-3">Nombre:</label>
					<div class="col-lg-6">
						<input type="text" id="fnombre" name="fnombre" class="form-control letras" maxlength="30" placeholder="Nombre" >
					</div>
				</div>

				<div class="row fisica form-group">
					<label class="control-label col-xs-3">Apellido paterno:</label>
					<div class="col-lg-6">
						<input type="text" id="fap_paterno" name="fap_paterno" class="form-control letras" maxlength="30" placeholder="Apellido paterno" >
					</div>
				</div>

				<div class="row fisica form-group">
					<label class="control-label col-xs-3">Apellido materno:</label>
					<div class="col-lg-6">
						<input type="text" id="fap_materno" name="fap_materno" class="form-control letras" maxlength="30" placeholder="Apellido materno" >
					</div>
				</div>

				<div class="row moral form-group">
					<label class="control-label col-xs-3">Razón social:</label>
					<div class="col-lg-6">
						<input type="text" id="nombre_fisc" name="nombre_fisc" class="form-control letras" maxlength="30" >
					</div>
				</div>

				<div class="row form-group">
					<label class="control-label col-xs-3">Calle:</label>
					<div class="col-lg-6">
						<input type="text" id="calle" name="calle" class="form-control " maxlength="80" >
					</div>
				</div>

				<div class="row form-group">
					<label class="control-label col-xs-3">Número exterior.:</label>
					<div class="col-lg-6">
						<input type="text" id="num_ext" name="num_ext" class="form-control" >
					</div>
				</div>

				<div class="row form-group">
					<label class="control-label col-xs-3">Número interior.:</label>
					<div class="col-lg-6">
						<input type="text" id="num_int" name="num_int" class="form-control" >
					</div>
				</div>

				<div class="row form-group">
					<label class="control-label col-xs-3">Código postal:</label>
					<div class="col-lg-6">
						<input type="text" id="id_cp" name="id_cp" class="form-control" >
					</div>
				</div>

				<div class="row form-group">
					<label class="control-label col-xs-3">Estado:</label>
					<div class="col-lg-6">
						<input type="text" id="nom_edo" name="id_edo" class="form-control" disabled>
						<input type="text" id="id_edo" name="id_edo" class="form-control" style="display:none;" >
					</div>
				</div>

				<div class="row form-group">
					<label class="control-label col-xs-3">Ciudad:</label>
					<div class="col-lg-6">
						<input type="text" id="id_ciudad" name="id_ciudad" class="form-control" style="display:none;" >
						<input type="text" id="nom_ciudad" name="nom_ciudad" class="form-control" disabled >
					</div>
				</div>

				<div class="row form-group">
					<label class="control-label col-xs-3">Municipio:</label>
					<div class="col-lg-6">
						<input type="text" id="id_municipio" name="id_municipio" class="form-control" style="display:none;" >
						<input type="text" id="nom_municipio" name="nom_municipio" class="form-control" disabled >
					</div>
				</div>

				<div class="row form-group">
					<label class="control-label col-xs-3">Colonia:</label>
					<div class="col-lg-6">
						<select id="id_colonia" name="id_colonia" class="form-control" ></select>
					</div>
				</div>

				<div class="row form-group">
					<label class="control-label col-xs-3">¿No aparece su colonia?:</label>
					<div class="col-lg-6">
						<input type="checkbox" id="chColonia" name="chColonia" value="1">
					</div>
				</div>

				<div id="dvColonia" class="row form-group">
					<label class="control-label col-xs-3">Escriba su colonia:</label>
					<div class="col-lg-6">
						<input type="text" id="colonia_otra" name="colonia_otra" class="form-control letras" maxlength="80" >
					</div>
				</div>
		                            
			</div>
	</div>
		                	                
</section>
    		
