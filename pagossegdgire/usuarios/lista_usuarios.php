<?php

/**************************************
script adicionales
**************************************/
$extraLib = <<<'EOD'
<script src="../js/bootstrap-dialog.min.js"></script>
<script src="../js/common_function.js"></script>
<script src="../js/lista_usuarios.js"></script>
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
				<h1 class="page-header">Lista de usuarios</h1>
			</div>
		</div>
	
	<div align="right">
    	&nbsp;
    	<button type="button" id="btnUpdatePassword" name="btnUpdatePassword" class="btn btn-outline btn-primary">Cambiar password</button>
		<button type="button" id="btnUpdateUsuario" name="btnUpdateUsuario" class="btn btn-outline btn-primary">Actualiza Usuario</button>
        <button type="button" id="btnEnableUsuario" name="btnEnableUsuario" class="btn btn-outline btn-primary">Habilita Usuario</button>
		<button type="button" id="btnDisableUsuario" name="btnDisableUsuario" class="btn btn-outline btn-danger">Deshabilita Usuario</button>
		<button type="button" id="btnDeleteUsuario" name="btnDeleteUsuario" class="btn btn-outline btn-danger">Eliminar Usuario</button>
	</div>
            
	<table id="lista_usuarios" class="table table-striped table-bordered table-hover customTables"  cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>id usuario</th>
                <th>Login</th>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Vigente</th>
                <th>Fc Ingreso</th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                <th>id usuario</th>
                <th>Login</th>
                <th>Nombre</th>
                <th>Rol</th>
                <th>Vigente</th>
                <th>Fc Ingreso</th>
            </tr>
        </tfoot>
        <tbody>
        </tbody>
    </table>
	
	<!-- fin contenido -->
	</div>
	<br>
	<br>
</div>
<!-- fin body -->
        	
<?php include_once("../footer.php"); ?>