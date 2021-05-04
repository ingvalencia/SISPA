var dataSend = {};
$(function() {
    
	
var dataSend = {};
var timeout = 100000;
var id_solicitud = 0;
var rowSelect;
var lst_sol_profesores;
var dataSerialize = "";
var lstGrupos;
var fecha_solicitud;
var id_grupo;
var sid_grupo;

	$("#btnUpdateCurso").hide();
	$("#btnEnableCurso").hide();
	$("#btnDisableCurso").hide();
	
	$("#fecha_solicitud").datepicker({ format: "yyyy-mm-dd", language: "es", autoclose: true});
	
	var d = new Date();
	id_grupo="";
	fecha_solicitud = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate();
	$("#fecha_solicitud").val(fecha_solicitud);
	
	
	function cancelOption(dialogWin){
		dialogWin.close();
	}
	
	function aceptOption(dialogWin){
		dialogWin.close();
		processData();
	}
	
	function setData(grupos){
		lstGrupos = grupos;
		
		$("#id_grupo").empty();
		
		$("#id_grupo").append("<option value=''>Selecciona un grupo</option");
		
		$.each(lstGrupos, function(id, g){
		
			$("#id_grupo").append("<option value='"+id+"'>"+g.nombre_grupo+"</option");
			console.log(lstGrupos[id].nombre_grupo);
		
		});
	}
	
	$("#fecha_solicitud").change(function(){
		fecha_solicitud=$("#fecha_solicitud").val();;
		dataSend.opt = "getListaGrupoFecha";
		dataSend.fecha_solicitud = fecha_solicitud;
		processData();
		lst_sol_profesores.ajax.reload();
	});
	
	$("#id_grupo").change(function(){

		id_grupo = $(this).val();
		$("#nombre_curso").html("");
		$("#fecha_curso").html("");
		if( id_grupo !=""){ 
			$("#nombre_curso").html(lstGrupos[id_grupo].nombre_curso);
			$("#fecha_curso").html(lstGrupos[id_grupo].fecha_inicio + " al "+ lstGrupos[id_grupo].fecha_fin);
		}
		
		console.log(id_grupo);
		
		lst_sol_profesores.ajax.reload();
		
	});
	
	$("#btnUpdateCurso").click(function(){
		window.location="./cursos.php?action=update&id_curso="+id_curso;	
	});
	
	$("#btnDisableCurso").click(function(){
		msg  = "Esta seguro que desea deshabilitar el curso: <br><br>"+nombre_curso;
		msg += "<br><br><strong>Nota: Al deshabilitar el curso ya no lo podra seleccionar para nuevos grupos</strong>";
		dialog.confirm(msg, aceptOption, cancelOption);
		dataSend.opt="disableCurso";
		dataSend.id_curso = id_curso;
	});
	
	$("#btnEnableCurso").click(function(){
		msg  = "Esta seguro que desea habilitar el curso: <br><br>"+nombre_curso;
		msg += "<br><br><strong>Nota: Al habilitar el curso podra selccionarlo para nuevos grupos</strong>";
		dialog.confirm(msg, aceptOption, cancelOption);
		dataSend.opt="enableCurso";
		dataSend.id_curso = id_curso;
	});
	
	lst_sol_profesores = $('#lst_sol_profesores').DataTable({
        "processing": true
		,"bFilter": false
        ,"serverSide": true
		,"language": opLanguage
		,"aoColumns": [
			{'sTitle': '#'}
			,{'sTitle': 'id_grupo'}
			,{'sTitle': 'Grupo'}
			,{'sTitle': '#Exp'}
			,{'sTitle': 'Nombre'}
			,{'sTitle': 'Fol. pago'}
			,{'sTitle': 'Cod. desc'}
			,{'sTitle': '% Desc'}
			,{'sTitle': 'Descuento'}
			,{'sTitle': 'Doc',
				'render': function(data, type, row ) {
					return '<a href="processSolicitudes.php?opt=getDocumentoProfesor&id_solicitud='+row[0]+'" download ><img src="../image/doc.png" /></a>'
				}
				//,"defaultContent": "<img>Click!</img>"
			}
			,{'sTitle': 'Estatus'}
			,{'sTitle': 'Fc. Solicitud'}]
        ,"ajax": {
            "url": "processListaSolProfesores.php"
            ,"type": "POST"
			,"data": function(d){
				d.id_grupo = id_grupo;
				d.fecha_solicitud = fecha_solicitud;
			}
        }
	});
	
	/*
	$('#lst_sol_profesores ').on( 'click', 'img', function () {
		
		return false;
		
		dataSend = {};
		dataSend.opt = "getDocumentoProfesor";
		dataSend.id_solicitud = $(this).attr("row");
		//processData();
		document.location="processSolicitudes.php?opt=getDocumentoProfesor&id_solicitud=47";
		return false;
    } );
	*/
	lst_sol_profesores .column(1).visible(false);
	
	//lista_cursos.column( 0 ).visible(false);
	//lst_sol_profesores.column( 5 ).visible(false);
	

	$('#lst_sol_profesores tbody').dblclick(function() {
		//window.location="./cursos.php?action=update&id_curso="+id_curso;
	});

	$('#lst_sol_profesores tbody').on( 'click', 'tr', function () {
		rowSelect = this;
        if ( $(this).hasClass('selected') ) {
			/*
            $(this).removeClass('selected');
			$("#btnUpdateCurso").hide();
			$("#btnEnableCurso").hide();
			$("#btnDisableCurso").hide();
			*/
        }
        else {
            lst_sol_profesores.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
			$("#btnUpdateCurso").show();
			if(lst_sol_profesores.row( this ).data()[4]=="Si"){
				$("#btnEnableCurso").hide();
				$("#btnDisableCurso").show();
			}
			else{
				$("#btnEnableCurso").show();
				$("#btnDisableCurso").hide();
			}
			
			sid_grupo =lst_sol_profesores.row( this ).data()[1];
			
			$("#nombre_curso").html(lstGrupos[sid_grupo].nombre_curso);
			$("#fecha_curso").html(lstGrupos[sid_grupo].fecha_inicio + " al "+ lstGrupos[sid_grupo].fecha_fin);
			
			//nombre_curso = lst_sol_profesores.row( this ).data()[1];
			//console.log( lista_cursos.row( this ).data()[0] );
        }
    } );	
	
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
					case "getListaGrupoFecha":
						setData(json.grupos);
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

	dataSend.opt = "getListaGrupoFecha";
	dataSend.fecha_solicitud = $("#fecha_solicitud").val();
	processData();
		    
});
