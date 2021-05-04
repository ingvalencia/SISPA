<?php

$rd = rand();

/**************************************
script adicionales
**************************************/
$extraLib = <<<"EOD"
<script src='../js/download.jquery.js'></script>
<script src='../js/common_function.js'></script>
<script src='../js/ct_conceptos_pago.js?$rd'></script>
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
                    
					<h3 class="page-header" style="Padding-left:15px; color:#2e82c4;">Administración de claves de conceptos de pago</h3>

					<?php 
						include_once("frmConcepto.php");
					?>
					
					<div id="dvGrid">
						<div class=" well col-lg-12">
						
							<label class="col-md-1 control-label">Clave:</label>
							<div class="col-md-2">
								<input id="sid_concepto_pago" name="sid_concepto_pago" class="form-control" type="text">
							</div>
							<label class="col-md-1 control-label">Área:</label>
							<div class="col-md-6">
								<select id="sid_area" name="sid_area" class="form-control">
									<option value="">Seleccione un área</option>
								</select>
							</div>
							<div class="col-md-2">
							<button type="button" id="btnSearch" name="btnSearch" class="btn btn-outline btn-primary">Buscar</button>
							</div>
						</div>
	
						<div class="row">
							<div class="col-md-6" align="left">
								
								<button type="button" id="btnSaveExcel" name="btnSaveExcel" class="btn btn-outline btn-success"><img src="../image/excel.png" /></button>
								
								<!--
								<button type="button" id="btnSaveExcel" name="btnSaveExcel" class="btn btn-outline btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
								-->
							</div>
							<div class="col-md-6" align="right">
								&nbsp;
								<button type="button" id="btnAddConcepto" name="btnAddConcepto" class="btn btn-outline btn-primary">Agregar concepto</button>
							</div>
						</div>
						
	
						<table id="ct_conceptos_pago" name="ct_conceptos_pago" class="table table-striped table-bordered table-hover table-condensed customTables display nowrap" cellspacing="0" width="100%">	
							<thead>
								<tr class="text-center backgroundMarinoUNAM textWhite">
									<th>Clave</th>
									<th>Nombre área</th>
									<th>Nombre concepto pago</th>
									<th>Tipo concepto</th>
									<th>Importe</th>
									<th># smdf</th>
									<th>Precio unitario</th> 
									<th>IVA</th>
									<th>IVA <label id="th_iva">gdf</label></th>
									<th>Costo variable</th>
									<th>Cuenta</th>
									<th>Activo</th>
									<th>Fecha de actualización</th>
								</tr>
							</thead>
							<tfoot>
								<tr class="text-center backgroundMarinoUNAM textWhite">
									<th>Clave</th>
									<th>Nombre área</th>
									<th>Nombre concepto pago</th>
									<th>Tipo concepto</th>
									<th>Importe</th>
									<th># smdf</th>
									<th>Precio unitario</th> 
									<th>IVA</th>
									<th>IVA</th>
									<th>Costo variable</th>
									<th>Cuenta</th>
									<th>Activo</th>
									<th>Fecha de actualización</th>
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

    