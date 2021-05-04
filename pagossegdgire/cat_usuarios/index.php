<?php

$rd = rand();

/**************************************
script adicionales
**************************************/
$extraLib = <<<'EOD'
<script src="../js/download.jquery.js"></script>
<script src="../js/common_function.js?$rd"></script>
<script src="../js/ct_usuarios.js?$rd"></script>
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
                   
					<h3 class="page-header" style="Padding-left:15px; color:#2e82c4;">Administración de usuarios</h3>


					<?php
						include_once("frmUsuario.php");
					?>
		
					<div id="dvGrid">
					
						<div class=" well col-lg-12">
						
							<label class="col-md-1 control-label">Nombre:</label>
							<div class="col-md-3">
								<input id="snombre" name="snombre" class="form-control" type="text">
							</div>
							<label class="col-md-1 control-label">Área:</label>
							<div class="col-md-5">
								<select id="sid_area" name="sid_area" class="form-control">
									<option value="">Seleccione un área</option>
								</select>
							</div>
							<div class="col-md-1">
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
								<button type="button" id="btnAddUsuario" name="btnAddUsuario" class="btn btn-outline btn-primary">Agregar usuario</button>
							</div>
						</div>
						
						<table id="ct_usuarios" name="ct_usuarios" class="table table-striped  table-hover table-condensed customTables display nowrap" cellspacing="0" width="100%">
							<thead>
								<tr class="text-center backgroundMarinoUNAM textWhite">
									<th>id usuario</th>
									<th>Login</th>
									<th>Nombre</th>
									<th>Ap. paterno</th>
									<th>Ap. materno</th>
									<th>Activo</th>
									<th>Área perteneciente</th> 
									<th>Rol</th>
									<th>Edit password</th>
								</tr>
							</thead>
							<tfoot>
								<tr class="text-center backgroundMarinoUNAM textWhite">
									<th>id usuario</th>
									<th>Login</th>
									<th>Nombre</th>
									<th>Ap. paterno</th>
									<th>Ap. materno</th>
									<th>Activo</th>
									<th>Área perteneciente</th> 
									<th>Rol</th>
									<th>Edit password</th>
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

   