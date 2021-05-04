<?php

$rd = rand();
/**************************************
script adicionales
**************************************/
$extraLib = <<<"EOD"
<script src="../libs/datetimepicker/moment.js"></script>
<link href="../libs/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="../libs/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="../js/common_function.js?$rd"></script>
<script src="../js/parametros.js?$rd"></script>
EOD;


include_once("../templete/header.php");
?>

    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">

<?php
	include_once("../templete/navigator.php");
    include_once("../templete/menu.php");
	
	
	
	
//limpiamos sesion para esta seccion unicamente 
$_SESSION["valid_parametros"]=0;
unset($_SESSION["valid_parametros"]);
$_SESSION["valid_parametros"]=1;
	
?>

    </nav>


	
    <!-- inicia body -->
    <div id="page-wrapper">
        <!-- contenido -->

		
		<!-- Modal -->
		<div class="modal fade" id="modalPassword" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="bootstrap-dialog type-primary modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="gridSystemModalLabel">Verificar identidad</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-2">Password</div>
							<div class="col-md-5">
								<input type="password" id="passwd" name="passwd" class="form-control" type="text">
							</div>
							<div class="col-md-5"></div>
						</div>
						<div class="row">
							<div class="col-md-2"></div>
							<div class="col-md-5">
								<span id="msg_passwd"></span>
							</div>
							<div class="col-md-5"></div>
						</div>
					</div>
					<div class="modal-footer">
						<button id="btnLogin" class="btn btn-outline btn-primary col-md-12">Login</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		
		
		
		
		
		
		
		
		
		
		<div class="modal fade" id="modalDate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display:none;">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modalDate_title"></h4>
				</div>
				<div class="modal-body">
					
					<div class="col-sm-12">
						
						<div class="col-sm-4">
							<label id="modalDate_data"></label>
						</div>
						
						<div class="input-group col-sm-5">
							<input id="dTime" name="dTime" type="text" class="form-control input-small">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
						</div>
						
					</div>
					
					<br>
					<br>
					
					<div class="col-sm-12 clsError" id="msg_error_horario"> </div>
					
					
				</div>
				<div class="modal-footer">
					<button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
					<button id="btnUpdate" name="btnUpdate" class="btn btn-primary" >Actualizar</button>
				</div>
				</div>
			</div>
		</div>
		
		
		
		
		
		<div class="modal fade" id="modalDate_temp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display:none;">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modalDate_temp_title"></h4>
				</div>
				<div class="modal-body">
					
					<!--
					<div class="row">
						
						<div class="input-group col-sm-3">
							<input id="dTime_t1" name="dTime_t1" type="text" class="form-control input-small">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
						</div>
						
						<div class="input-group col-sm-3">
							<input id="dTime_t2" name="dTime_t2" type="text" class="form-control input-small">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
						</div>
						
					</div>
					-->


					<div class="input-group input-daterange col-sm-8 col-sm-offset-2">
					    <input id="dTime_t1" name="dTime_t1" type="text" class="form-control input-small">
					    <div class="input-group-addon">a</div>
					    <input id="dTime_t2" name="dTime_t2" type="text" class="form-control input-small">
					</div>

					
					<br>
					<br>
					
					<div class="col-sm-12 clsError" id="msg_error_horario"> </div>
					
					
				</div>
				<div class="modal-footer">
					<button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
					<button id="btnUpdate_temp" name="btnUpdate_temp" class="btn btn-primary" >Actualizar</button>
				</div>
				</div>
			</div>
		</div>
		
		
		
		
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Administración de parametros</h1>

					<div id="dvParametros" style="display:none;">
					
						<div class="row">
							<label class="col-md-3 control-label">cveserie:</label>
							<div class="col-md-2">
								<a href="#" id="cveserie" name="cveserie" class="cls_cveserie" ></a>
							</div>
							<div class="col-md-7"></div>
						</div>
						
						<div class="row">
							<label class="col-md-3 control-label">iva:</label>
							<div class="col-md-2">
								<a href="#" id="iva" name="iva" class="cls_iva"></a>
							</div>
							<div class="col-md-7"></div>
						</div>
						
						<div class="row">
							<label class="col-md-3 control-label">smdf:</label>
							<div class="col-md-2">
								<a href="#" id="smdf" name="smdf" class="cls_smdf"></a>
							</div>
							<div class="col-md-7"></div>
						</div>
						
						<div class="row">
							<label class="col-md-3 control-label">Cierre temporal:</label>
							<div class="col-md-4">
								<a href="#" class="tmp_sistema">
								<span id="apertura_sistema_temporal" name="apertura_sistema_temporal"></span> 
								<strong>al</strong>
								<span id="cierre_sistema_temporal" name="cierre_sistema_temporal"></span> 
								</a>
							</div>
							<div class="col-md-5"></div>
						</div>
						
						<div class="row">
							<label class="col-md-3 control-label">Apertura del sistema:</label>
							<div class="col-md-2">
								<a href="#" id="apertura_sistema" name="apertura_sistema"></a> 
							</div>
							<div class="col-md-7"></div>
						</div>
						
						<div class="row">
							<label class="col-md-3 control-label">Cierre del sistema:</label>
							<div class="col-md-2">
								<a href="#" id="cierre_sistema" name="cierre_sistema"></a> 
							</div>
							<div class="col-md-7"></div>
						</div>
						
	
					</div>
		
                </div>
            </div>
        </div>

        <!-- fin contenido -->
    </div>
    <!-- fin body -->

   