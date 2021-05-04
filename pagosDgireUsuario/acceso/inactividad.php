<?php

$segundos = 1966; //si pasa este tiempo se detecta la inactividad del usuario en el sitio
if(($_SESSION['tiempo']+$segundos) < time()) {
    
   echo'<script type="text/javascript">
   
        var types = BootstrapDialog.TYPE_DANGER;
         
        BootstrapDialog.show({
            type: types,
            title: "Aviso",
            message: "Su sesion ha expirado por inactividad, vuelva a logearse para continuar",
            buttons: [{
                label: "Aceptar"
                ,action: function(dialog) {
                   window.location.href="../acceso";
                }
            }]
        });
        
       </script>';
}else 
   $_SESSION['tiempo']=time();
    
?>