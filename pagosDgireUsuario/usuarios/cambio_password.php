<?php

/**************************************
script adicionales
**************************************/
$extraLib = <<<'EOD'
<script src="../js/bootstrap-dialog.min.js"></script>
<script src="../js/common_function.js"></script>
<script src="../js/cambio_password.js"></script>
EOD;


include_once("../header.php");

?>


<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">

<?php
	include_once("../navigator.php");
	include_once("../menu.php");
?>
			        
</nav>

<!-- inicia body -->
<div id="page-wrapper">
    <div class="container-fluid">
	<!-- contenido -->
	
	
	
	
	
	



					   <div class="row">
                            <div class="col-lg-12">
                                <h1 id="hTitle" class="page-header">Cambio de contraseña</h1>
                            </div>
                            <!-- /.col-lg-12 -->
                        </div>
                        
						<!--
						<form id="frmUsuario" name="frmUsuario" data-toggle="validator" >
                               
								<div class="form-group">
                                    <label class="control-label">Nombre completo del usuario</label>
                                    <input type="text" name="nombre_usr" id="nombre_usr" class="form-control">
                                </div>
							-->	
                        <div class="input-group col-lg-6">
                        	<form id="frmPassword" name="frmPassword" data-toggle="validator">
								<div class="form-group">
									<label class="control-label">Nueva contraseña</label>
									<input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" />
								</div>
								<br/>
								<br/>
								<div class="form-group">
									<label class="control-label">Confirmar contraseña</label>
									<input type="password" class="form-control" id="confirm_password" name="confirm_password"  placeholder="Confirmar contraseña" />
								</div>
                            </form>
						</div>
                        <button id="btnSave" class="btn btn-primary">Cambiar</button>
						<button id="btnCancelar" class="btn btn-danger">Cancelar</button>
                        <!-- /.row -->
    
    
    
    
    
	
	
	<!-- fin contenido -->
	</div>
	<br>
	<br>
</div>
<!-- fin body -->
        	
<?php include_once("../footer.php"); ?>


























