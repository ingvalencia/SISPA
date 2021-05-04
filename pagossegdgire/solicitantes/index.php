<?php

/**************************************
script adicionales
**************************************/
$rd = rand();
$extraLib = <<<"EOD"
<script src="../js/download.jquery.js"></script>
<script src="../js/common_function.js"></script>
<script src="../js/solicitantes.js?$rd"></script>
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
                   
					<h3 class="page-header" style="Padding-left:15px; color:#2e82c4;">Datos de solicitantes</h3>

					<div class=" well col-lg-12">
                    
						<label class="col-md-1 control-label">Nombre:</label>
						<div class="col-md-5">
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

					<div class="text-right"><img id="btnExcel" src="../image/excel.png"></div>
						
                 
					<table id="solicitantes" name="solicitantes" class="table table-striped  table-hover table-condensed customTables display nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr class="text-center backgroundMarinoUNAM textWhite">
								<th>id</th>
								<th>Tipo</th>
								<th>Plantel</th>
								<th># Exp</th>
								<th>Nombre</th>
								<th>Ap paterno</th>
								<th>Ap materno</th>
								<th>Email</th>
								<th>Teléfono</th> 
								<th>Celular</th>
								<th>Registro</th>
								<th>Vigente</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="text-center backgroundMarinoUNAM textWhite">
								<th>id</th>
								<th>Tipo</th>
								<th>Plantel</th>
								<th># Exp</th>
								<th>Nombre</th>
								<th>Ap paterno</th>
								<th>Ap materno</th>
								<th>Email</th>
								<th>Teléfono</th> 
								<th>Celular</th>
								<th>Registro</th>
								<th>Vigente</th>
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

   