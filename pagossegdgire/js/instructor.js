var dataSend = {};

$(function() {
	

	var id_instructor;
	var timeout = 100000;
	var curp;
		
	$("#btnUpdateInstructor").hide();
	
	$("#telefono").mask("99999999");
	$("#celular").mask("9999999999");
	$("#curp").mask("AAAA999999AAAAAA99");
	
	function setData(data){
		id_instructor = data.id_instructor;
		$.each(data, function(id, valor){
			//console.log(id+": "+valor);
			$("#"+id).val(valor);
		});
	}
	
	
	$("#curp").change(function(){
		
		curp = $("#curp").val();
		curp = curp.toUpperCase();
		$("#curp").val(curp);
		dataSend = {};
		dataSend.opt = "checkCurp";
		dataSend.curp = curp;
		processData();
	});
	
	
	$("#btnUpdateInstructor").click(function(){
		
		opt = "updateInstructor";
		dataSend=$('#frmInstructor').serializeArray();
		dataSend.opt = opt   
		dataSend.push({"name" : "opt", "value" : opt });
		dataSend.push({"name" : "id_instructor", "value" : id_instructor});
		
		$('#frmInstructor').formValidation('validate');
		return false;
   });
   
   
   
   $("#btnSaveInstructor").click(function(){
	   
	   opt = "saveInstructor";
		dataSend=$('#frmInstructor').serializeArray();
		dataSend.opt = opt   
		dataSend.push({"name" : "opt", "value" : opt });
		
		$('#frmInstructor').formValidation('validate');
		return false;
   });
   
    $("#btnCancelInstructor").click(function(){
	   window.location = "./lista_instructores.php";
   });
   

	
	function afterSaveInstructor(msg){
		msg+="<br><br> ¿Desea agregar otro instuctor?";
		dialog.confirm(msg, 
					function(dWin){ 
							dWin.close();
							$('#frmInstructor')[0].reset();
							$('#frmInstructor').formValidation('resetForm');
					},
					function(dWin){ 
							dWin.close();
							window.location="lista_instructores.php";
					});
		return false;
	}
	
	
	
	function afterGetInstructor(xInstructor){
		console.log(xInstructor);
		setData(xInstructor);
	}
	
	
	function afterUpdateInstructor(msg){
		dialog.message(msg, function(dialogWin){ window.location = "./lista_instructores.php";});
	}
	
	
	function afterCheckCurp(json){
		
		if(json.exist=="si"){
			msg  = "Ya existe un instructor con esta curp <strong>"+curp+"</strong>ingrese otra curp";
			dialog.message(msg);
			$("#curp").val("");
		}
		
		return false;
	}
	
	
	
	
	
	
	
	
$('#frmInstructor').formValidation({
        message: 'Valor no es valido',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
		
            curp: {
                
                validators: {
                    notEmpty: {
                        message: 'Debes ingresar un curp valido'
                    },
                    stringLength: {
                        min: 18,
                        max: 18,
                        message: 'Debe de ser de 18 caracteres'
                    }
					
                }
            },
			nombre_persona: {
                
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el nombre de persona'
                    },
                    stringLength: {
                        min: 3,
                        max: 50,
                        message: 'Debe de ser mayor a 3 caracteres'
                    }
                }
            },
			ap_paterno: {
                
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el ap paterno'
                    },
                    stringLength: {
                        min: 3,
                        max: 50,
                        message: 'Debe de ser mayor a 3 caracteres'
                    }
                }
            },
			ap_materno: {
                
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el ap materno'
                    },
                    stringLength: {
                        min: 3,
                        max: 50,
                        message: 'Debe de ser mayor a 3 caracteres'
                    }
                }
            },
			genero: {
                validators: {
                    notEmpty: {
                        message: 'Debe seleccionar el genero'
                    }
                }
            },
			telefono: {
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el telefono'
                    }
                }
            },
			celular: {
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el celular'
                    }
                }
            },
			email: {
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el email'
                    },
                    emailAddress: {
                        message: 'El email no es valido'
                    }
                }
            }
			
        }
    }).on('success.form.fv', function(e) {
	
		var xnom = $("#nombre_persona").val();
			xnom += " "+$("#ap_paterno").val();		
			xnom += " "+$("#ap_materno").val();
			
		switch(opt){
			case "saveInstructor":			
				msg = "Esa seguro que desea guardar los datos del instructor: <br>"+xnom;
				break;
			case "updateInstructor":
				msg = "Esa seguro que desea actualizar los datos del instructor: <br>"+xnom;
				break;
		}
		
		dialog.confirm(msg, function(dialogWin){dialogWin.close(); processData(); });
		
    });

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	function processData(){
		
		$.ajax({
			type: "POST",
			url: 'processInstructores.php',
			data: dataSend,
			dataType: "json",
			timeout: timeout,
			beforeSend: function() {
				dialog.show();
			},
			success: function(json) {

				dialog.close();

				if (json.error == "si") {
					dialog.alert(json.msg);
					console.log(json.debug);
					return false;
				}
				
				switch(dataSend.opt){
					case "saveInstructor":
						afterSaveInstructor(json.msg);
						break;
					case "updateInstructor":
						afterUpdateInstructor(json.msg);
						break;
					case "getInstructor":
						afterGetInstructor(json.instructor);
						break;
					case "checkCurp":
						afterCheckCurp(json);
						break;
					default:
					
				}
				
				
				return false;

			},
			error: function(jqXHR, textStatus, errorThrown) {
				dialog.close();
				msg = "";
				if (textStatus == "timeout") {
					msg = "El tiempo de espera agotado: intentelo de nuevo";
				} else {
					msg = "Error en la conexión: intentelo de nuevo";
				}
				dialog.alert(msg);
				return false;
			}
		});
	}
	
	//console.log("action: "+action);
	if(dataSend.opt != undefined){
		if(dataSend.opt=="getInstructor"){
			opt = "updateInstructor";
			$("#titlePage").text("Actualizar datos instructor");
			processData();
			$("#btnSaveInstructor").hide();
			$("#btnUpdateInstructor").show();
			$("#btnCancelInstructor").show();
		}
		else{
			opt = "saveInstructor";
			$("#btnSaveInstructor").show();
			$("#btnUpdateInstructor").hide();
			$("#btnCancelInstructor").hide();
		}
	}
	else{
		opt = "saveInstructor";
	}
	
});
