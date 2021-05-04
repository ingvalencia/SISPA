$(function() {
	
	var dataSend = {};
	var timeout = 100000;
	var ct_usuarios = 0;
	var id_usuario = 0;
	var nombre_usr="";
	var isValid = false;
	var opt;
    
	$("#btnUpdateGrupo").hide();
    $("#btnEnableGrupo").hide();
    $("#btnListaAlumnos").hide();
	$("#btnDisableGrupo").hide();
	
	$("#id_concepto_pago").mask("9999");
	$("#importe_smdf").mask("9999");
	$("#cuenta").mask("9999");
	$("#precio_unitario").mask("9999999.99");
	
	$("#uimporte_smdf").mask("9999");
	$("#ucuenta").mask("9999");
	$("#uprecio_unitario").mask("9999999.99");
	
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
	

	$("#login").change(function(){
		
		dataSend = {};
		dataSend.opt = "existLogin";
		dataSend.login = $("#login").val();
		processData();
		
	});
	
	
	$("#btnAddUsuario").click(function(){
		
		clearForm();
		$("#frmUsuario").data('formValidation').resetForm();
		$("#btnUpdUsuario").hide();
		$("#btnSaveUsuario").show();
		$("#dvPasswd_ch").hide();
		$("#dvPasswd").show();
		$("#dvGrid").hide();
		$("#dvFrmUsuario").show();
		$(".page-header").html("Agregar usuario");
		$('#frmUsuario').formValidation('enableFieldValidators', 'passwd', true)
                        .formValidation('enableFieldValidators', 'confirm_passwd', true);

	});
	
	
	$("#btnCloseUsuario").click(function(){
		
		$("#dvGrid").show();
		$("#dvFrmUsuario").hide();
		$(".page-header").html("Administración de usuarios");
		
	});
	
	
	$("#btnSaveUsuario").click(function(){
	
		opt = "addUsuario";
		$('#frmUsuario').formValidation('validate');
		
	});
	
	
	$("#btnUpdUsuario").click(function(){
	
		opt = "updUsuario";
		$('#frmUsuario').formValidation('validate');
		
	});
	
	
	$("#passwd_ch").click(function(){
		
		$("#passwd").val("");
		$("#confirm_passwd").val("");
		
		if($(this).is(":checked")){
			$("#dvPasswd").show();
			$('#frmUsuario').formValidation('enableFieldValidators', 'passwd', true)
                        .formValidation('enableFieldValidators', 'confirm_passwd', true);

		}
		else{
			$("#dvPasswd").hide();
			$('#frmUsuario').formValidation('enableFieldValidators', 'passwd', false)
                        .formValidation('enableFieldValidators', 'confirm_passwd', false);

		}

		
	});
	
	ct_usuarios = $('#ct_usuarios').DataTable( {
		"processing": true
		,"bFilter": false
        ,"serverSide": true
		,"language": opLanguage
		,"scrollX": true
		,"order": [[ 0, "asc" ]]
        ,"ajax": {
            "url": "processListaUsuarios.php"
            ,"type": "POST"
        }
    } );
		

	$('#ct_usuarios tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
	    }
        else {
            ct_usuarios.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

			id_usuario = ct_usuarios.row( this ).data()[0];
			login = ct_usuarios.row( this ).data()[1];
			
        }
    });
	
	
	$('#ct_usuarios tbody').dblclick(function() {
	
		dataSend = {};
		dataSend.opt="getUsuario";
		dataSend.id_usuario = id_usuario;
		processData();		
		
	});
	



	
	
	$('#frmUsuario').formValidation({
		framework: 'bootstrap',
        message: 'Valor no es valido',
		excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
		
            nombre_usr: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el nombre del usuario'
                    }
                }
            },
			ap_pat_usr: {
                validators: {
                    notEmpty: {
                        message: 'Ingrese el apellido'
                    }
                }
            },
			login: {
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el login'
                    },
                    callback: {
                        message: 'Ya existe el usuario',
                        callback: function(value, validator, $field) {
							return !isValid;		
                        }
                    }
                }
            },
			passwd:{
				enabled: false,
				validators: {
                    notEmpty: {
                        message: 'Debe indicar el password'
                    }
                }
			},
			confirm_passwd:{
				enabled: false,
				validators: {
                    notEmpty: {
                        message: 'Debe indicar el password'
                    },
					identical: {
						field: 'passwd',
						message: 'Los password debe de ser iguales'
					}
                }
			},
			id_area: {
                validators: {
                    notEmpty: {
                        message: 'Debe indicar un área'
                    }
                }
            },
			id_rol: {
                validators: {
                    notEmpty: {
                        message: 'Debe indicar un rol'
                    }
                }
            },
			vigente: {
                validators: {
                    notEmpty: {
                        message: 'Seleccione una opción'
                    }
                }
            }
        }
    }).on('success.form.fv', function(e) {
	
		var msg;
		
		dataSend = {};
		dataSend = $("#frmUsuario").serializeArray();
		
		if(opt == "addUsuario"){
			msg = "¿Esta seguro que desea agregar el nuevo usuario?";
		}
		else if(opt == "updUsuario"){
			msg = "¿Esta seguro que desea actualizar los datos del usuario?";
			dataSend.push({"name" : "id_usuario", "value" : id_usuario });
		}
		
		dataSend.opt = opt;
		dataSend.push({"name" : "opt", "value" : opt });
		
		dialog.confirm(msg, function(dialogWin){dialogWin.close();  processData(); });
		
    });
	
	
	
	
	
	function setIni(xData){
		
		var item;
		
		$("#id_area").append("<option value=''>-seleccione-</option>");
		$.each(xData.areas, function(id, val){
			
			item = "<option value='"+val.id_area+"'>"+val.nom_area+"</option>";
			$("#id_area").append(item);
			$("#uid_area").append(item);
			$("#sid_area").append(item);
			
		});
		
		$("#id_rol").append("<option value=''>-seleccione-</option>");
		$.each(xData.roles, function(id, val){
			item = "<option value='"+val.id_rol+"'>"+val.nom_rol+"</option>";
			$("#id_rol").append(item);
		});
		
	}
	
	$("#id_concepto_pago").change(function(){
		
		dataSend = {};
		dataSend.opt="validClave";
		dataSend.clave=$("#id_concepto_pago").val();
		processData();
		
		
	});
	
	function setConcepto(json){
		
		$.each(json.concepto, function(id, val){
			$("#u"+id).val(val);
			$("#u"+id).html(val);
		});
	}
	
	
	
	function processData() {

        $.ajax({
            type: "POST",
            url: 'processUsuarios.php',
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
						setIni(json.data);
						break;
					
					case "existLogin":
						
						isValid = json.exist;
		
						$('#frmUsuario').formValidation('revalidateField', 'login');
	
						break;
						
					case "addUsuario":
						dialog.message(json.msg);
						$(".page-header").html("Administración de usuarios");
						$("#dvGrid").show();
						$("#dvFrmUsuario").hide();
						clearForm();
						break;
						
					case "getUsuario":
						//console.log(json.usuario);
						$.each(json.usuario, function(id, val){
							$("#"+id).val(val);
						});
						
						$("#frmUsuario").data('formValidation').resetForm();
						$(".page-header").html("Actualizar datos de usuario");
						$("#btnUpdUsuario").show();
						$("#btnSaveUsuario").hide();
						$("#dvPasswd_ch").show();
						$("#dvPasswd").hide();
						$("#dvGrid").hide();
						$("#dvFrmUsuario").show();
						$('#frmUsuario').formValidation('enableFieldValidators', 'passwd', false)
										.formValidation('enableFieldValidators', 'confirm_passwd', false);

						
						break;
						
					case "updUsuario":
						
						dialog.message(json.msg);
						$(".page-header").html("Administración de usuarios");
						$("#dvGrid").show();
						$("#dvFrmUsuario").hide();
						clearForm();
						ct_usuarios.ajax.reload();
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
	
	dataSend.opt="getIni";
	processData();
		    
});
