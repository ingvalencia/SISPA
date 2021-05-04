$(function() {
	
	var dataSend = {};
	var timeout = 100000;
	var ct_usuarios = 0;
	var id_usuario = 0;
	var nombre_usr="";
	var validCorreo = false;
	var opt;
	var validRFC = false;


  //$("#nombre").mask("aaa");

  /*$('#nombre').mask('-99999999999999999.00', {
          //opciones
          placeholder: '[-]000[.00]',
          translation: {
            '-': {pattern: /[-]/, optional: true}            
          }
           }); 
  */
    
	$("#btnUpdateGrupo").hide();
    $("#btnEnableGrupo").hide();
    $("#btnListaAlumnos").hide();
	$("#btnDisableGrupo").hide();
	$(".moral").hide();
	$("#dvFactura").hide();
	$("#dvColonia").hide();
    $("#captcha_").hide();
	
	$("#ptl_ptl").mask("9999");
	$("#exp_unam").mask("9999999999");
	
	$("#telefono").mask("9999999999");
	$("#celular").mask("9999999999");
	
	$("#num_ext").mask("999999");
	$("#num_int").mask("999999");
	
    $("#id_cp").mask("99999");

	$("#captcha").mask("999999");

  //#################################################//
   /*Valido solo letras y espacios*/

   $(".letras").keypress(function (key) {
            window.console.log(key.charCode)
            if ((key.charCode < 97 || key.charCode > 122)//letras mayusculas
                && (key.charCode < 65 || key.charCode > 90) //letras minusculas
                && (key.charCode != 45) //retroceso
                && (key.charCode != 241) //�
                && (key.charCode != 209) //�
                && (key.charCode != 32) //espacio
                && (key.charCode != 225) //�
                && (key.charCode != 233) //�
                && (key.charCode != 237) //�
                && (key.charCode != 243) //�
                && (key.charCode != 250) //�
                && (key.charCode != 193) //�
                && (key.charCode != 201) //�
                && (key.charCode != 205) //�
                && (key.charCode != 211) //�
                && (key.charCode != 218) //�
 
                )
                return false;
        });

   //#################################################//
	
	
   //#################################################//
   
	function clearForm(){
		
		$("#nombre_usr").val("");
		$("#ap_pat_usr").val("");
		$("#ap_mat_usr").val("");
		$("#login").val("");
		$("#passwd").val("");
		$("#confirm_passwd").val("");
		$("#id_area").val("");
		$("#id_rol").val("");
		$("#vigente").val("");
		
	}
	//#################################################//
	
    //#################################################//
	$("#btnRegistrar").click(function(){

		opt = "addSolicitante";
		
		$('#frmSolicitante').formValidation('validate');
						
        });
	//#################################################//
	
	//#################################################//
	
	$("#correo_e").change(function(){

		if($("#correo_e").val()==""){
			validCorreo = false;
			return false;
		}
		
		dataSend = {};
		dataSend.opt="validEmail";
		dataSend.correo_e = $("#correo_e").val();
		processData();
		
	});
	//#################################################//
	
	//#################################################//
	
	$("#rfc").change(function(){

		if($("#rfc").val()==""){
			validRFC = false;
			return false;
		}
		
		dataSend = {};
		dataSend.opt="validRFC";
		dataSend.rfc = $("#rfc").val();
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
	
	$('#frmSolicitante').formValidation({
            framework: 'bootstrap',
            message: 'Valor no es v�lido',
            excluded: ':disabled',
            icon: {
            valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
            },
		
            fields: {
		
                        id_perfil: {
                            validators: {
                                notEmpty: {
                                    message: 'Debe indicar un perfil.'
                                }
                            }
                        },
						ptl_ptl: {
							enabled: false,
									validators: {
										notEmpty: {
											message: 'Debe indicar el plantel.'
										}
									}
								},
						exp_unam: {
							enabled: false,
							validators: {
								notEmpty: {
									message: 'Debe indicar el número de expediente.'
								}
							}
						},
						nombre: {
							validators: {
								notEmpty: {
									message: 'Debe indicar el nombre.'
								}
							}
						},
						ap_paterno:{
							validators: {
								notEmpty: {
									message: 'Debe indicar el apellido paterno.'
								}
							}
						},
						correo_e:{
							validators: {
										notEmpty: {
											message: 'Debe indicar un correo. <br>'
										},
										emailAddress: {
												  message: 'Debe de ser un correo válido. <br>'
										},
										callback: {
												  message: 'No usar un correo ya registrado.',
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
													message: 'Debe repetir el correo. <br>'
											},
											emailAddress: {
													message: 'Debe ser un correo válido. <br>'
											},
											identical: {
												field: 'correo_e',
												message: 'Los correos no coinciden.'
											}
									}
						},
						telefono:{
							validators: {
								notEmpty: {
									message: 'Debe indicar el telefono.'
								},
								 stringLength: {
									min: 10,
									max: 15,
									message: 'La longitud debe ser de al menos 10 dígitos.'
								}
							}
						},
						passwd:{
								validators: {
											notEmpty: {
												message: 'Debe indicar la contraseña. <br>'
											},
											stringLength: {
												min: 8,
												max: 15,
												message: 'La contraseña debe tener entre 8 a 15 carácteres. <br>'
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
												message: 'Debe repetir la contraseña. <br>'
											},
											identical: {
											  field: 'passwd',
											  message: 'Las contraseñas deben de ser iguales.'
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
						},
						agree: {
								  validators: {
									  notEmpty: {
										  message: 'Debe confirmar el aviso de privacidad.'
									  }
								  }
						}
						/* factura */
						,rfc: {
								enabled: false,
										validators: {
											notEmpty: {
												message: 'Debe indicar un RFC. <br>'
											},
											stringLength: {
												min: 12,
												max: 13,
												message: 'El RFC se debe de componer de 13 carácteres para persona física y de 12 para persona moral. <br> '
											},
											regexp: {
												regexp: /^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/,
												message: 'El RFC no es válido. <br>'
											},
											callback: {
												message: 'No usar un RFC ya esta registrado.',
												callback: function(value, validator, $field) {
												console.log("fun:" +validRFC);
												return validRFC;    
												}
											}
								  
										}
						},
						fnombre: {
							enabled: false,
							validators: {
								notEmpty: {
									message: 'Debe indicar el nombre.'
								}
							}
						},
						fap_paterno: {
							enabled: false,
							validators: {
								notEmpty: {
									message: 'Debe indicar el apellido paterno.'
								}
							}
						},
						nombre_fisc: {
							enabled: false,
							validators: {
								notEmpty: {
									message: 'Debe indicar la razón social.'
								}
							}
						},
						calle: {
							enabled: false,
							validators: {
								notEmpty: {
									message: 'Debe indicar la calle.'
								}
							}
						},
						num_ext: {
							enabled: false,
							validators: {
								notEmpty: {
									message: 'Debe indicar un número exterior.'
								}
							}
						},
						id_cp: {
							enabled: false,
							validators: {
								notEmpty: {
									message: 'Debe indicar un código postal.<br>'
								},
								stringLength: {
									min: 5,
									max: 5,
									message: 'El código postal no válido.'
								}
							}
						},
						id_colonia: {
							enabled: false,
							validators: {
								notEmpty: {
									message: 'Debe seleccionar una colonia.'
								}
							}
						},
						colonia_otra: {
							enabled: false,
							validators: {
								notEmpty: {
									message: 'Debe indicar el nombre de la colonia.'
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

      /*
      #cHECAR
      if ($("#chFactura").is(':checked')) {
            dataSend = $("#frmrfactura").serializeArray();
        }
      */

  		msg = "¿Esta seguro que desea actualizar los datos del usuario?";
  		
  		dataSend.opt = "addSolicitante";
  		dataSend.push({"name" : "opt", "value" : "addSolicitante" });
  		
  		
  		if($("#chFactura").is(':checked')) {
  			dataSend.push({"name" : "dFactura", "value" :  1});
  		}
  		else{
  			dataSend.push({"name" : "dFactura", "value" :  0});
  		}
  		
  		
  		dialog.confirm(msg, function(dialogWin){dialogWin.close();  processData(); });
        
        
    }
	
	//#################################################//
	
	//#################################################//
	//boton Salir
	
	$("#btnSalir").click(function(){
	
		window.location="../solicitud_usr/index.php";
				
	});
	 
	//#################################################//
	
	
	//#################################################//
	
	//boton imprime
	
	$(document).ready(function () {
        $('#btnImp').click(function () {
          $('#imprimeRegistro').printThis();
        });
      });
	 
	//#################################################//
	
	//#################################################//
	$("#tipo_persona").change(function(){
		
		$("#fnombre").val("");
		$("#fap_paterno").val("");
		$("#fap_materno").val("");
		$("#nombre_fisc").val("");
		
		if($("#tipo_persona").val()==1){
			$(".fisica").show();
			$(".moral").hide();
			$('#frmSolicitante')
			.formValidation('enableFieldValidators', 'nombre_fisc', false)
			.formValidation('enableFieldValidators', 'fnombre', true)
			.formValidation('enableFieldValidators', 'fap_paterno', true)
      .formValidation('enableFieldValidators', 'fap_materno', false);
			
		}
		else{
			$(".fisica").hide();
			$(".moral").show();
			$('#frmSolicitante')
			.formValidation('enableFieldValidators', 'nombre_fisc', true)
			.formValidation('enableFieldValidators', 'fnombre', false)
			.formValidation('enableFieldValidators', 'fap_paterno', false)
      .formValidation('enableFieldValidators', 'fap_materno', false);

		}
		
		
	});
	//#################################################//
	
	//#################################################//
	function setIni(json){
		
		//console.log("asdadasd");
		//console.log(json);
		
		$("#id_perfil").append("<option value=''>seleccione...</option>");
		$.each(json.perfiles, function(id, val){
			
			item = "<option value='"+val.id_perfil+"'>"+val.nom_perfil+"</option>";
			$("#id_perfil").append(item);
			
		});
		
		$("#terminos").html(json.terminos);		
		
	}
	//#################################################//

	//#################################################//
	//limpia Datos de facturacion al desahabilitad el check Marque...
	
	$("#chFactura").click(function(){
				
		if($(this).is(':checked')) {

			$("#dvFactura").show();
			$('#frmSolicitante')
			 // .formValidation('enableFieldValidators', 'tipo_persona', true)
			.formValidation('enableFieldValidators', 'rfc', true)
			.formValidation('enableFieldValidators', 'nombre_fisc', false)
			.formValidation('enableFieldValidators', 'fnombre', true)
			.formValidation('enableFieldValidators', 'fap_paterno', true)
			.formValidation('enableFieldValidators', 'fap_materno', false)
			.formValidation('enableFieldValidators', 'calle', true)
			.formValidation('enableFieldValidators', 'num_ext', true)
			.formValidation('enableFieldValidators', 'id_cp', true)
			.formValidation('enableFieldValidators', 'id_colonia', true);
		
		}
		else{

			$("#dvFactura").hide();

			$("#chColonia").prop("checked", false); 
			$("#dvColonia").hide();


			$('#frmSolicitante')
			//.formValidation('enableFieldValidators', 'tipo_persona', false)
			.formValidation('enableFieldValidators', 'rfc', false)
			.formValidation('enableFieldValidators', 'nombre_fisc', true)
			.formValidation('enableFieldValidators', 'fnombre', false)
			.formValidation('enableFieldValidators', 'fap_paterno', false)
			.formValidation('enableFieldValidators', 'fap_materno', false)
			.formValidation('enableFieldValidators', 'calle', false)
			.formValidation('enableFieldValidators', 'num_ext', false)
			.formValidation('enableFieldValidators', 'id_cp', false)
			.formValidation('enableFieldValidators', 'id_colonia', false)
			.formValidation('enableFieldValidators', 'colonia_otra', false);

             /*Limpio variables*/
         
            $("#tipo_persona").val("");
            $("#rfc").val("");
            $("#nombre_fisc").val("");
            $("#fnombre").val("");
            $("#fap_paterno").val("");
            $("#fap_materno").val("");
            $("#calle").val("");
            $("#num_ext").val("");
            $("#num_int").val("");
            $("#id_cp").val("");
            $("#nom_edo").val("");
            $("#nom_ciudad").val("");
            $("#nom_municipio").val("");
            $("#id_colonia").val("");
            $("#colonia_otra").val("");

		}

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
	
	$("#chColonia").click(function(){
				
		if($(this).is(':checked')) {
			$("#dvColonia").show();
			
			$('#frmSolicitante').formValidation('enableFieldValidators', 'id_colonia', false)
                          .formValidation('enableFieldValidators', 'colonia_otra', true);
		  $("#id_colonia").val("");
    }
		else{
			$("#dvColonia").hide();
			$('#frmSolicitante').formValidation('enableFieldValidators', 'id_colonia', true)
                        .formValidation('enableFieldValidators', 'colonia_otra', false);
		  $("#colonia_otra").val("");
    }

	});
	//#################################################//

	//#################################################//
    //limpia casilla: �No aparece su colonia?

    $(document).on('change', '#id_colonia', function() {


         $("#chColonia").prop("checked", false); 
         $("#dvColonia").hide();
         $("#colonia_otra").val("");
      
    });
	//#################################################//

    //#################################################//
	
	$("#chTerminos").click(function(){
					
		if($(this).is(':checked')) {
	
			  $("#myModal").modal();
		}

	});
	//#################################################//
	
	//#################################################//
	$("#id_perfil").change(function(){
				
		$("#dv_ptl_ptl").hide();
		$("#dv_exp_unam").hide();
		
		$("#exp_unam").val("");
		$("#ptl_ptl").val("");
		
		if(($(this).val() == 2)||($(this).val() == 3)){
			$("#dv_ptl_ptl").show();
			$("#dv_exp_unam").show();
			$('#frmSolicitante').formValidation('enableFieldValidators', 'ptl_ptl', true)
                        .formValidation('enableFieldValidators', 'exp_unam', true);

		}
		else if($(this).val() == 1){
			$("#dv_ptl_ptl").show();
			$('#frmSolicitante').formValidation('enableFieldValidators', 'ptl_ptl', true)
                        .formValidation('enableFieldValidators', 'exp_unam', false);
		}
		
		
	});
	//#################################################//
	
	//#################################################//
	$("#id_cp").change(function(){
		
		dataSend = {};
		dataSend.opt = "getColonia";
		dataSend.id_cp = $("#id_cp").val();
		processData();
		
		
	});
	//#################################################//
	
	//#################################################//
	function processData() {

        $.ajax({
            type: "POST",
            url: 'processSolicitante.php',
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
						setIni(json);
						break;
					
					case "getColonia":
		
						$("#id_colonia").empty();
						//$("#id_colonia").find("option").remove();
						//$('#mySelect').find('option')
						$("#id_colonia").append("<option value=''>Seleccione...</option>");
						
						$.each(json.colonias, function(id, col){
							$("#id_edo").val(col.id_edo);
							$("#nom_edo").val(col.nom_edo);
							$("#id_ciudad").val(col.id_ciudad);
							$("#nom_ciudad").val(col.nom_ciudad);
							$("#id_municipio").val(col.id_municipio);
							$("#nom_municipio").val(col.nom_municipio);
							
							$("#id_colonia").append("<option value='"+col.id_colonia+"'>"+col.nom_colonia+"</option>");
						});
						console.log(json);
	
						break;
						
					case "validEmail":
						
						validCorreo = !json.exist;
						console.log("proc:" +validCorreo);
		
						$('#frmSolicitante').formValidation('revalidateField', 'correo_e');
						
						break;
					
					case "validRFC":
						
						validRFC = !json.exist;
						console.log("proc:" +validRFC);
		
						$('#frmSolicitante').formValidation('revalidateField', 'rfc');
						
						break;
					
					case "addSolicitante":
						
						$("#dvGrid").hide();
						$("#dvConfirm").show();
						
						$("#correo_e").html(json.correo_e);
							
						
						break;
					
					
					
											
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
	//#################################################//
	
	dataSend.opt="getIni";
	processData();
		    
});
