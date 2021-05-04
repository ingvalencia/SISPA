<!DOCTYPE html>

<html lang="en">
    <?php
    /*Encabezado*/
    require_once("../templete/header2.php");

    require_once ("../common/config.php");
    require_once ("../common/clsSolicitudes.php");
    require_once ("../common/clsDatosFacturacion.php");
    include_once ("../common/clsCorreo.php");
    require_once ("../common/class.WSPagos.php");
    include_once ("../common/clsLog.php");

    foreach($_POST as $k => $val){
        $var = "\$" . $k . "=0;";
        eval($var);
        $var = "\$ref=&$" . $k . ";";
        eval($var);
        $ref = addslashes($val);
    }

    $solicitudes = new clsSolicitudes();
    $enviacorreo = new clsCorreo();
    $ficha = new WSPagos();

    $MyDebug->SetDebug(0);
    $MyDebug->DebugError();


    if(!$solicitudes){


        /*-------------------------------------------------------------------------------------------------------------------*/
            #Error envia correo en la bitacora
            
            $salto="\r\n";
            $log = new clsLog($logfile='error:CRON_general.log');
            $log_message = 'Error CRON001: No se pudo conectar con las clases: Solicitudes'.$salto;
            
            $log->writeLog($log_message);
            
        /*-------------------------------------------------------------------------------------------------------------------*/

    }


    global $solicitudes;

    $lista_Referencias = $solicitudes->getConsultaReferenciasCron();

    $numero_Referencia = $lista_Referencias->count;


    foreach($lista_Referencias->referencias as $i => $valor) {

            
        $referencias = $valor->referencia_ban;

        $resp =  $ficha->getStatus($referencias);


        if($resp == WSP_SUCCESS) {

            #ACTUALIZAR LA TABLA Y CAMBIAR EL ESTADO DE LOS PAGOS

            /*
                        
            $lstParam=array("cve_edo_sol" => 'FINZ',"cve_edo_pago" => 'PFIN');
            
            $resp = $this->update_solicitud($id_solicitante, $folio_sol, $lstParam);
            
            if(!$resp){
                return false;
            }
            
            return $this->generarFacturaTicket($id_solicitante, $folio_sol);
            */

            print_r('Entra aqui A');exit();



        }else if($resp == WSP_ERROR){


            //print_r('Entra aqui B');

            
                    if($ficha->getResponse()->error == "No existen registros bancarios con la referencia "){

                                /*-------------------------------------------------------------------------------------------------------------------*/
                                #Botacora
                                
                                $salto="\r\n";
                                $log = new clsLog($logfile='cron_consulta_referencia_no_pagadas.log');
                                $log_message = 'No existen registros bancarios con la referencia : '.$referencias.''.''.$salto;
                                            
                                $log->writeLog($log_message);

                                /*-------------------------------------------------------------------------------------------------------------------*/

                    }
                    else{
                                /*-------------------------------------------------------------------------------------------------------------------*/
                                    #Botacora
                                    $salto="\r\n";
                                    $error_result=end(get_object_vars($ficha->getResponse()));
                                    $log = new clsLog($logfile='cron_error:WS_estado_referencia.log');
                                    $log_message = ' No se puede consultar el estado de pago de la siguiente referencia: '.$referencias.$salto.'Error Web Service: ('.$error_result.')'.$salto;
                                    $log->writeLog($log_message);
                                /*-------------------------------------------------------------------------------------------------------------------*/
                                        
                    }

                    
        }


    }

    ?>

    <section class="content">

      <div class="error-page">
        <h2 class="headline text-red"></h2>

        <div class="error-content">
          <h3><i class="fa fa-warning text-red"></i> CRON</h3>

          <p>
            
          </p>

          <form class="search-form">
        
          </form>
        </div>
      </div>
      
    </section>
