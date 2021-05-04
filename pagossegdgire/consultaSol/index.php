<?php

/**************************************
script adicionales
**************************************/
$rd = rand();
$extraLib = <<<"EOD"

<link href="../bower_components/datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="../bower_components/datepicker/js/bootstrap-datepicker.js" rel="stylesheet"></script>
<script src="../bower_components/datepicker/locales/bootstrap-datepicker.es.min.js" rel="stylesheet"></script>

<script src="../js/download.jquery.js"></script>
<script src="../js/common_function.js"></script>
<script src="../js/consultaSol.js?$rd"></script>
EOD;

include_once("../templete/header.php");
?>

    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">

        <?php
	include_once("../templete/navigator.php");
	include_once("../templete/menu.php");
?>

    </nav>


    <!-- inicia body -->
    <div id="page-wrapper">
        <!-- contenido -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    
					<h3 class="page-header" style="Padding-left:15px; color:#2e82c4;">Seguimiento administrador pagos</h3>

					
					
					<div id="dvGrid">
					
						<div class="well">
						
							<div class="row">
								<label class="col-md-3 text-right">Fecha solicitud de :</label>
								<div class="col-md-9 input-daterange input-group" id="datepicker">
									<input name="fec_sol1" id="fec_sol1" type="text" class="input-sm form-control search" placeholder="yyyy-mm-dd" readonly="">
									<span class="input-group-addon">al</span>
									<input name="fec_sol2" id="fec_sol2" type="text" class="input-sm form-control search" placeholder="yyyy-mm-dd" readonly="">
								</div>
							</div>
							
							<div class="row">
								<label class="col-md-3 text-right">Fecha actualización de :</label>
								<div class="col-md-9 input-daterange input-group" id="datepicker">
									<input name="fec_actualizacion1" id="fec_actualizacion1" type="text" class="input-sm form-control search" placeholder="yyyy-mm-dd" readonly="">
									<span class="input-group-addon">al</span>
									<input name="fec_actualizacion2" id="fec_actualizacion2" type="text" class="input-sm form-control search" placeholder="yyyy-mm-dd" readonly="">
								</div>
							</div>
							
							<div class="row">
								<label class="col-md-3 text-right">Fecha factura de :</label>
								<div class="col-md-9 input-daterange input-group" id="datepicker">
									<input name="fec_factura1" id="fec_factura1" type="text" class="input-sm form-control search" placeholder="yyyy-mm-dd" readonly="">
									<span class="input-group-addon">al</span>
									<input name="fec_factura2" id="fec_factura2" type="text" class="input-sm form-control search" placeholder="yyyy-mm-dd" readonly="">
								</div>
							</div>
							
							<div class="row">
								<label class="col-md-3 text-right">Folio de :</label>
								<div class="col-md-9 input-daterange input-group" id="datepicker">
									<input name="folio_sol1" id="folio_sol1" type="text" class="input-sm form-control search" placeholder="99999999999" >
									<span class="input-group-addon">al</span>
									<input name="folio_sol2" id="folio_sol2" type="text" class="input-sm form-control search" placeholder="99999999999" >
								</div>
							</div>
							
							<div class="row">
								<label class="col-md-3 text-right">Conceptos :</label>	
								<div class="col-md-9">
									<select id="id_concepto_pago" name="id_concepto_pago" class="text-left form-control search">
										<option value="">Seleccione...</option>
									</select>
								</div>
							</div>
							
							<div class="row">
								<label class="col-md-3 text-right">Estado de trámite :</label>	
								<div class="col-md-9">
									<select id="cve_edo_sol" name="cve_edo_sol" class="text-left form-control search">
										<option value="">Seleccione...</option>
									</select>
								</div>
							</div>
							
							<div class="row">
								<label class="col-md-3 text-right">Estado de pago :</label>	
								<div class="col-md-9">
									<select id="cve_edo_pago" name="cve_edo_pago" class="text-left form-control search">
										<option value="">Seleccione...</option>
									</select>
								</div>
							</div>
							
							<div class="row">
								<label class="col-md-3 text-right">Departamento :</label>	
								<div class="col-md-9">
									<select id="id_area" name="id_area" class="text-left form-control search">
										<option value="">Seleccione...</option>
									</select>
								</div>
							</div>
							
							<br>
							<div class="row text-center">
								
					
								<div aling="center">
									<button id="btnSearch" class="btn btn-outline btn-primary pull-right btn-sm"><i class="fa fa-search fa-fw"></i> Buscar</button>
									<button id="btnClear" class="btn btn-outline btn-primary pull-right btn-sm"><i class="fa fa-eraser fa-fw"></i> Limpiar</button>
								</div>
	
							</div>
						</div>
						
						<div class="text-right"><img id="btnExcel" src="../image/excel.png"></div>
						
						<table id="solicitudes" name="solicitudes" class="table table-striped  table-hover table-condensed customTables display nowrap" cellspacing="0" width="100%">
							<thead>
								<tr class="text-center backgroundMarinoUNAM textWhite">
									<th>Folio</th>
									<th>Clave</th>
									<th>Cuenta</th>
									<th>Concepto</th>
									<th>Importe</th>
									<th>Cantidad</th> 
									<th>Monto</th>
									<th>IVA</th>
									<th>Precio unit</th>
									<th>Estado pago</th>
									<th>Ref Bancaria</th>
									<th>Fc Solicitud</th>
									<th>Actualización estado</th>
									<th>Fc Factura</th>
									<th>Fc Entrega</th>
									<th>Solicitante</th>
									<th>Entregado</th>
									<th>Responsable</th>
									<th>Área responsable</th>
									<th>Plantel</th>
									<th># Factura</th>
									<th># Ticket</th>
								</tr>
							</thead>
							<tfoot>
								<tr class="text-center backgroundMarinoUNAM textWhite">
									<th>Folio</th>
									<th>Clave</th>
									<th>Cuenta</th>
									<th>Concepto</th>
									<th>Importe</th>
									<th>Cantidad</th> 
									<th>Monto</th>
									<th>IVA</th>
									<th>Precio unit</th>
									<th>Estado pago</th>
									<th>Ref Bancaria</th>
									<th>Fc Solicitud</th>
									<th>Actualización estado</th>
									<th>Fc Factura</th>
									<th>Fc Entrega</th>
									<th>Solicitante</th>
									<th>Entregado</th>
									<th>Responsable</th>
									<th>Área responsable</th>
									<th>Plantel</th>
									<th># Factura</th>
									<th># Ticket</th>
								</tr>
							</tfoot>
							<tbody>
							</tbody>
						</table>
				
						
					</div>
					
					
					
					
					
                </div>
            </div>
        </div>





        <!-- fin contenido -->
    </div>
    <!-- fin body -->

    