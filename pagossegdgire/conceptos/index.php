<?php

$rd = rand();
/**************************************
script adicionales
**************************************/
$extraLib = <<<"EOD"
<script src="../js/download.jquery.js"></script>
<script src="../js/common_function.js"></script>
<script src="../js/administrador_area.js?$rd"></script>
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
                    
					<h3 class="page-header" style="Padding-left:15px; color:#2e82c4;">Seguimiento administrador área</h3>

					
					<div id="dvGrid">
						<div class=" well col-lg-12">
							<label class="col-md-1 control-label">Folio:</label>
							<div class="col-md-2">
								<input id="folio_sol" name="folio_sol" class="form-control" type="text">
							</div>
							<label class="col-md-1 control-label">Nombre:</label>
							<div class="col-md-2">
								<input id="fnombre" name="fnombre" class="form-control" type="text">
							</div>
							<label class="col-md-2 control-label">Estado de pago:</label>
							<div class="col-md-2">
								<select id="cve_edo_pago" name="cve_edo_pago" class="form-control">
									<option value="">Seleccione un área</option>
								</select>
							</div>
							<div class="col-md-2">
							<button type="button" id="btnSearch" name="btnSearch" class="btn btn-outline btn-primary">Buscar</button>
							</div>
						</div>
		
						<div class="text-right"><img id="btnExcel" src="../image/excel.png"></div>
           
		   
						<div class="">
							<table id="detalles" name="detalles" class="table table-striped  table-hover table-condensed customTables display nowrap" cellspacing="0" width="100%">
								<thead>
									<tr class="text-center backgroundMarinoUNAM textWhite  ">
										<th>Id sol</th>
										<th>Folio</th>
										<th>Clave ISI</th>
										<th>Concepto</th>
										<th>Nombre concepto</th>
										<th>Cantidad</th> 
										<th>Monto</th>
										<th>IVA</th>
										<th>Precio unit</th>
										<th>Solicitante</th>
										<th>Estado pago</th>
										<th># Factura</th>
										<th># Ticket</th>
										<th>Entregado</th>
										<th>Comentario</th>
									</tr>
								</thead>
								<tfoot>
									<tr class="text-center backgroundMarinoUNAM textWhite ">
										<th>Id sol</th>
										<th>Folio</th>
										<th>Clave ISI</th>
										<th>Concepto</th>
										<th>Nombre concepto</th>
										<th>Cantidad</th> 
										<th>Monto</th>
										<th>IVA</th>
										<th>Precio unit</th>
										<th>Solicitante</th>
										<th>Estado pago</th>
										<th># Factura</th>
										<th># Ticket</th>
										<th>Entregado</th>
										<th>Comentario</th>
									</tr>
								</tfoot>
								<tbody>
								</tbody>
							</table>
						</div>	
					</div>
					
					
                </div>
            </div>
        </div>





        <!-- fin contenido -->
    </div>
    <!-- fin body -->
