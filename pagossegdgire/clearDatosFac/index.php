<?php

/**************************************
script adicionales
**************************************/
$rd = rand();
$extraLib = <<<"EOD"
<script src='../js/common_function.js?$rd'></script>
<script src='../js/clearDatosFacturacion.js?$rd'></script>
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
                    
					<h3 class="page-header" style="Padding-left:15px; color:#2e82c4;">Datos de facturación</h3>

					<div class=" well col-lg-12">
                    
						<label class="col-md-1 control-label">RFC:</label>
						<div class="col-md-2">
							<input id="rfc" name="rfc" class="form-control" type="text">
						</div>
						<label class="col-md-1 control-label">Nombre:</label>
						<div class="col-md-2">
							<input id="fnombre" name="fnombre" class="form-control" type="text">
						</div>
						<label class="col-md-1 control-label">Correo:</label>
						<div class="col-md-3">
							<input id="fcorreo_e" name="fcorreo_e" class="form-control" type="text">
						</div>
						<div class="col-md-1">
		                   <button type="button" id="btnSearch" name="btnSearch" class="btn btn-outline btn-primary">Buscar</button>
						</div>
					</div>

						<div class="row">
							<div class="col-md-6" align="left">
							</div>
							<div class="col-md-6" align="right">
								&nbsp;
								<button type="button" id="btnDeleteRFC" name="btnDeleteRFC" class="btn btn-outline btn-danger" style="display:none;">Eliminar</button>
							</div>
						</div>

						<table id="datos_facturacion" name="datos_facturacion" class="table table-striped  table-hover table-condensed customTables display nowrap" cellspacing="0" width="100%">
							<thead>
								<tr class="text-center backgroundMarinoUNAM textWhite">
									<th>id sol</th>
									<th>Correo</th>
									<th>RFC</th>
									<th>Nombre fiscal</th>
									<th>Calle</th>
									<th>Num. ext.</th>
									<th>Num. int.</th>
									<th>Cod. Postal</th> 
									<th>Estado</th>
									<th>Municipio</th>
									<th>Ciudad</th>
									<th>Colonia</th>
									<!--
									<th>Eliminar</th>
									-->
								</tr>
							</thead>
							<tfoot>
							   <tr class="text-center backgroundMarinoUNAM textWhite">
									<th>id sol</th>
									<th>Correo</th>
									<th>RFC</th>
									<th>Nombre fiscal</th>
									<th>Calle</th>
									<th>Num. ext.</th>
									<th>Num. int.</th>
									<th>Cod. Postal</th> 
									<th>Estado</th>
									<th>Municipio</th>
									<th>Ciudad</th>
									<th>Colonia</th>
									<!--
									<th>Eliminar</th>
									-->
								</tr>
							</tfoot>
							<tbody>
							</tbody>
                    </table>
				
                </div>
            </div>
        </div>





        <!-- fin contenido -->
    </div>
    <!-- fin body -->

    