var sendData = {};
var id_alumno = 0;
var timeout = 100000;
var ctNiveles;
var ctAreas;
var action;
$(function() {
	

	var id_instructor;
	var exitAlumno = false;
	var processPage = "";
	var action = "";
	var alumno;
	var opt="";
	

	$("#curp").mask("AAAA999999AAAAAA99");
	$("#num_expediente").mask("99999999");
	
	$("#dvNumExpediente").hide();
	$("#dvCurp").hide();
	$("#frmAlumno").hide();
	
	
	$("#btnAddAlumnoGrupo").hide();
	
	
	function clearFrmAlumno(){
		
		
		
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
		$('#frmAlumno').formValidation('resetForm');
		return false;
		$("#dvConteiner").empty();
		$("#dvConteiner").hide();
		$("#dvGrupo").show();
	});
	
	$("#btnCancelAlumno").click(function(){
		localStorage.removeItem("action");
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
	
		
		$("#frmAlumno").hide();
		$("#frmAlumno").find("input").val("");
		$("#curp").val("");
		$("#num_expediente").val("");
		
		if($("#tipo_alumno").val()==1){
			$("#dvNumExpediente").show();
			$("#dvCurp").hide();
			$("#dvProcedencia").show();
		}
		else if($("#tipo_alumno").val()==2){
			$("#dvNumExpediente").hide();
			$("#dvCurp").show();
			$("#dvProcedencia").show();
		}
		else{
			$("#dvNumExpediente").hide();
			$("#dvCurp").hide();
			$("#dvProcedencia").hide();
		}
		
		$("#btnAddAlumnoGrupo").hide();
		
	});
	
	
	$("#num_expediente").change(function(){
		
		dataSend = {};
		if(action!="getAlumno"){
			$("#frmAlumno").hide();
			$("#frmAlumno").find("input").val("");
		}
		dataSend.opt = "checkExpediente";
		dataSend.num_expediente = $("#num_expediente").val();
		processData();
	});
	
	$("#curp").change(function(){
		
		if(action!="getAlumno"){
			$("#frmAlumno").hide();
			$("#frmAlumno").find("input").val("");
		}

		dataSend = {};
		dataSend.opt = "checkCurp";
		dataSend.curp = $("#curp").val();
		processData();
	});
	
	function setDataAlumno(xAlumno){
		
		console.log("'"+xAlumno.num_expediente+"'");
		alumno = xAlumno;
		id_alumno=xAlumno.id_alumno;
		$("dvCurp").show();
		
		$("#num_expediente").val(xAlumno.num_expediente);
		
		//if($("#num_expediente").val()==" "){
		alert("'"+xAlumno.num_expediente+"'");
		if(xAlumno.num_expediente==null){
			$("dvNumExpediente").show();
			alert("dfsdf");
		}
		
		if(xAlumno.num_expediente==""){
			$("#tipo_alumno").val(2);
		}
		else{
			$("dvNumExpediente").show();
			$("#num_expediente").val(xAlumno.num_expediente);
			$("#tipo_alumno").val(1);
		}
		
		if($("#num_expediente").val()==" "){
			$("dvNumExpediente").show();
		}
		
		$("#curp").show();
		$("#curp").val(xAlumno.curp);
		$("#curp").prop('disabled', true);
		
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
		
		$("#frmAlumno").show();
	}
	
	
	function actionAfterCheckAlumnoGrupo(data){
		
		if(json.exist=="si"){
			existAlumno = true;
			setDataAlumno(data.alumno);
			$("#frmAlumno").find("input").prop('disabled', true);
			$("#frmAlumno").find("select").prop('disabled', true);

		}
		else{
			id_alumno = 0;
			existAlumno = false;
			$("#frmAlumno").find("input").prop('disabled', false);
			$("#frmAlumno").find("select").prop('disabled', false);
		}
		
		$("#btnAddAlumnoGrupo").show();
	}
	
	function actionAfterCheckAlumno(json){
		
		if(dataSend.opt == "checkExpediente"){
			
			if(json.exist=="si"){
				if(action == "getAlumno"){
					$("#curp").val(alumno.curp);
					$("#num_expediente").val(alumno.num_expediente);
				}
				else{
					$("#curp").val("");
					$("#num_expediente").val("");
				}
				msg="Ya existe un alumno con este número de expediente, intente con otro";
				dialog.message(msg);
				$("#btnSaveAlumno").hide();
				return false;
			}
			
			
			if(json.validExp == "si"){
				$("#frmAlumno").show();
				setDataAlumno(json.alumno);
				$("#btnSaveAlumno").show();
				return false;
			}

			$("#curp").val("");
			$("#num_expediente").val("");
			msg="No existe un profesor con este número de expediente, itente con otro";
			dialog.message(msg);
			$("#btnSaveAlumno").hide();
			return false;
		}


		
		if(json.exist=="si"){
			if(action == "saveAlumno"){
				$("#curp").val("");
				$("#num_expediente").val("");
				$("#btnSaveAlumno").hide();
			}
			else{
				$("#curp").val(alumno.curp);
				$("#num_expediente").val(alumno.num_expediente);
				$("#btnSaveAlumno").hide();
			}
			
			msg="Ya existe un alumno con esta curp, intente con otro";
			dialog.message(msg);
			return false;
		}
		
		if(action == "saveAlumno"){
			$("#frmAlumno").show();
			$("#btnSaveAlumno").show();
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
		
            procedencia: {
                
                validators: {
                    notEmpty: {
                        message: 'Debe indicar la procedencia'
                    },
                    stringLength: {
                        min: 6,
                        max: 200,
                        message: 'Debe de ser mayor a 6 caracteres'
                    }
                }
            },
			nombre_persona: {
                
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el nombre de persona'
                    },
                    stringLength: {
                        min: 6,
                        max: 200,
                        message: 'Debe de ser mayor a 6 caracteres'
                    }
                }
            },
			ap_paterno: {
                
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el ap paterno'
                    },
                    stringLength: {
                        min: 6,
                        max: 200,
                        message: 'Debe de ser mayor a 6 caracteres'
                    }
                }
            },
			ap_materno: {
                
                validators: {
                    notEmpty: {
                        message: 'Debe indicar el ap materno'
                    },
                    stringLength: {
                        min: 6,
                        max: 200,
                        message: 'Debe de ser mayor a 6 caracteres'
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
			case "regNewAlumnoGrupo":
				msg = "Esa seguro que desea agregar al grupo y guardar los datos del nuevo alumno : <br>"+xnom;
				break;
		}
		
		dialog.confirm(msg, function(dialogWin){dialogWin.close(); processData(); });
		
    });

	function afterSaveAlumno(xMsg){
		
		msg  = xMsg+"<br><br>";
		msg += "Quiere agregar otro alumno";
		
		dialog.confirm(msg
		,function(dialogWin){
			dialogWin.close();
			clearFrmAlumno();
			
			$("#curp").val("");
			$("#dvCurp").hide();
			$("#num_expediente").val("");
			$("#dvNumExpediente").hide();
			$("#frmAlumno").hide();
			$("#tipo_alumno").val(0);
			$("#btnSaveAlumno").hide();
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
		$("#curp").prop('disabled', false);
		$("#dvNumExpediente").show();
		$("#num_expediente").prop('disabled', false);
		
		
	}
	
	function afterUpdateAlumno(msg){
		
		localStorage.removeItem("action");
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
	
		if(!existAlumno){
			opt= "regNewAlumnoGrupo";
			getDataAlumno();
			dataSend.opt = opt;
			dataSend.push({"name" : "opt", "value" : opt });
			$('#frmAlumno').formValidation('validate');
		}
		else{
			opt = "regAlumnoGrupo";
			dataSend = {};
			dataSend.opt = opt;
			dataSend.id_alumno = id_alumno;
			dataSend.id_grupo = localStorage.getItem('id_grupo');
			msg  = "Esta seguro que quiere agregar al grupo el alumno:";
			msg += "<br>".$("#nombre_persona").val();
			msg += " ".$("#ap_paterno").val();
			msg += " ".$("#ap_materno").val();
			dialog.confirm(msg, function(dialogWin){ dialogWin.close(); processData();})
		}
	});















	
	function processData(){
		
		$.ajax({
			type: "POST",
			url: processPage,
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
					case "checkExpediente":
					case "checkCurp":
						if(action == "addAlumno"){ actionAfterCheckAlumnoGrupo(json); }
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
	
	if(localStorage.getItem('action')!=null){
		action = localStorage.getItem('action');
		if((action != "getAlumno")&&(action != "addAlumno")&&(action != "saveAlumno")){
			action == "saveAlumno";
		}
	}
	else{
		action = "saveAlumno";
	}
	
	
	if(action=="getAlumno"){
		processPage = "processAlumnos.php";
		dataSend.opt = action;
		dataSend.id_alumno = localStorage.getItem('id_alumno');
		$("#titlePageAlumno").text("Actualizar datos Alumno");
		$("#btnSaveAlumno").hide();
		$("#btnUpdateAlumno").show();
		$("#btnCancelAlumno").show();
		$("#dvBtnAlumno").show();
		$("#dvBtnGrupo").hide();
	}
	else if(action == "saveAlumno"){
		processPage = "processAlumnos.php";
		dataSend.opt = "getIni";
		$("#btnSaveAlumno").hide();
		$("#btnUpdateAlumno").hide();
		$("#btnCancelAlumno").show();
		$("#dvBtnAlumno").show();
		$("#dvBtnGrupo").hide();
	}
	else if(action=="addAlumno"){
		processPage = "processGrupos.php";
		dataSend.opt = "getIniAlumno";
		$("#titlePageAlumno").html("Agregar alumno al grupo");
		$("#btnAddAlumnoGrupo").hide();
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
