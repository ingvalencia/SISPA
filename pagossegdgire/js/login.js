$(function() {

	var dataSend = {};
	var timeout = 100000;

	$("#btnLogin").click(function(){
		
		dataSend=$('#frmLogin').serializeArray();
		
		$("#usuario").attr("disabled", true);
		$("#password").attr("disabled", true);
		$("#btnLogin").attr("disabled", true);
		$("#dvMsg").empty();
		$("#dvMsg").html("Procesando solicitud...");
		
		processData();
		return false;
	});
	
	function processData(){
		
		$.ajax({
			type: "POST",
			url: 'login.php',
			data: dataSend,
			dataType: "json",
			timeout: timeout,
			beforeSend: function() {
				//dialog.show();
			},
			success: function(json) {

				//dialog.close();
				$("#usuario").attr("disabled", false);
				$("#password").attr("disabled", false);
				$("#btnLogin").attr("disabled", false);

				if (json.error == "si") {
					$("#dvMsg").html(json.msg);
					console.log(json.debug);
					return false;
				}
				
				if(json.sucess=="si"){
					$("#dvMsg").html("Redireccionando...");
					//window.location = "../grupos/lista_grupos.php";
					window.location = json.url;
				}
				else{
					$("#dvMsg").html(json.msg);
				}
				
				
				return false;

			},
			error: function(jqXHR, textStatus, errorThrown) {
				//dialog.close();
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
	

	
});
