
$(function() {

	var dataSend = {};
	var timeout = 100000;

	//#################################################//
	//Funcion borrar
	jQuery.fn.borrar = function(){
		$(this).each (function() {
			$($(this)).val('');
		});
		}
	//#################################################//

	//#################################################//
	function validData(){
		
		if($("#usuario").val()==""){
			$('#dvMsg').html('<span class="alert alert-danger">Debe indicar un usuario registrado.</span>');
			$("#usuario").focus();
			return false;
		}
		
		if($("#password").val()==""){
			$('#dvMsg').html('<span class="alert alert-danger">Debe indicar su  contraseña.</span>');
			$("#password").focus();
			return false;
		}
		
		return true;
	}
	//#################################################//
	
	//#################################################//
	
	$("#btnLogin").click(function(){
		
		
		if(!validData()){
			return false;
		}
		
		dataSend=$('#frmLogin').serializeArray();
		
		$("#usuario").attr("disabled", true);
		$("#password").attr("disabled", true);
		$("#btnLogin").attr("disabled", true);
		$("#dvMsg").empty();
		
		processData();
		return false;
	});
	//#################################################//

	//#################################################//
	
	function processData(){

		$.ajax({
			type: "POST",
			url: 'login.php',
			data: dataSend,
			dataType: "json",
			timeout: timeout,
			beforeSend: function() {

			},
			success: function(json) {


				$("#usuario").attr("disabled", false);
				$("#password").attr("disabled", false);
				$("#btnLogin").attr("disabled", false);
				
				
				if (json.error == "si") {
					$("#dvMsg").html(json.msg);
					console.log(json.debug);
					return false;
				}
				
				if(json.sucess=="si"){
					
					$("#dvMsg").html(json.msg);
					$("#dvMsg").show();
					window.location.replace("../inicio/index.php");

				}
				else{
					$("#dvMsg").html(json.msg);
					$("#dvMsg").show();
					$("#usuario").borrar();
					$("#password").borrar();
				}

				return false;

			},
			error: function(jqXHR, textStatus, errorThrown) {
			
				$("#usuario").attr("disabled", false);
				$("#password").attr("disabled", false);
				$("#btnLogin").attr("disabled", false);

				msg = "";
				if (textStatus == "timeout") {
					msg = "El tiempo de espera agotado: intentelo de nuevo";
				} else {
					msg = "Error en la conexión: intentelo de nuevo";
				}
				$("#dvMsg").html(msg);
				return false;
			}
		});
	}
	
	//#################################################//


});
