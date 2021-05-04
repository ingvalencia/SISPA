$(function() {
	
	var dataSend = {};
	var timeout = 100000;
	var validCorreo = false;

	$("#captcha_").hide();
	$("#captcha").mask("999999");
    
	var opt;
    
	//#################################################//
	function clearForm(){
		
	//$("#nombre_usr").val("");
		
	}
	//#################################################//
	
	
	/*------------------Solicitar Recuperaci�n de Contrase�a-----------------------------*/
    
	//#################################################//
	$("#btnRegistrar").click(function(){

		opt = "recoveryPassword";
		
		$('#frmSolicitante').formValidation('validate');
						
    });
	//#################################################//
    
	//#################################################//
    //Boton redirecciona
	
	$(document).on('click','.clsRegresa',function(){			
        setTimeout("window.location.href='../acceso/index.php';",0);
	});
	//#################################################//
    
    //#################################################//
    $("#correo_e").change(function(){

		if($("#correo_e").val()==""){
			validCorreo = false;
			return false;
		}
		
		dataSend = {};
		dataSend.opt="checkCorreoSol";
		dataSend.correo_e = $("#correo_e").val();
		processData();
		
	});
	//#################################################//
	
	//#################################################//
	// Generando un captcha de suma
  
		function randomNumber(min, max) {
			return Math.floor(Math.random() * (max - min + 1) + min);
		}
		$('#captchaOperation').html([randomNumber(1, 100), '+', randomNumber(1, 200), '='].join(' '));
	
		//Regenera la suma
		$("#btnRegenera").click(function(){
		
			$('#captchaOperation').html([randomNumber(1, 100), '+', randomNumber(1, 200), '='].join(' '));
	
			/*Limpiar input de respuesta*/
			$('#captcha').val("");
	
			$('#frmSolicitante')
				  .formValidation('enableFieldValidators', 'captcha', true);
					
		});
  //#################################################//


   //#################################################//
    //limpia la suma al desahabilitad el check No soy...

    $(document).on('change', '#Chcaptcha', function() {

      
      if($(this).is(':checked')) {
      
            $("#captcha_").show();

            $('#captchaOperation').html([randomNumber(1, 100), '+', randomNumber(1, 200), '='].join(' '));

            
            $('#frmSolicitante')
            .formValidation('enableFieldValidators', 'captcha', true);
       
    }
    else{//#El check no esta habilitado
      
            $("#captcha_").hide();

             /*Limpio variables*/
             $("#captcha").val("");

             $('#frmSolicitante')
              .formValidation('enableFieldValidators', 'captcha', false);

       
         }
         
    });

  //#################################################// 


  //#################################################//   
	$('#frmSolicitante').formValidation({
		framework: 'bootstrap',
        message: 'Valor no es válido',
		excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
        },
		
        fields: {
		
			correo_e:{
				validators: {
                    notEmpty: {
                        message:'Debe indicar un correo. <br>'
                    },
			   emailAddress: {
                        message: 'Debe de ser un correo válido.<br>'
                    },
                    callback: {
                        message: 'Usar el correo registrado en el sistema.',
                        callback: function(value, validator, $field) {
							          console.log("fun:" +validCorreo);
							          return validCorreo;		
                        }
                    }
                }
			},
			confirmar_correo_e:{
				validators: {
                    notEmpty: {
                        message: 'Debe repetir el correo.<br>'
                    },
					emailAddress: {
                        message: 'Debe de ser un correo válido.<br>'
                    },
					identical: {
						field: 'correo_e',
						message: ' Los correos no coinciden.'
					}
                }
			},
			captcha: {
                validators: {
                    callback: {
                        message: 'Respuesta incorrecta.',
                        callback: function(value, validator, $field) {
                            var items = $('#captchaOperation').html().split(' '), sum = parseInt(items[0]) + parseInt(items[2]);
                            return value == sum;
                        }
                    }
                }
            },
			Chcaptcha: {
					  validators: {
						  notEmpty: {
							  message: 'Debe confirmar que no es un robot.'
						  }
					  }
				  }
			
        }
    }) .on('success.form.fv', function(e) {

    	agregar_solicitud();
    })
	.on('err.form.fv', function(e) {
		
	});
 //#################################################// 


  //#################################################//    
  function agregar_solicitud(){
        
      var msg;
  		
  		dataSend = {};
  		dataSend = $("#frmSolicitante").serializeArray();

  		msg = "¿Esta seguro que desea enviar la información?";
  		
  		dataSend.opt = "recoveryPassword";
  		dataSend.push({"name" : "opt", "value" : "recoveryPassword" });
  		
  		dialog.confirm(msg, function(dialogWin){dialogWin.close();  processData(); });
        
        
    }
   //#################################################// 
	/*-----------------------------------------------*/
	
	
	
	/*------------------Cambio de contrase�a-----------------------------*/

   //#################################################// 
	$("#btnRegistrarA").click(function(){

		opt = "changePassword";
		
		$('#frmSolicitanteA').formValidation('validate');
						
    });
	 //#################################################// 


	 //#################################################// 
	$("#btnSolicita").click(function(){

		$(document).ready(function() {
			setTimeout("window.location.href='../acceso/index.php';");
		});
						
    });
   //#################################################// 

  //#################################################// 
    $("#btnSalir").click(function(){

		$(document).ready(function() {
			setTimeout("window.location.href='../acceso/index.php';");
		});
						
    });
  //#################################################//


  //#################################################//
  // Generando un captcha de suma
  
    function randomNumber(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    }
    $('#captchaOperation').html([randomNumber(1, 100), '+', randomNumber(1, 200), '='].join(' '));

  //Regenera la suma
    $("#btnRegenera").click(function(){
    
        $('#captchaOperation').html([randomNumber(1, 100), '+', randomNumber(1, 200), '='].join(' '));

        /*Limpiar input de respuesta*/
        $('#captcha').val("");

        $('#frmSolicitanteA')
              .formValidation('enableFieldValidators', 'captcha', true);
                
    });
  //#################################################//


   //#################################################//
    //limpia la suma al desahabilitad el check No soy...

    $(document).on('change', '#Chcaptcha', function() {

      
      if($(this).is(':checked')) {
      
            $("#captcha_").show();

            $('#captchaOperation').html([randomNumber(1, 100), '+', randomNumber(1, 200), '='].join(' '));

            
            $('#frmSolicitanteA')
            .formValidation('enableFieldValidators', 'captcha', true);
       
    }
    else{//#El check no esta habilitado
      
            $("#captcha_").hide();

             /*Limpio variables*/
             $("#captcha").val("");

             $('#frmSolicitanteA')
              .formValidation('enableFieldValidators', 'captcha', false);

       
         }
         
    });

  //#################################################// 

  
	//#################################################// 
	$('#frmSolicitanteA').formValidation({
		framework: 'bootstrap',
        message: 'Valor no es válido',
		excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
        },
		
        fields: {
		
			passwd:{
				validators: {
                    notEmpty: {
                        message: 'Debe indicar la contraseña<br>'
                    },
					          stringLength: {
                        min: 8,
                        max: 15,
                        message: 'La contraseña debe tener entre 8 a 15 caracteres<br>'
                    },
                    regexp: {
                        regexp: /^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,15}$/,
                        message: 'Debe contener al menos un número.<br>Al menos un carácter en mayúscula.<br>Al menos un carácter en minúscula.'
                    }
                }
			},
			confirmar_passwd:{
				validators: {
                    notEmpty: {
                        message: 'Debe repetir la contraseña.'
                    },
					identical: {
						field: 'passwd',
						message: 'Las contraseñas debe de ser iguales.'
					}
                }
			},
			captcha: {
                validators: {
                    callback: {
                        message: 'Respuesta incorrecta.',
                        callback: function(value, validator, $field) {
                            var items = $('#captchaOperation').html().split(' '), sum = parseInt(items[0]) + parseInt(items[2]);
                            return value == sum;
                        }
                    }
                }
            },
        Chcaptcha: {
                validators: {
                    notEmpty: {
                        message: 'Debe confirmar que no es un robot.'
                    }
                }
            }
			
        }
    }) .on('success.form.fv', function(e) {

    	agregar_solicitudA();
    })
	.on('err.form.fv', function(e) {
		
	});
   //#################################################// 

    //#################################################// 
    function agregar_solicitudA(){
        
       var msg;
		
		dataSend = {};
		dataSend = $("#frmSolicitanteA").serializeArray();

		msg = "¿Esta seguro que desea enviar la información?";
		
		dataSend.opt = "changePassword";
		dataSend.push({"name" : "opt", "value" : "changePassword" });
		
		dialog.confirm(msg, function(dialogWin){dialogWin.close();  processData(); });
        
        
    }
	 //#################################################// 
	/*-------------------------------------------------------------------*/
	
	
	/*-----------------------------------------------*/
	function processData() {

        $.ajax({
            type: "POST",
            url: 'processRegistro.php',
            data: dataSend,
            dataType: "json",
            timeout: timeout,
            beforeSend: function() {
                dialog.show();
            },
            success: function(json) {

                dialog.close();

                if (json.error) {
                    dialog.alert(json.msg);
					console.log(json.debug);
                    return false;
                }
				
				switch(dataSend.opt){
					case "getIni":
						//setIni(json);
						break;
                    
                    /*Solicita Solicitar Recuperaci�n de Contrase�a*/  
          case "checkCorreoSol":
						
						validCorreo = !json.exist;
						console.log("proc:" +validCorreo);
		
						$('#frmSolicitante').formValidation('revalidateField', 'correo_e');
						
						break;
                    
					case "recoveryPassword":
						  
                        $('#info_correo').html('Se ha enviado un correo a '+' '+json.correo_e+' '+' para la recuperaci&oacute;n de la contrase&ntilde;a.');
                        $("#myModal").modal();
						$("#dvGrid").hide();
						$("#dvConfirm").show();
						
						break;
					/**/
					
					/*Cambio de contrase�a*/
					
					case "changePassword":
						  					//$resp = array('success' => true, 'message' => "<p>La contrase&ntilde;a para ingresar al sistema de pagos de la DGIRE ha sido actualizada con &eacute;xito. Ya puede acceder al sistema</p>");
                        //$('#info_correo').html('Se ha enviado un correo a '+' '+json.correo_e+' '+' para la recuperaci&oacute;n de la contrase&ntilde;a.');
                        //$("#info_pwd").data('correo', resp.correo);
						$("#info_pwd").html(json.resp);
						console.log(json.message)
						$("#myModal").modal();
						$("#dvGrid").hide();
						
						//$("#dvConfirm").show();
						
						break;
					
					/**/ 
					
											
				}
				
				  return false;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                dialog.close();
                msg = "";
                if (textStatus == "timeout") {
                    msg = "tiempo agotado";
                } else {
                    //msg = messageCommon.errorSend;
                    msg = "Error en la conexion";
                }
				dialog.alert(msg)
                return false;
            }
        });
    }
	/*-----------------------------------------------*/
	//#################################################//
	
	dataSend.opt="getIni";
	processData();
		    
});
