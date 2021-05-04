<div id="dvFrmUsuario" style="display:none;">
    <div id="dvGridR">

        <div class="row">

			<div class="col-md-1">
				<div class="row text-center">
					<label>Clave</label>
				</div>
				<div class="row">
					<input id="clave" name="clave" maxlength="3" class="form-control">
				</div>
			</div>
			
			<div class="col-md-7">
				<div class="row text-center">
					<label>Concepto</label>
				</div>
				<div class="row">
					<select id="lst_conceptos" name="lst_conceptos" class="form-control">
						<option value="">Seleccione un concepto...</option>
					</select>
				</div>
			</div>
			
			<div class="col-md-1">
				<div class="row text-center">
					<label>Cantidad</label>
				</div>
				<div class="row">
					<input id="cantidad" class="form-control" disabled>
				</div>
			</div>
			
			<div class="col-md-2">
				<div class="row text-center">
					<label>Monto</label>
				</div>
				<div class="row">
					<div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input type="text" class="form-control" id="precio_unitario" disabled>
                    </div>
				</div>
			</div>
			
			<div class="col-md-1">
			
				<div class="row">
					&nbsp;
				</div>
				<div class="row">
					<button id="btnAddConcepto" class="btn btn-outline btn-primary pull-right btn-sm" disabled><span class="glyphicon glyphicon-plus"></span> agregar</button>
				</div>
			
			</div>

        </div>

        

        <form id="frmUsuario" name="frmUsuario" data-toggle="validator">
            <div class="table-responsive">
                <table id="tSolicitud" name="tSolicitud" class="table table-striped table-bordered table-hover table-condensed customTables display nowrap" cellspacing="0" width="100%">
                    <thead>

                        <tr>
                            <td colspan="8" class="backgroundTitle">
                                <div align="center" class="textWhite">Conceptos agregados</div>
                            </td>
                        </tr>

                        <tr class="text-center backgroundMarinoUNAM textWhite">
                            <td>Clave</td>
                            <td>Concepto</td>
                            <td>importe</td>
                            <td>Cantidad</td>
                            <td>Precio unit</td>
                            <td>IVA</td>
                            <td>Monto</td>
                            <td></td>
                        </tr>
                    </thead>

                    <tbody id="xbody">
                    </tbody>

                    <tfoot>
                        <tr class="text-center">
                            <td colspan="8">
                                <table class="customTables display nowrap table-striped table-hover" align="right" cellpadding="0" width="200">
                                    <tfoot>
                                        <tr class="backgroundMarinoUNAM textWhite">

                                            <td width="800" colspan="5" align="right" class="ui-corner-all">Sub Total</td>
                                            <td width="100" align="right"><span id="stotal">$0.00</span></td>

                                        </tr>
                                        <tr class="">
                                            <td colspan="5" align="right" class="ui-corner-all">I.V.A</td>
                                            <td align="right"><span id="ivat">$0.00</span></td>

                                        </tr>
                                        <tr class="backgroundMarinoUNAM textWhite">
                                            <td colspan="5" align="right" class="ui-corner-all">Monto Total</td>
                                            <td align="right"><span id="mtotal">$0.00</span></td>

                                        </tr>
                                    </tfoot>
                                </table>
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </form>

        <!--Opción factura -->

        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <label><b>Marque la casilla de verificación si desea facturar</b></label>&nbsp;
					<input type="checkbox" id="chFactura" name="chFactura"></input>
                </div>
				<div id="dvFactura" class="panel-body" style="display:none;">
					<?php 
						include_once("rfactura.php"); 
					?>
				</div>
				
            </div>
        </div>


        <div class="modal-footer">
            <button id="btnRegistrar" name="btnRegistrar" class="btn btn-outline btn-primary"><span class="glyphicon glyphicon-log-out"></span> Registrar</button>
            <button id="btnCloseUsuario" class="btn btn-outline btn-danger"><span class="glyphicon glyphicon-remove"></span> Cancelar</button>

        </div>
    </div>
    <!-- Termina grid -->

</div>

<!--Mensaje Registro exitoso -->
<div id="dvConfirm" style="display:none;">
    <div class="alert alert-info">
        <div class="row">
            <div class="col-lg-12">
                <h3 style="Padding-left:15px; color:#2e82c4;"> Registro Exitoso</h3>
            </div>

            <p align="justify" style="margin-left:30px;">
                Le confirmamos que su solicitud se ha registrado satisfactoriamente.
                <br><br> Con el número de folio: <label id="folio_sol" name="folio_sol"></label>
				<br><br> Monto de pago: <label id="monto_tot_conc" name="monto_tot_conc"></label>
                <br><br> Referencia bancaria: <label id="referencia_ban" name="referencia_ban"></label>
				<br><br> La ficha de su depósito fue enviada por correo electrónico o si lo prefiere podrá descargarla dando click en el botón <label>"Ficha de depósito"</label>. 
            </p>

        </div>
    </div>


	
	<div class="col-lg-2 col-lg-offset-2">
			
		<button name="btnFichad" id="btnFichad" class="btn btn-outline btn-primary center-block" >
			<span class="glyphicon glyphicon-save-file"></span> Ficha de depósito
		</button>
	
	</div>
	
	<div class="col-lg-3 col-lg-offset-3">
	
		<button id="btnCerrar" name="btnCerrar" class="btn btn-outline btn-primary center-block ">
			<span class="glyphicon glyphicon-edit"></span> Nueva solicitud
		</button>
	
	</div>


</div>
<!-- Termina row -->