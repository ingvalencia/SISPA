var sendData = {};
var id_alumno = 0;
var timeout = 100000;
var ctNiveles;
var ctAreas;
var actionAlumno;
$(function() {
	

	var id_instructor;
	var exitAlumno = false;
	var processPage = "";
	var actionAlumno = "";
	var alumno;
	var opt="";
	

	$("#curp").mask("AAAA999999AAAAAA99");
	$("#num_expediente").mask("99999999");
	
	
	$("#btnAddAlumnoGrupo").hide();
	$("#btnCancelAlumnoGrupo").show();
	
	$("#btnSaveAlumno").hide();
	$("#btnUpdateAlumno").hide();
	$("#btnCancelAlumno").show();
	
	
	function clearFrmAlumno(){

		$('#frmAlumno').formValidation('resetForm');
				
		$("#frmAlumno").find("input").val("");
		$("#genero").val(0);
		
		$("#slcCTNiveles").find('option').remove();
		$("#slcNivelesAlumno").find('option').remove();
		
		$("#slcCTAreas").find('option').remove();
		$("#slcAreasAlumno").find('option').remove();
		
		$.each(ctNiveles, function(n, nivel){
			$("#slcCTNiveles").append("<option value='"+nivel.id_nivel+"'>"+nivel.descripcion_nivel+"</option>")
		});
		
		$.each(ctAreas, function(a, area){
			$("#slcCTAreas").append("<option value='"+area.id_area+"'>"+area.descripcion_area+"</option>")
		});
		
		$('#frmAlumno').formValidation('resetForm');
	}
	
	function setCatalogos(data){
		ctNiveles = data.ctNiveles;
		ctAreas = data.ctAreas;
		
		$.each(ctNiveles, function(n, nivel){
			$("#slcCTNiveles").append("<option value='"+nivel.id_nivel+"'>"+nivel.descripcion_nivel+"</option>")
		});
		
		$.each(ctAreas, function(a, area){
			$("#slcCTAreas").append("<option value='"+area.id_area+"'>"+area.descripcion_area+"</option>")
		});
		
	}
	
	function getDataAlumno(){
		
		dataSend = {};
		
		var lstNiveles = new Array();
		var lstAreas = new Array();
		
		dataSend = $("#frmAlumno").serializeArray();
		dataSend.push({"name" : "num_expediente", "value" : $("#num_expediente").val() });
		dataSend.push({"name" : "curp", "value" : $("#curp").val() });

		$("#slcNivelesAlumno > option").each(function() { lstNiveles.push(this.value); });
		dataSend.push({"name" : "niveles", "value" : lstNiveles });
		$("#slcAreasAlumno > option").each(function() { lstAreas.push(this.value); });
        dataSend.push({"name" : "areas", "value" : lstAreas });
		//cad += "&lstUser=" + JSON.stringify(lstUser);
	}
	
	
	
	$("#btnCancelAlumnoGrupo").click(function(){
		location.reload();
		return false;
		localStorage.removeItem("actionAlumno");
		localStorage.removeItem("id_alumno");
		$("#dvConteiner").empty();
		$("#dvConteiner").hide();
		$("#dvDataGrupo").show();
	});
	
	$("#btnCancelAlumno").click(function(){
		localStorage.removeItem("actionAlumno");
		localStorage.removeItem("id_alumno");
		window.location = "lista_alumnos.php";		
	});
	
	
	$('#slcCTNiveles').on('change', function() {
		val = $("#slcCTNiveles option:selected").val();
        text = $("#slcCTNiveles option:selected").text();
        $("#slcCTNiveles option[value='" + val + "']").remove();
        $("#slcNivelesAlumno").append("<option value='" + val + "'>" + text + "</option>");
    });
	
	$('#slcNivelesAlumno').on('change', function() {
		val = $("#slcNivelesAlumno option:selected").val();
        text = $("#slcNivelesAlumno option:selected").text();
        $("#slcNivelesAlumno option[value='" + val + "']").remove();
        $("#slcCTNiveles").append("<option value='" + val + "'>" + text + "</option>");
    });
	
	
	$('#slcCTAreas').on('change', function() {
		val = $("#slcCTAreas option:selected").val();
        text = $("#slcCTAreas option:selected").text();
        $("#slcCTAreas option[value='" + val + "']").remove();
        $("#slcAreasAlumno").append("<option value='" + val + "'>" + text + "</option>");
    });
	
	$('#slcAreasAlumno').on('change', function() {
		val = $("#slcAreasAlumno option:selected").val();
        text = $("#slcAreasAlumno option:selected").text();
        $("#slcAreasAlumno option[value='" + val + "']").remove();
        $("#slcCTAreas").append("<option value='" + val + "'>" + text + "</option>");
    });
	
	
		
	$("#tipo_alumno").change(function(){
	
		if($("#tipo_alumno").val() == 1){
			$("#num_expediente").val("");
			$("#num_expediente").prop('disabled', false);
		}
		else{
			$("#num_expediente").val("");
			$("#num_expediente").prop('disabled', true);
		}
		
	});
	

	
	
	$("#num_expediente").change(function(){		
		dataSend = {};
		dataSend.opt = "checkExpediente";
		dataSend.num_expediente = $("#num_expediente").val();
		processData();
	});
	
	$("#curp").change(function(){
		dataSend = {};
		dataSend.opt = "checkCurp";
		dataSend.curp = $("#curp").val();
		processData();
	});
	
	function setDataAlumno(xAlumno){
		
		alumno = xAlumno;
		if(xAlumno.id_alumno != undefined){
			id_alumno=xAlumno.id_alumno;
		}
		
		$("#num_expediente").val(xAlumno.num_expediente);
		$("#curp").val(xAlumno.curp);
		
		$.each(xAlumno, function(id, valor) {
			if(id=="dNiveles"){
				$.each(valor, function(i, n) {
					$('#slcNivelesAlumno').append("<option value='" + n.id_nivel + "'>" + n.descripcion_nivel + "</option>");
					$("#slcCTNiveles option[value='" + n.id_nivel + "']").remove();
				});
			}
			else if(id=="dAreas"){
				$.each(valor, function(i, a) {
					$('#slcAreasAlumno').append("<option value='" + a.id_area + "'>" + a.descripcion_area + "</option>");
					$("#slcCTAreas option[value='" + a.id_area + "']").remove();
				});
			}
			else{
				$("#"+id).val(valor);
			}
		});

	}
	
	
	function actionAfterCheckAlumnoGrupo(json){
		
		//Si se checo el curp
		if(dataSend.opt == "checkCurp"){
			if(json.exist=="si"){
				if(json.alumno.num_expediente!=""){
					$("#tipo_alumno").val(1);
					$("#num_expdiente").prop('disabled', false);
				}
				else{
					$("#tipo_alumno").val(2);
					$("#num_expdiente").prop('disabled', true);
				}
			
				existAlumno = true;
				setDataAlumno(json.alumno);
				$("#frmAlumno").find("input").prop('disabled', true);
				$("#frmAlumno").find("select").prop('disabled', true);
				$("#curp").prop('disabled', false);
				$("#num_expediente").prop('disabled', false);
				actionAlumno = "regAlumnoGrupo";
				
				return false;
			}
			
			existAlumno = false;
			actionAlumno = "regNewAlumnoGrupo";
			
			return false;
		}
		
		//si se checo num_expediente
		if(json.exist=="si"){
			existAlumno = true;
			setDataAlumno(json.alumno);
			$("#frmAlumno").find("input").prop('disabled', true);
			$("#frmAlumno").find("select").prop('disabled', true);
			$("#curp").prop('disabled', false);
			$("#num_expediente").prop('disabled', false);
			actionAlumno = "regAlumnoGrupo";
		}
		else if((json.exist=="no")&&(json.validExp=="si")){
			id_alumno = 0;
			existAlumno = false;
			actionAlumno = "regNewAlumnoGrupo";
			setDataAlumno(json.alumno);
			$("#frmAlumno").find("input").prop('disabled', false);
			$("#frmAlumno").find("select").prop('disabled', false);
			$("#curp").prop('disabled', false);
			$("#num_expediente").prop('disabled', false);
			
		}
		else if((json.exist=="no")&&(json.validExp=="no")){
			clearFrmAlumno();
			msg = "No existe ningun profesor con este expediente";
			dialog.message(msg);
			return false;
		}
		else{			
			id_alumno = 0;
			existAlumno = false;
			actionAlumno = "regNewAlumnoGrupo";
			$("#frmAlumno").find("input").prop('disabled', false);
			$("#frmAlumno").find("select").prop('disabled', false);
			$("#curp").prop('disabled', false);
			$("#num_expediente").prop('disabled', false);
		}
		
		return false;
	}
	
	function actionAfterCheckAlumno(json){
		
		if(dataSend.opt == "checkExpediente"){
			
			if(json.exist=="si"){
				if(actionAlumno == "getAlumno"){
					$("#curp").val(alumno.curp);
					$("#num_expediente").val(alumno.num_expediente);
				}
				else{
					$("#curp").val("");
					$("#num_expediente").val("");
				}
				msg="Ya existe un alumno con este número de expediente, intente con otro";
				dialog.message(msg);
				return false;
			}
			
			
			if(json.validExp == "si"){
				setDataAlumno(json.alumno);
				return false;
			}

			$("#curp").val("");
			$("#num_expediente").val("");
			msg="No existe un profesor con este número de expediente, itente con otro";
			dialog.message(msg);
			return false;
		}


		//curp
		if(json.exist=="si"){
			if(actionAlumno == "saveAlumno"){
				$("#curp").val("");
			}
			else{
				$("#curp").val(alumno.curp);
				$("#num_expediente").val(alumno.num_expediente);
			}
			
			msg="Ya existe un alumno con esta curp, intente con otro";
			dialog.message(msg);
			return false;
		}
		
	}
























$('#frmAlumno').formValidation({
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
                        message: 'Debe de ser mayor a 6 caracteres'
                    }
					
                }
            },
			num_expediente: {
                
                validators: {
                    /*
					notEmpty: {
                        message: 'Debe un expediente valido',
						callback: function(value, validator) {
							return false;
						}
                    },
					*/
                    stringLength: {
                        min: 8,
                        max: 8,
                        message: 'Debe de ser de 8 digitos'
                    },
					digits: {
                        message: 'Solo pueden ser números'
                    },
                    callback: {
                        message: 'Debe de ser un expediente valido',
                        callback: function(value, validator) {
								
								console.log("12");
								if($("#tipo_alumno").val()==1){
									console.log("13");
									if($("#num_expediente").val()=="") { return false; }
									//if(value == "") { return false; }
								}
								console.log("14");
								return true;
                        }
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
			case "saveAlumno":			
				msg = "Esa seguro que desea guardar los datos del alumno: <br>"+xnom;
				break;
			case "updateAlumno":
				msg = "Esa seguro que desea actualizar los datos del alumno: <br>"+xnom;
				break;
			case "regAlumnoGrupo":
				msg = "Esa seguro que desea registrar al grupo el alumno : <br>"+xnom;
				break;
			case "regNewAlumnoGrupo":
				msg  = "Esa seguro que desea crear el nuevo alumno:<br><br>"+xnom;
				msg += "<br><br>Y agregar el alumno al grupo";
				break;
		}
		
		dialog.confirm(msg, function(dialogWin){dialogWin.close(); processData(); });
		
    });


	function afterRegAlumnoGrupo(json){
		
		actionAlumno = "regAlumnoGrupo";
		clearFrmAlumno();
		existAlumno = false;
		id_alumno=0;
		$("#frmAlumno").find("input").prop('disabled', false);
		$("#frmAlumno").find("select").prop('disabled', false);
		$("#tipo_alumno").val(1);
		msg = "Desea registrar otro alumno al grupo";
		dialog.confirm(msg
		,function(dialogWin){ dialogWin.close(); }
		,function(dialogWin){ dialogWin.close(); location.reload(); });
		
	}

	function afterSaveAlumno(xMsg){
		
		msg  = xMsg+"<br><br>";
		msg += "Quiere agregar otro alumno";
		
		dialog.confirm(msg
		,function(dialogWin){
			dialogWin.close();
			clearFrmAlumno();
		}
		,function(dialogWin){
			dialogWin.close();
			window.location = "lista_alumnos.php";
		});
	}
	
	function afterGetAlumno(data){
		
		alumno = data.alumno;
		setCatalogos(data);
		setDataAlumno(alumno);
		
		$("#dvTipoAlumno").hide();
		$("#dvCurp").show();
		//$("#curp").prop('disabled', false);
		$("#dvNumExpediente").show();
		//$("#num_expediente").prop('disabled', false);
		
		
	}
	
	function afterUpdateAlumno(msg){
		
		localStorage.removeItem("actionAlumno");
		localStorage.removeItem("id_alumno");
		
		dialog.message(msg, function(dialogWin){ dialogWin.close(); window.location = "lista_alumnos.php"; })
	}


	$("#btnSaveAlumno").click(function(){
		opt= "saveAlumno";
		getDataAlumno();
		dataSend.opt = opt;
		dataSend.push({"name" : "opt", "value" : opt });
		$('#frmAlumno').formValidation('validate');
	});
	
	$("#btnUpdateAlumno").click(function(){
		opt= "updateAlumno"; 
		getDataAlumno();
		dataSend.opt = opt
		dataSend.push({"name" : "opt", "value" : opt });
		dataSend.push({"name" : "id_alumno", "value" : id_alumno });
		$('#frmAlumno').formValidation('validate');
	});
	
	$("#btnAddAlumnoGrupo").click(function(){
	
		opt=actionAlumno;
		getDataAlumno();
		dataSend.opt = actionAlumno
		dataSend.push({"name" : "opt", "value" : opt });
		dataSend.push({"name" : "id_alumno", "value" : id_alumno });
		dataSend.push({"name" : "id_grupo", "value" : localStorage.getItem('id_grupo') });
		$('#frmAlumno').formValidation('resetForm');
		if(actionAlumno == "regAlumnoGrupo"){
			if(!$("#curp").val()){
				msg="Debe indicar un número de expediente o curp";
				dialog.message(msg);
				return false;
			}
		}
		
		$('#frmAlumno').formValidation('validate');
	});















	
	function processData(){
		
		$.ajax({
			type: "POST",
			url: "../alumnos/processAlumnos.php",
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
					//alumnos
					case "getIni":
						setCatalogos(json.data);
						break;
					case "getIniAlumno":
						setCatalogos(json);
						break;
					case "saveAlumno":
						afterSaveAlumno(json.msg);
						break;
					case "updateAlumno":
						afterUpdateAlumno(json.msg);
						break;
					case "getAlumno":
						afterGetAlumno(json.data);
						break;
					case "regAlumnoGrupo":
					case "regNewAlumnoGrupo":
						afterRegAlumnoGrupo(json);
						break;
					case "checkExpediente":
					case "checkCurp":
						if(actionAlumno == "regAlumnoGrupo"){ actionAfterCheckAlumnoGrupo(json); }
						else if(actionAlumno == "regNewAlumnoGrupo"){ actionAfterCheckAlumnoGrupo(json); }
						else{ actionAfterCheckAlumno(json); }
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
	
	
	//alert(localStorage.getItem('id_alumno'));
	
	if(localStorage.getItem('actionAlumno')!=null){
		//alert("1");
		actionAlumno = localStorage.getItem('actionAlumno');
		//alert("ssss:"+actionAlumno);
		if((actionAlumno != "getAlumno")&&(actionAlumno != "addAlumno")&&(actionAlumno != "saveAlumno")){
			actionAlumno == "saveAlumno";
		}
	}
	else{
		//alert("2");
		actionAlumno = "saveAlumno";
	}
	
	$("#tipo_alumno").val(1);

	if(actionAlumno=="getAlumno"){
		processPage = "processAlumnos.php";
		dataSend.opt = actionAlumno;
		dataSend.id_alumno = localStorage.getItem('id_alumno');
		$("#titlePageAlumno").text("Actualizar datos Alumno");
		$("#btnSaveAlumno").hide();
		$("#btnUpdateAlumno").show();
		$("#btnCancelAlumno").show();
		$("#dvBtnAlumno").show();
		$("#dvBtnGrupo").hide();
		$("#tipo_alumno").prop('disabled', true);
		$("#curp").prop('disabled', true);
		$("#num_expediente").prop('disabled', true);
	}
	else if(actionAlumno == "saveAlumno"){
		processPage = "processAlumnos.php";
		dataSend.opt = "getIni";
		$("#btnSaveAlumno").show();
		$("#btnUpdateAlumno").hide();
		$("#btnCancelAlumno").show();
		$("#dvBtnAlumno").show();
		$("#dvBtnGrupo").hide();
	}
	else if(actionAlumno=="regAlumnoGrupo"){
		processPage = "processGrupos.php";
		dataSend.opt = "getIni";
		$("#titlePageAlumno").html("Agregar alumno al grupo");
		$("#btnAddAlumnoGrupo").show();
		$("#btnCancelAlumnoGrupo").show();
		$("#dvBtnAlumno").hide();
		$("#dvBtnGrupo").show();
	}
	else{
		processPage = "processAlumnos.php";
		dataSend.opt = "getIni";
		$("#btnSaveAlumno").hide();
		$("#btnUpdateAlumno").hide();
		$("#btnCancelAlumno").show();
		$("#dvBtnAlumno").show();
		$("#dvBtnGrupo").hide();
	}

	//console.log(dataSend.opt);
	processData();

	
});
