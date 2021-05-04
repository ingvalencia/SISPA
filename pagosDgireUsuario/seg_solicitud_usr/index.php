<?php

/**************************************
script adicionales
**************************************/
$ran = rand();
$extraLib = <<<"EOD"
<script src="../js/download.jquery.js"></script>
<script src="../js/common_function.js"></script>
<script src="../js/seg_solicitud_usr.js?$ran"></script>

EOD;

include_once("../templete/header.php");
require_once("../acceso/inactividad.php");
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
                    
					 <h4 class="page-header" style="Padding-left:10px; color:#2e82c4;"><b>Seguimiento de solicitud de pago</b></h3>

					<div id="dvGrid">
					
						<div class="well">

							<div class="row">
								
								<label class="col-md-1">Folio:</label>
								
								<div class="col-md-3">
									<input id="folio_sol" name="folio_sol"  class="form-control">
								</div>
	
								<label class="col-md-2">Estado del pago:</label>
	
								<div class="col-md-4">
									<select id="cve_edo_pago" name="cve_edo_pago" class="form-control">
										<option value="">Seleccione...</option>
									</select>
								</div>
	
								<div class="col-md-2">	                                           
									<button id="btnSearch" class="btn btn-outline btn-primary pull-right btn-sm"><i class="fa fa-search fa-fw"></i> Buscar</button>
								</div>
	
							</div>
						</div>
						
						<div>
							<button id="btnClear" class="btn btn-outline btn-primary pull-right btn-sm"><i class="fa fa-refresh fa-fw"></i> Refrescar</button>
							<button id="btnCHPago" class="btn btn-outline btn-primary pull-right btn-sm" style="display:none"><i class="fa fa-refresh fa-fw"></i> checar pago</button>
						</div>
						
						<div > 
							<table id="solicitudes" name="solicitudes" class="table table-striped  table-hover table-condensed customTables display nowrap" cellspacing="0" width="100%">
											
								<thead>
									<tr>
											<td colspan="12" class="backgroundTitle">
												<div align="center"class="textWhite">  Solicitudes registradas</div>
											</td>
									 </tr>
									
									<tr class="text-center backgroundMarinoUNAM textWhite">
										<th>Folio</th>
										<th>Monto</th>
										<th>Estado pago</th>
										<th>RFC</th>
										<th>Actualización estado</th>
										<th>Fecha de entrega</th>
										<th>Comentarios</th> 
										<th>Ref Bancaria</th>
									</tr>
								</thead>
								<tfoot>
									<tr class="text-center backgroundMarinoUNAM textWhite">
										<th>Folio</th>
										<th>Monto</th>
										<th>Estado pago</th>
										<th>RFC</th>
										<th>Actualización estado</th>
										<th>Fecha de entrega</th>
										<th>Comentarios</th> 
										<th>Ref Bancaria</th>
									</tr>
								</tfoot>
								<tbody>
								</tbody>
							</table>
						</div>
						
						<div  id="dvDetalle" style="display:none;" class="row">
										<table id="detalles" name="detalles" class="table table-striped  table-bordered table-inverse customTables display nowrap" cellspacing="0" width="100%">	
												<thead>
				
													<tr>
														<td colspan="12" class="backgroundTitle">
															<div align="center"class="textWhite"> Conceptos detalle de la solicitud</div>
														</td>
													</tr>
				
													<tr class="text-center backgroundMarinoUNAM textWhite">
														<th>Clave</th>
														<th>Entregado</th>
														<th>Concepto</th>
														<th>Área</th>
														<th>Importe</th>
														<th>Cantidad</th>
														<th>Precio unit.</th> 
														<th>IVA</th>
														<th>Total</th>
													</tr>
												</thead>
				
												<tbody id="xbody">
												</tbody>
												
										</table>
										
										<div align="right">
											<table class="customTables display nowrap table-striped table-hover" align="right" cellpadding="0"  width="200" >
													<tfoot>
														
														<tr class="backgroundMarinoUNAM textWhite">
															<td width="800" colspan="5" align="right" class="ui-corner-all">Sub Total</td>
															<td width="100" align="right"><span id="monto_total_sin_iva">$0.00</span></td>							
														</tr>
														
														<tr class="">
															<td colspan="5" align="right" class="ui-corner-all">I.V.A</td>
															<td align="right"><span id="monto_total_iva">$0.00</span></td>						
														</tr>
														
														<tr class="backgroundMarinoUNAM textWhite">
															<td colspan="5" align="right" class="ui-corner-all">Monto Total</td>
															<td align="right"><span id="monto_total">$0.00</span></td>
														</tr>
														
													</tfoot>
											</table>
										</div>
						</div>	
					</div>
                </div>
            </div>
        </div>
        <!-- fin contenido -->
    </div>
    <!-- fin body -->

   
