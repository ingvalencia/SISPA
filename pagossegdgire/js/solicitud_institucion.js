var dataSend = {};
$(function() {
	
	var timeout = 100000;
	var id_alumno = 0;
	var msg = "";
	var solIns;
	var dias1;
	var dias2;
	var fecha_inicio1;
	var fecha_inicio2;
	var fecha_fin1;
	var fecha_fin2;
	var hora_inicio1;
	var hora_inicio2;
	var hora_fin1;
	var hora_fin2;
	
	$("#dvChangeHorarios").hide();
	$("#hora_inicio1").mask("99:99");
	$("#hora_inicio2").mask("99:99");
	$("#hora_fin1").mask("99:99");
	$("#hora_fin2").mask("99:99");
	
	
	$("#fecha_inicio1").datepicker({ format: "yyyy-mm-dd", language: "es", autoclose: true});
	$("#fecha_inicio2").datepicker({ format: "yyyy-mm-dd", language: "es", autoclose: true});
	$("#fecha_fin1").datepicker({ format: "yyyy-mm-dd", language: "es", autoclose: true});
	$("#fecha_fin2").datepicker({ format: "yyyy-mm-dd", language: "es", autoclose: true});
	
	
	$("#btnChangeHorario").click(function(){
	
		var dias;
		
		$("#fecha_inicio1").val(solIns.fecha_propuesta_inicio1);
		$("#fecha_fin1").val(solIns.fecha_propuesta_fin1);
		$("#fecha_inicio2").val(solIns.fecha_propuesta_inicio2);
		$("#fecha_fin2").val(solIns.fecha_propuesta_fin2);
		$("#hora_inicio1").val(solIns.horario_propuesta_inicio1);
		$("#hora_fin1").val(solIns.horario_propuesta_fin1);
		$("#hora_inicio2").val(solIns.horario_propuesta_inicio2);
		$("#hora_fin2").val(solIns.horario_propuesta_fin2);
		
		dias = solIns.dias_propuesta1;
		dias = dias.split(",");
		
		$('#diasProp1 td').each(function() {
			if($(this).hasClass('tdSelect')){
				$(this).removeClass('tdSelect');
			}
		});
		
		$.each(dias,function(i, val){
			$('#diasProp1 td').each(function() {
				//$(this).removeClass('tdSelect');
				if($(this).attr("dia") == val){
					$(this).addClass('tdSelect');
				}
			});
		});
		
		dias = solIns.dias_propuesta2;
		dias = dias.split(",");
		
		$('#diasProp2 td').each(function() {
			if($(this).hasClass('tdSelect')){
				$(this).removeClass('tdSelect');
			}
		});
			
		$.each(dias,function(i, val){
			$('#diasProp2 td').each(function() {
				//$(this).removeClass('tdSelect');
				if($(this).attr("dia") == val){
					$(this).addClass('tdSelect');
				}
			});
		});
		
		
		$("#dvShowHorarios").hide();
		$("#dvChangeHorarios").show();
		
	});
	
	
	
	/********************* ***********************/
	
	function valid_fecha_curso(){
		
		var fc_inicio;
		var fc_fin;
		
		$("#fecha_inicio").removeClass("error");
		$("#fecha_fin").removeClass("error");
		$("#fecha_inicio_error").html("");	
		
		fc_inicio = $("#fecha_inicio").val();
		fc_fin = $("#fecha_fin").val();
		if(fc_inicio==""){
			$("#fecha_inicio").addClass("error");
			$("#fecha_fin").val("");
			$("#fecha_inicio_error").html("Debe de indicar una fecha de inicio");
			return false;
		}
		
		if(fc_fin==""){
			$("#fecha_fin").addClass("error");
			$("#fecha_fin").val("");
			$("#fecha_inicio_error").html("Debe de indicar una fecha final");
			return false;
		}

		if(new Date(fc_inicio) > new Date(fc_fin)){
			$("#fecha_inicio").addClass("error");
			$("#fecha_fin").val("");
			$("#fecha_inicio_error").html("La fecha inicial debe de ser menos a la final");
			return false;
		}
		
		return true;
	}
	
	function check_hora(hr){
	
		var hr = hr.split(":");
		var d;
		var h;
		
		h = parseInt(hr[0]);
		m = parseInt(hr[1]);
		if(h>23){ return false;}
		if(m>56){ return false;}
		return true;
	}
	
	function valid_horario(){
		var hora_inicio;
		var hora_fin;
		
		$("#hora_inicio").removeClass("error");
		$("#hora_fin").removeClass("error");
		$("#horario_error").html("");
		
		if(!check_hora($("#hora_inicio").val())){
			$("#horario_error").show();
			$("#hora_inicio").addClass("error");
			$("#hora_inicio").val("");
			$("#horario_error").html("Debe de ser una hora valida");
			return false;
		}
		
		if(!check_hora($("#hora_fin").val())){
			$("#horario_error").show();
			$("#hora_fin").addClass("error");
			$("#hora_fin").val("");
			$("#horario_error").html("Debe de ser una hora valida");
			return false;
		}
		
		hora_inicio = $("#hora_inicio").val();
		if(hora_inicio==""){
			$("#hora_inicio").addClass("error");
			$("#hora_fin").val("");
			$("#horario_error").html("Debe de indicar una hora de inicio");
			return false;
		}
		
		hora_fin = $("#hora_fin").val();
		if(hora_fin==""){
			$("#hora_fin").addClass("error");
			$("#hora_fin").val("");
			$("#horario_error").html("Debe de indicar una hora final");
			return false;
		}
		
		if(new Date('01/01/2011 '+hora_inicio) > new Date('01/01/2011 '+hora_fin)){
			$("#hora_inicial").addClass("error");
			$("#hora_fin").val("");
			$("#horario_error").html("La hora inicial debe de ser menos a la final");
			return false;
		}
		
		return true;
	}
	
	/***********************************/

	$('.tDias tbody').on( 'click', 'td', function () {
		
        if ( $(this).hasClass('tdSelect') ) {
			$(this).removeClass('tdSelect');
        }
        else {
			$(this).addClass('tdSelect');
        }
    } );
	
	
	$("#btnCHSave").click(function(){
		
		msg="¿Está seguro que quiere cambiar las fechas y horarios?";
		dialog.confirm(msg, function(dWin){dWin.close();
		
			fecha_inicio1 = $("#fecha_inicio1").val();
			fecha_inicio2 = $("#fecha_inicio2").val();
			fecha_fin1 = $("#fecha_fin1").val();
			fecha_fin2 = $("#fecha_fin2").val();
			
			$("#fecha_propuesta_inicio1").html(fecha_inicio1);
			$("#fecha_propuesta_fin1").html(fecha_fin1);
			$("#fecha_propuesta_inicio2").html(fecha_inicio2);
			$("#fecha_propuesta_fin2").html(fecha_fin2);
			
			dias1 = "";
			$('#diasProp1 td').each(function() {
				if($(this).hasClass('tdSelect')){
					if(dias1!=""){ dias1+=","; }
					dias1 += $(this).attr('dia');
				}
			});
			$("#dias_propuesta1").html(dias1);
			
			dias2 = "";
			$('#diasProp2 td').each(function() {
				if($(this).hasClass('tdSelect')){
					if(dias2!=""){ dias2+=","; }
					dias2 += $(this).attr('dia');
				}
			});
			$("#dias_propuesta2").html(dias2);
			
			hora_inicio1 = $("#hora_inicio1").val();
			hora_fin1 = $("#hora_fin1").val();
			
			hora_inicio2 = $("#hora_inicio2").val();
			hora_fin2 = $("#hora_fin2").val();
			
			
			$("#horario_propuesta_inicio1").html(hora_inicio1);
			$("#horario_propuesta_inicio2").html(hora_inicio2);
			$("#horario_propuesta_fin1").html(hora_fin1);
			$("#horario_propuesta_fin2").html(hora_fin2);

			dataSend={};
			dataSend.opt = "changeFechas";
			dataSend.id_solicitud_institucion = id_solicitud_institucion;
			dataSend.fecha_propuesta_inicio1 = fecha_inicio1;
			dataSend.fecha_propuesta_inicio2 = fecha_inicio2;
			dataSend.fecha_propuesta_fin1 = fecha_fin1;
			dataSend.fecha_propuesta_fin2 = fecha_fin2;
			dataSend.dias_propuesta1=dias1;
			dataSend.dias_propuesta2=dias2;
			dataSend.horario_propuesta_inicio1 = hora_inicio1;
			dataSend.horario_propuesta_inicio2 = hora_inicio2;
			dataSend.horario_propuesta_fin1 = hora_fin1;
			dataSend.horario_propuesta_fin2 = hora_fin2;
			processData();
			
			
			
		});
	});
	
	$("#btnCHCancel").click(function(){
		$("#dvShowHorarios").show();
		$("#dvChangeHorarios").hide();
	});
	
	$("#btnDelAlumno").click(function(){
		
		msg = "¿Está seguro que desea eliminar el alumno de la solicitud?";
		
		dialog.confirm(msg, function(dWin){
			dWin.close();
			$("#btnDelAlumno").hide();
			dataSend = {};
			dataSend.opt = "delAlSolInstitucion";
			dataSend.id_solicitud_institucion = id_solicitud_institucion;
			dataSend.id_alumno = id_alumno;
			processData()
		});
		
		
	});
	
	var lista_alumnos = $('#lista_alumnos').DataTable( {
        "processing": true
		,"bFilter": false
        ,"serverSide": true
		,"language": opLanguage
        ,"ajax": {
            "url": "processListaAlumnos.php"
            ,"type": "POST"
			,"data": function ( d ) {
                d.id_solicitud_institucion = id_solicitud_institucion;
            }
        }
    } );
	
		
		
	lista_alumnos.column( 0 ).visible(false);
	
	$('#lista_alumnos tbody').on( 'click', 'tr', function () {
            lista_alumnos.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
			
			console.log("sss");
			if(lista_alumnos.row( this ).data()!=undefined){
			$("#btnDelAlumno").show();
				id_alumno = lista_alumnos.row( this ).data()[0];
			}

    } );
	
	function setData(sol){
		
		solIns = sol;
		
		$.each(sol,function(i, val){
			$("#"+i).html(val);
		});
	}
	
	
	function processData() {

        $.ajax({
            type: "POST",
            url: 'processSolicitudes.php',
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
					case "getSolInstitucion":
						setData(json.solicitud);
						break;
					case "delAlSolInstitucion":
						lista_alumnos.row('.selected').remove().draw( false );
						dialog.message(json.msg);
						break;
					case "changeFechas":
						dialog.message(json.msg,function(dWin){dWin.close(); location.reload();});
						
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
	
	processData();
	
});
