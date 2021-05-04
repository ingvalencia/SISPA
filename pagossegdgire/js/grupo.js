var dataSend = {};
var id_grupo = 0;
var timeout = 100000;

$(function() {

	var lista_instructores;
	var lista_cursos;
	var lista_alumnos_grupo;
    var id_curso = 0;
    var nombre_curso = "";
	var nombre_grupo = "";
	var monto_curso = "";
	var nombre_alumno = "";
    var id_instructor = 1;
	var min_participantes;
	var max_participantes;
    var xGrupo;
	var rowAlumno;

	$("#dvDataGrupo").hide();
	$("#dvGrupo").hide();
	
	
    /*
	$("#fecha_inicio").datetimepicker({
		language:  'es',
		format: 'yyyy-mm-dd',
		pickTime: false,
		autoclose: 1,
		keyboardNavigation: false
	});
	*/
	
	
	/*
	$("#fecha_fin").datetimepicker({
		language:  'es',
		format: 'yyyy-mm-dd',
		pickTime: false,
		autoclose: 1,
		keyboardNavigation: false
	});
	*/
	
	$("#fecha_fin").datepicker({ format: "yyyy-mm-dd", language: "es", autoclose: true});
	$("#fecha_inicio").datepicker({ format: "yyyy-mm-dd", language: "es", autoclose: true});
	
	

	$("#fecha_inscripcion_ini").datepicker({ format: "yyyy-mm-dd", language: "es", autoclose: true});
	$("#fecha_inscripcion_fin").datepicker({ format: "yyyy-mm-dd", language: "es", autoclose: true});
	
    //$("#fecha_inscripcion_ini").datetimepicker({language:  'es',format: 'yyyy-mm-dd'});
    //$("#fecha_inscripcion_fin").datetimepicker({language:  'es',format: 'yyyy-mm-dd'});

	//$("#hora_inicio").mask("99:99");
	$("#hora_inicio").mask("99:99");
	$("#hora_fin").mask("99:99");
	
	
	//format: 'yyyy-mm-dd hh:ii'
	
	/*
	$('#hora_inicio').datetimepicker({
		language:  'es',
		format: "hh:ii",
		autoclose: 1,
		startView: 1,
	});
	
	$('#hora_fin').datetimepicker({
		language:  'es',
		format: "hh:ii",
		autoclose: 1,
		startView: 1,
	});
	*/
	
	$("#min_participantes").mask("999");
	$("#max_participantes").mask("999");
	
    $("#btnSaveGrupo").hide();
	//$("#btnCancelGrupo").hide();
    $("#btnUpdateGrupo").hide();
	$("#btnDeleteAlumno").hide();
	$("#dvConteiner" ).hide();

    $("#dvLista_cursos").hide();
    $("#dvLista_instructores").hide();
	$("#btnSelectInstructor").hide();

    $("#dvDataAlumno").hide();
	
	
	$("#btnEdithGrupo").click(function(){
		$("#dvDataGrupo").hide();
		$("#dvGrupo").show();
	});
	
	$("#btnBackGrupo").click(function(){
		window.location = "lista_grupos.php";
	});
	
	
	function clearFrmGrupo(){
		id_grupo=0;
		id_instructor = 1;
		nombre_curso="";
		
		$("#frmGrupo").find("input").val("");
		$("#id_modalidad").val("0");
		$("#dias").val("0");
		$("#nombre_curso").html("");
		$("#monto_curso").html("");
		$("#nombre_instructor").html("");
		$("#min_participantes").val(min_participantes);
		$("#max_participantes").val(max_participantes);
	}
	
	function setIni(data){
			
		$("#id_modalidad").append("<option value='0'>Seleccione una modalidad</option>");
		$.each(data.lstModalidades, function(id, valor) {
			$("#id_modalidad").append("<option value='"+id+"'>"+valor.descripcion_modalidad+"</option>");
		});
		
		$("#dias").append("<option value='0' selected>Seleccione los días</option>");
		$.each(data.lstDias, function(id, valor) {
			$("#dias").append("<option value='"+id+"'>"+valor+"</option>");
		});
		
		min_participantes = data.min_participantes;
		max_participantes = data.max_participantes;
		
		$("#min_participantes").val(min_participantes);
		$("#max_participantes").val(max_participantes);
	}

    function setData(data) {

		setIni(data);
		xGrupo = data.grupo;
		//console.log(xGrupo);
		id_grupo = xGrupo.id_grupo;
		id_curso = xGrupo.dCurso.id_curso;
   		nombre_grupo = xGrupo.nombre_grupo;
		nombre_curso = xGrupo.nombre_curso;
		monto_curso = xGrupo.monto_curso;
    	id_instructor = xGrupo.dInstructor.id_instructor;
	
		grupo = data.grupo;
        $.each(grupo, function(id, valor) {
            if (id == "dCurso") {}
			 else if (id == "dInstructor") {}
			 else if (id == "dModalidad") {} 
			 else if (id == "dSede") {} 
			 else if (id == "dAlumnos") {
                var tr = "";

                $.each(valor, function(index, xAlumno) {
                    tr = "<tr>";
                    tr += "<td>" + xAlumno.id_alumno + "</td>";
					tr += "<td>" + xAlumno.num_expediente + "</td>";
					tr += "<td>" + xAlumno.curp + "</td>";
                    tr += "<td>" + xAlumno.nombre_persona + "</td>";
                    tr += "<td>" + xAlumno.ap_paterno + "</td>";
                    tr += "<td>" + xAlumno.ap_materno + "</td>";
					tr += "<td>" + xAlumno.genero + "</td>";
					tr += "<td>" + xAlumno.telefono + "</td>";
					tr += "<td>" + xAlumno.celular + "</td>";
					tr += "<td>" + xAlumno.email + "</td>";
                    tr += "</tr>";
                    $("#lista_alumnos_grupo").append(tr);
                });

                initListGrupo();
            } else {
				if(id=="nombre_instructor"){
					$("#" + id).text(valor);
					$("#" + id+"_label").text(valor);
				}
				else if(id=="nombre_curso"){
					$("#" + id).text(valor);
					$("#" + id+"_label").text(valor);
				}
				else if(id=="monto_curso"){
					$("#" + id).text(valor);
					$("#" + id+"_label").text(valor);
				}
				else if(id=="modalidad"){
					$("#id_modalidad").val(grupo.dModalidad.id_modalidad);
					$("#id_modalidad_label").text(grupo.modalidad);
				}
				else {
					$("#" + id).val(valor);
					$("#" + id+"_label").text(valor);
				}
                
                //$("#" + id).text(valor);
                //console.log(id + ": " + valor);
            }
        });
		
		$("#dvDataGrupo").show();

    }

	/**************  validaciones  *************/
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
	
	function valid_fecha_inscripcion(){
		var fc_inicio;
		var fc_fin;
		
		$("#fecha_inscripcion_ini").removeClass("error");
		$("#fecha_inscripcion_fin").removeClass("error");
		$("#fecha_inscripcion_error").html("");
		
		fc_inicio = $("#fecha_inscripcion_ini").val();
		if(fc_inicio==""){
			$("#fecha_inscripcion_ini").addClass("error");
			$("#fecha_inscripcion_fin").val("");
			$("#fecha_inscripcion_error").html("Debe de indicar una fecha de inicio");
			return false;
		}
		
		fc_fin = $("#fecha_inscripcion_fin").val();
		if(fc_fin==""){
			$("#fecha_inscripcion_fin").addClass("error");
			$("#fecha_inscripcion_fin").val("");
			$("#fecha_inscripcion_error").html("Debe de indicar una fecha final");
			return false;
		}
		
		if(new Date(fc_inicio) > new Date(fc_fin)){
			$("#fecha_inscripcion_ini").addClass("error");
			$("#fecha_inscripcion_fin").val("");
			$("#fecha_inscripcion_error").html("La fecha inicial debe de ser menos a la final");
			return false;
		}
		
		return true;
	}
	
	function valid_nombre_grupo(){
		$("#nombre_grupo_error").html("");	
		
		nombre_grupo = $("#nombre_grupo").val();
		if(nombre_grupo==""){
			$("#nombre_grupo_error").html("Debe de indicar un nombre de grupo");
			return false;
		}
		
		return true;
	}
	
	function valid_nombre_curso(){
		$("#nombre_curso_error").html("");
		if(id_curso==0){
			$("#nombre_curso_error").html("Debe de seleccionar un curso");
			return false;
		}
		
		return true;
	}
	
	
	function valid_modalidad(){
		$("#id_modalidad_error").html("");
		
		valor = $("#id_modalidad").val();
		if(valor=="0"){
			$("#id_modalidad_error").html("Debe seleccionar una modalidad");
			return false;
		}
		
		return true;
	}
	
	function valid_dias(){
		$("#dias_error").html("");
		
		valor = $("#dias").val();
		if(valor=="0"){
			$("#dias_error").html("Debe seleccionar los dias del curso");
			return false;
		}
		
		return true;
	}
	
	function valid_num_participantes(){
		$("#num_participantes_error").html("");
		$("#max_participantes").removeClass("error");
		$("#min_participantes").removeClass("error");
		
		minPart = $("#min_participantes").val();
		maxPart = $("#max_participantes").val();
		if((minPart=="")||(maxPart=="")){
			$("#num_participantes_error").html("Debe indicar el mínimo y máximo de participantes");
			return false;
		}
		else if(parseInt(minPart) > parseInt(maxPart)){
			$("#max_participantes").addClass("error");
			$("#num_participantes_error").html("El número máximo de participantes debe ser mayor al mínimo");
			return false;
		}
		
		return true;
	}
	
	function validFormGrupo(){
			
		var flag = true;
		
		if(!valid_nombre_grupo()){ flag = false; }
		if(!valid_nombre_curso()){ flag = false; }
		if(!valid_modalidad()){ flag = false; }
		if(!valid_dias()){ flag = false; }
		if(!valid_num_participantes()){ flag = false; }
		if(!valid_fecha_curso()){ flag = false; }
		if(!valid_horario()){ flag = false; }
		if(!valid_fecha_inscripcion()){ flag = false; }
		
		return flag;
	}
	
	
	
	/****************  fin validaciones  ****************/
	
	
	
	/**************** eventos change ****************/
	$("#nombre_grupo").change(function(){
		valid_nombre_grupo();
	});
	
	$("#nombre_curso").change(function(){
		valid_nombre_curso();
	});
	
	$("#id_modalidad").change(function(){
		valid_modalidad();
	});
	
	$("#dias").change(function(){
		valid_dias();
	});
	
	$("#fecha_inicio").change(function(){
		valid_fecha_curso();
	});
	
	$("#fecha_fin").change(function(){
		valid_fecha_curso();
	});
	
	$("#hora_inicio").change(function(){
		valid_horario();
	});
	
	$("#hora_fin").change(function(){
		valid_horario();
	});
	
	$("#min_participantes").change(function(){
		valid_num_participantes();
	});
	
	$("#max_participantes").change(function(){
		valid_num_participantes();
	});
	
	$("#fecha_inscripcion_ini").change(function(){
		valid_fecha_inscripcion();
	});
	
	$("#fecha_inscripcion_fin").change(function(){
		valid_fecha_inscripcion();
	});
	
	/************   fin eventos change  ************/
	
	
	/************** eventos click *****************/
    $("#btnCancelGrupo").click(function() {
        location.reload();
    });
	

	function beforeSaveGrupo(dialogWin){
		
		dialogWin.close();
		
		dataSend = $("#frmGrupo").serializeArray();
		
		dataSend.opt = "saveGrupo";	   
		dataSend.push({"name" : "opt", "value" : "saveGrupo"});
		dataSend.push({"name" : "id_curso", "value" : id_curso});
		//dataSend.push({"name" : "id_modalidad", "value" : id_curso});
		dataSend.push({"name" : "id_instructor", "value" : id_instructor});
		//return false;
		processData();		
	}
	
	function beforeUpdateGrupo(dialogWin){
			
		dialogWin.close();
		dataSend = $("#frmGrupo").serializeArray();
		dataSend.opt = "updateGrupo";	   
		dataSend.push({"name" : "opt", "value" : "updateGrupo"});
		dataSend.push({"name" : "id_grupo", "value" : id_grupo});
		dataSend.push({"name" : "id_curso", "value" : id_curso});
		//dataSend.push({"name" : "id_modalidad", "value" : id_curso});
		dataSend.push({"name" : "id_instructor", "value" : id_instructor});
		
		processData();		
	}
	
	function afterSaveGrupo(json){
	
		msg = json.msg + " <br><br>Desea agregar otro grupo";
		dialog.confirm(msg 
			,function(dialogWin){ 
				clearFrmGrupo();
				dialogWin.close();
			}
			,function(dialogWin){ 
				window.location = "../grupos/lista_grupos.php";
				dialogWin.close();
			});
		
	}
	
	
    $("#btnSaveGrupo").click(function() {

		if(!validFormGrupo()){
			return false;
		}
		
		msg = "Esta seguro que desea agregar el grupo: <br><br>"+nombre_grupo;
		dialog.confirm(msg, beforeSaveGrupo);

    });
	
	$("#btnUpdateGrupo").click(function() {

		console.log("1");
		if(!validFormGrupo()){
			console.log("2");
			return false;
		}
		console.log("3");
		
		msg = "Esta seguro que desea actualizar los datos del grupo: <br><br>"+nombre_grupo;
		dialog.confirm(msg, beforeUpdateGrupo, function(dialogWin){ dialogWin.close(); });

    });


	/******************* fin eventos click *******/
	

    

    /********		inicia cursos			*********/
    lista_cursos = $('#lista_cursos').DataTable({
        "processing": true
        ,"bInfo": false
        ,"bFilter": false
		,"pagingType": "simple"
		//,"bLengthChange": false
		,"language": opLanguage
		,"serverSide": true
        ,"ajax": {
            "url": "../cursos/processListaCursos.php",
            "type": "POST",
			"data": function ( d ) {
                d.vigente = 1;
            }
        }
    });

    lista_cursos.column(0).visible(false);
    lista_cursos.column(2).visible(false);
    lista_cursos.column(3).visible(false);
    lista_cursos.column(4).visible(false);
    //lista_cursos.column(5).visible(false);
    //lista_cursos.column(6).visible(false);

	$("#btnSelectCurso").click(function() {

        if (nombre_curso != "") {
            $("#nombre_curso").text(nombre_curso);
			$("#monto_curso").text(monto_curso);
            $("#dvLista_cursos").hide();
        }
		
		valid_nombre_curso();

    });
	
    $("#btnShowCursos").click(function() {

        $("#dvLista_cursos").show();
        $("#dvLista_instructores").hide();
		
        $("#dvDataAlumno").hide();

    });

    $('#lista_cursos tbody').on('click', 'tr', function() {
	
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
            //$("#btnSelectCurso").hide();
        } else {
            lista_cursos.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $("#btnSelectCurso").show();

            id_curso = lista_cursos.row(this).data()[0];
            nombre_curso = lista_cursos.row(this).data()[1];
			
			$("#nombre_curso").html(nombre_curso);
			//nombre_curso = "wwwww";
			monto_curso = lista_cursos.row(this).data()[3];
            //console.log( lista_cursos.row( this ).data()[0] );
        }
    });
    /********		fin cursos			*********/


    /********		inicia instructores			*********/
    lista_instructores = $('#lista_instructores').DataTable({
        "processing": true
		,"bInfo": false
        ,"bFilter": false
        //,"bPaginate": true
		,"pagingType": "simple"
		//,"bLengthChange": false
		,"language": opLanguage
        //,"bSort": false
        ,"serverSide": true
        ,"ajax": {
            "url": "../instructores/processListaInstructores.php"
            ,"type": "POST"
        }
    });

    lista_instructores.column(0).visible(false);
	lista_instructores.column(1).visible(false);
    lista_instructores.column(5).visible(false);
    lista_instructores.column(6).visible(false);
    lista_instructores.column(7).visible(false);
    lista_instructores.column(8).visible(false);

    $("#btnSelectInstructor").click(function() {

        if (nombre_instructor != "") {
            $("#nombre_instructor").text(nombre_instructor);
            $("#dvLista_instructores").hide();
        }

    });

    $("#btnShowInstructores").click(function() {

        $("#dvLista_instructores").show();
        $("#dvLista_cursos").hide();
		
        $("#dvDataAlumno").hide();

    });
	
    $('#lista_instructores tbody').on('click', 'tr', function() {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
            //$("#btnSelectInstructor").hide();
        } else {
            lista_instructores.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $("#btnSelectInstructor").show();

            id_instructor = lista_instructores.row(this).data()[0];
            nombre_instructor = lista_instructores.row(this).data()[2] + " ";
            nombre_instructor += lista_instructores.row(this).data()[3] + " ";
            nombre_instructor += lista_instructores.row(this).data()[4] + " ";
            //console.log( lista_cursos.row( this ).data()[0] );
        }
    });
    /********		fin instructores			*********/


    /********		inicia alumnos			*********/
    
	function afterDeleteAlumno(msg){
		dialog.message(msg);
		lista_alumnos_grupo.row(rowAlumno).remove().draw();
		$("#btnDeleteAlumno").hide();
		clearDataAlumno();
        $("#dvDataAlumno").hide();
	}
	$("#btnDeleteAlumno").click(function(){
	
		msg = "Esta seguro que quiere eliminar del grupo al alumno: <br><br>"+nombre_alumno;
		dialog.confirm(msg
		,function(dialogWin){
			dialogWin.close();
			dataSend.opt = "deleteAlumno";
			dataSend.id_grupo = id_grupo;
			dataSend.id_alumno = id_alumno;
			processData();
		}
		,function(dialogWin){ dialogWin.close();});
	
	});
	
	$("#btnAddAlumno").click(function(){
	
		if(actionGrupo == "addGrupo"){
			return false;
		}
	
		localStorage.setItem('actionAlumno', 'regAlumnoGrupo');
		localStorage.setItem('id_grupo', id_grupo);

		$("#dvDataGrupo").hide();
	
		$("#dvConteiner" ).empty();
  		$("#dvConteiner" ).load("../alumnos/frmAlumno.php");
		$("#dvConteiner" ).show();
	
	});
	
	
	function clearDataAlumno(){
		$("#dvDataAlumnolabel").text("");
	}
	
	function initListGrupo() {
		if(actionGrupo=="updateGrupo"){
			lista_alumnos_grupo = $('#lista_alumnos_grupo').DataTable({
				"bInfo": false
				,"bFilter": false
				,"bPaginate": false
				,"bSort": false
				,"font-size": "0.8em"
			});
			
			lista_alumnos_grupo.column(0).visible(false);
		}
		else{
			$("#dvLista_alumnos_grupo").html("Primero debe de crear el grupo para poder agregar alumnos");
		}
    }

    $('#lista_alumnos_grupo tbody').on('click', 'tr', function() {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
            //$("#btnDeleteAlumno").hide();
        } else {
            lista_alumnos_grupo.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
            $("#btnDeleteAlumno").show();

            $("#dvDataAlumno").show();
            id_alumno = lista_alumnos_grupo.row(this).data()[0];
			nombre_alumno  = lista_alumnos_grupo.row(this).data()[3];
			nombre_alumno += " "+lista_alumnos_grupo.row(this).data()[4];
			nombre_alumno += " "+lista_alumnos_grupo.row(this).data()[5];
					
			clearDataAlumno();
            $.each(xGrupo.dAlumnos[id_alumno], function(id, valor) {
                $("#" + id).text(valor);

            });
			
			rowAlumno = this;

        }
    });
    /********		fin alumnos			*********/



	
	
	
	
	
    

    function processData() {

        $.ajax({
            type: "POST",
            url: 'processGrupos.php',
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
					case "getGrupo":
						setData(json.data);
						break;
					case "saveGrupo":
						afterSaveGrupo(json);
						break;
					case "deleteAlumno":
						afterDeleteAlumno(json.msg);
						break;
					case "updateGrupo":
						dialog.message(json.msg, function(dialogWin){ location.reload(); dialogWin.close();	});
						break;
					case "getIni":
						$("#dvGrupo").show();
						setIni(json.data);
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
                console.log(msg);
                dialog.alert(msg);//showMessage(msg);
                return false;
            }
        });
    }


console.log("1");
    if (dataSend.opt != undefined) {
		console.log("2");
        if (dataSend.opt == "getGrupo") {
			console.log("3");
			$("#titlePage").text("Actualizar datos grupo");

            $("#btnSaveGrupo").hide();
            $("#btnUpdateGrupo").show();
        } else {
			console.log("4");
            $("#btnSaveGrupo").show();
            $("#btnUpdateGrupo").hide();
			
        }
    }
	else{
		console.log("5");
		dataSend.opt = "getIni";
		$("#btnSaveGrupo").show();
	}
	
	console.log("6");
	if(actionGrupo=="saveGrupo"){
		console.log("7");
		$("#btnShowAlumnos").hide();
	}
	
	console.log("8");
	processData();
	
	/*
	$('#myModal').on('shown.bs.modal', function () {
  $('#myInput').focus()
})

$('#sss').click(function () {
	$("#bodyModal" ).empty();
  	$("#bodyModal" ).load("../alumnos/frmAlumno.php");
  
})
*/
	
	/*
	BootstrapDialog.show({
            message: '<div id="dvFrmModal"></div>',
            onhide: function(dialogRef){
                var fruit = dialogRef.getModalBody().find('input').val();
                if($.trim(fruit.toLowerCase()) !== 'banana') {
                    alert('Need banana!');
                    return false;
                }
            },
            buttons: [{
                label: 'Close',
                actionGrupo: function(dialogRef) {
                    dialogRef.close();
                }
            }]
        });
	*/
	
	//$("#dvFrmModal" ).empty();
  	//$("#dvFrmModal" ).load("../alumnos/frmAlumno.php");
	
	
});
