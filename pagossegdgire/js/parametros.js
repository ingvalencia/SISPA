$(function() {
	
	var dataSend = {}
		,timeout = 100000
		,ct_usuarios = 0
		,id_usuario = 0
		,nombre_usr=""
		,isValid = false
		,opt
		,cveserie
		,smdf
		,iva
		,msg
		,xWin
		,apertura_sistema_temporal
		,cierre_sistema_temporal
		,date
		;

	
	msg = "¿Está seguro que desea realizar los cambios?";
	
	$("#btnLogin").click(function(){
		dataSend.opt = "validUser";
		dataSend.passwd = $("#passwd").val();
		processData();
	});
    
	$("#modalPassword").modal();
	

	//$("#cveserie").mask("9999");
	$("#iva").mask("99");
	$("#smdf").mask("9999.99");
	
	
	$('#dTime').datetimepicker({format : 'DD-MM-YYYY HH:mm'});
	$('#dTime_t1').datetimepicker({format : 'DD-MM-YYYY HH:mm'});
	$('#dTime_t2').datetimepicker({format : 'DD-MM-YYYY HH:mm'});
	
	
	
	
	
	
	
	
	$(".cls_cveserie").click(function(){
		
		BootstrapDialog.show({
			title: "Actualizar CVESERIE"
			,message: "<br><div class='col-md-2'>CVESERIE:</div><div class='col-md-10'><input class='form-control' value='"+$("#cveserie").html()+"'><br><br>"
			,buttons: [
				{
					label: 'aceptar'
					,cssClass: 'btn btn-outline btn-primary'
					,action: function(dWin){
						xWin = dWin;
						cveserie = dWin.getModalBody().find('input').val();
						dataSend={};
						dataSend.opt = "update_cveserie";
						dataSend.cveserie = cveserie;
						
						if(cveserie==""){
							dialog.message("Debe de agregar la serie", function(dWin){dWin.close();  });
							return false;
						}
						
						dialog.confirm(msg, function(dWin){xWin.close(); dWin.close();  processData();});
						
					}
				}
				,{
					label: 'Cancelar'
					,cssClass: 'btn btn-outline btn-danger'
					,action: function(dWin){dWin.close();}
				}
			]
		});
	

	});
	
	
	
	$(".cls_iva").click(function(){
		
		BootstrapDialog.show({
			title: "Actualizar el I.V.A"
			,message: "<br><div class='col-md-2'>IVA:</div><div class='col-md-10'><input class='form-control' value='"+$("#iva").html()+"'><br><br>"
			,buttons: [
				{
					label: 'aceptar'
					,cssClass: 'btn btn-outline btn-primary'
					,action: function(dWin){
						xWin = dWin;
						iva = dWin.getModalBody().find('input').val();
						dataSend={};
						dataSend.opt = "update_iva";
						dataSend.iva = iva;
						
						if(iva==""){
							dialog.message("Debe de agregar el iva", function(dWin){dWin.close();  });
							return false;
						}
						
						dialog.confirm(msg, function(dWin){xWin.close(); dWin.close();  processData();});
						
					}
				}
				,{
					label: 'Cancelar'
					,cssClass: 'btn btn-outline btn-danger'
					,action: function(dWin){dWin.close();}
				}
			]
		});
	

	});
	
	
	$(".cls_smdf").click(function(){
		
		BootstrapDialog.show({
			title: "Actualizar el SMDF"
			,message: "<br><div class='col-md-2'>SMDF:</div><div class='col-md-10'><input class='form-control' value='"+$("#smdf").html()+"'><br><br>"
			,buttons: [
				{
					label: 'aceptar'
					,cssClass: 'btn btn-outline btn-primary'
					,action: function(dWin){
						xWin = dWin;
						smdf = dWin.getModalBody().find('input').val();
						dataSend={};
						dataSend.opt = "update_smdf";
						dataSend.smdf = smdf;
						
						if(smdf==""){
							dialog.message("Debe de agregar el smdf", function(dWin){dWin.close();  });
							return false;
						}
						
						dialog.confirm(msg, function(dWin){xWin.close(); dWin.close();  processData();});
						
					}
				}
				,{
					label: 'Cancelar'
					,cssClass: 'btn btn-outline btn-danger'
					,action: function(dWin){dWin.close();}
				}
			]
		});

	});
	
	
	
	$("#apertura_sistema").click(function(){
		
		$("#modalDate_title").html("Actualizar apertura del sistema");
		$("#modalDate_data").html("apertura del sistema:");
		$('#modalDate').modal('show', {backdrop: 'static', keyboard: true});
		
		$("#dTime").val($(this).html());
		opt="update_apertura_sistema";
		
	});
	
	$("#cierre_sistema").click(function(){
		
		$("#modalDate_title").html("Actualizar cierre del sistema");
		$("#modalDate_data").html("cierre del sistema:");
		$('#modalDate').modal('show', {backdrop: 'static', keyboard: true});
		
		$("#dTime").val($(this).html());
		opt="update_cierre_sistema";
		
	});
	
	
	$("#btnUpdate").click(function(){
		
		$('#modalDate').modal('hide');
		date = $("#dTime").val();
		dataSend={};
		dataSend.opt = opt
		dataSend.date = date;
		processData();
						
	});
	
	
	$(".tmp_sistema").click(function(){
		
		$("#modalDate_temp_title").html("Actualizar cierre del sistema temporal");
		$("#modalDate_temp_data").html("cierre del sistema:");
		$('#modalDate_temp').modal('show', {backdrop: 'static', keyboard: true});
		
		$("#dTime_t1").val($("#apertura_sistema_temporal").html());
		$("#dTime_t2").val($("#cierre_sistema_temporal").html());
		opt="update_apertura_temporal";
		
	});
	
	
	$("#btnUpdate_temp").click(function(){
		
		apertura_sistema_temporal = $("#dTime_t1").val();
		cierre_sistema_temporal = $("#dTime_t2").val();
		
		dialog.confirm(msg, function(dWin){
			dWin.close();
			$('#modalDate_temp').modal('hide');
			dataSend={};
			dataSend.opt = "update_apertura_sistema_temporal"
			dataSend.apertura_sistema_temporal = apertura_sistema_temporal;
			dataSend.cierre_sistema_temporal = cierre_sistema_temporal;
			processData();
		});
		
		
	});
	
	
	
	$("#btnUpdParametros").click(function(){
		
		var msg;
		var opt;
		opt = "updParametros";
		
		//clearForm();
		
		
		msg = "¿Está seguro que desea realizar los cambios?";
		
		dataSend={};
		dataSend = $("#frmParametros").serializeArray();
		
		dataSend.opt = opt;
		dataSend.push({"name" : "opt", "value" : opt });
		
		dialog.confirm(msg, function(dialogWin){dialogWin.close();  processData(); });

	});
	
	
	$("#btnCloseParametros").click(function(){
		
		window.location = "./";
		
	});
	
	
	
	function processData() {

        $.ajax({
            type: "POST",
            url: 'processParametros.php',
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
					
					case "validUser":
						
						if(!json.valid){
							$("#msg_passwd").html(json.msg);
							return false;
						}
	
						$("#modalPassword").modal("hide");
						
						dataSend = {};
						dataSend.opt="getParametros";
						processData();
						
						$("#dvParametros").show();
						break;
						
					case "getParametros":
						
						$.each(json.parametros, function(id, val){
							$("#"+id).html(val.valor);
							console.log("#"+id+" => "+val.valor);
						});
						
						console.log("www");
						
						break;
						
					case "updParametros":
						
						dialog.message(json.msg);
						
						break;
					
					case "update_cveserie":
					
						dialog.message(json.msg);
						$("#cveserie").html(cveserie);
						
						break;
						
					case "update_iva":
						
						dialog.message(json.msg);
						$("#iva").html(iva);
						
						break;
						
					case "update_smdf":
						
						dialog.message(json.msg);
						$("#smdf").html(smdf);
						
						break;
						
					case "update_apertura_sistema":
						
						dialog.message(json.msg);
						$("#apertura_sistema").html(date);
						
						break;
						
					case "update_cierre_sistema":
						
						dialog.message(json.msg);
						$("#cierre_sistema").html(date);
						
						break;
					
					case "update_apertura_sistema_temporal":
						
						dialog.message(json.msg);
						$("#apertura_sistema_temporal").html(apertura_sistema_temporal);
						$("#cierre_sistema_temporal").html(cierre_sistema_temporal);
						
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
	
	dataSend = {};
	dataSend.opt="getParametros";
	processData();
	
	//$("#dvParametros").show();
						
	
});
