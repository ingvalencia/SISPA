$(function() {
	
	var dataSend = {};
	var timeout = 100000;
	var detalles;
	var id_solicitante = "";
	var id_concepto_pago = "";
	var folio_sol = "";
	var cve_edo_pago="";
	var fnombre ="";
	var opt;
   
	
	$("#folio_sol").mask("99999999999");
	

	$("#btnSearch").click(function(){
		
		$("#dvDetalle").hide();
		createGrid();
		
	});
	
	$("#btnClear").click(function(){
		
		$("#folio_sol").val("");
		$("#cve_edo_pago").val("");
		$("#dvDetalle").hide();
		createGrid();
		
	});
	
	$("#cve_edo_pago").change(function(){
		$("#dvDetalle").hide();
		createGrid();
	});
	
	$("#btnExcel").click(function(){
		folio_sol = $("#folio_sol").val();
		fnombre = $("#fnombre").val();
		cve_edo_pago = $("#cve_edo_pago").val();
		$.download("excel.php", "folio_sol="+folio_sol+"&fnombre="+fnombre+"&cve_edo_pago="+cve_edo_pago, "POST");
	});
	
	function createGrid(){
				
		detalles.destroy();
		
		$('#detalles tbody').empty();
		
		folio_sol = $("#folio_sol").val();
		cve_edo_pago = $("#cve_edo_pago").val();
		fnombre = $("#fnombre").val();
		
		detalles = $('#detalles').DataTable( {
			"processing": true
			,"bFilter": false
			,"serverSide": true
			,"language": opLanguage
			,"scrollX": true
			,"order": [[ 0, "asc" ]]
			,"ajax": {
				"url": "processListDetalleArea.php"
				,"type": "POST"
				,"data": function ( d ) {
						d.folio_sol = folio_sol;
						d.cve_edo_pago = cve_edo_pago;
						d.fnombre = fnombre;
					}
			}
		} );

		
	}
	
	detalles = $('#detalles').DataTable( {
		"processing": true
		,"bFilter": false
        ,"serverSide": true
		,"language": opLanguage
		,"scrollX": true
		,"order": [[ 0, "asc" ]]
        ,"ajax": {
            "url": "processListDetalleArea.php"
            ,"type": "POST"
        }
    } );
	
	detalles.column( 0 ).visible(false);

	$('#detalles tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
	    }
        else {
            detalles.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

			id_solicitante = detalles.row( this ).data()[0];
			folio_sol = detalles.row( this ).data()[1];
			id_concepto_pago = detalles.row( this ).data()[3];
			
        }
    });
	
	
	$('#detalles tbody').dblclick(function() {
	
		dataSend = {};
		dataSend.opt="getComentarioDetalleSolicitud";
		dataSend.folio_sol = folio_sol;
		dataSend.id_concepto_pago = id_concepto_pago;
		processData();		
		
	});
	

	
	function setIni(json){
		
		var item;
		
		$.each(json.estados, function(id, val){
			
			item = "<option value='"+val.cve_edo_pago+"'>"+val.nom_edo_pago+"</option>";
			$("#cve_edo_pago").append(item);
			
		});
		
	}
	
	function setDetalle(json){
		
		var msg;
		var comentario_resp;
		
		comentario_resp = json.data.detalles.comentario_resp;
		console.log(comentario_resp);
		if(comentario_resp=="null"){
			comentario_resp="";
		}
		
		msg = "";
		msg+= "<table>";
		msg+= "<tr>";
		msg+= "<td>Clave concepto</td>";
		msg+= "<td>:&nbsp;"+id_concepto_pago+"</td>";
		msg+= "</tr>";
		msg+= "<tr>";
		msg+= "<td>Folio solicitud</td>";
		msg+= "<td>:&nbsp;"+folio_sol+"</td>";
		msg+= "</tr>";
		msg+= "</table>";
		msg+= "<br><textarea class='form-control' cols='8' style='resize:vertical'>"+comentario_resp+"</textarea>";
		BootstrapDialog.show({
			title: 'Agregar comentario',
            message: msg,
            closable: true,
            closeByBackdrop: false,
            closeByKeyboard: false,
            buttons: [{
                label: 'Guardar',
				cssClass: 'btn-success',
                action: function(dWin) {
					var xmsg = "¿Está seguro que desea guardar los cambios?";
                    dialog.confirm(xmsg, function(xdWin){
						var com = dWin.getModalBody().find('textarea').val();
						dWin.close();
						xdWin.close();
						dataSend = {};
						dataSend.id_solicitante = id_solicitante;
						dataSend.folio_sol = folio_sol;
						dataSend.id_concepto_pago = id_concepto_pago;
						dataSend.opt = "saveComentarioDetalleResp";
						dataSend.comentario_resp = com;
						processData();
					});
                }
            }, {
                label: 'Cancelar',
				cssClass: 'btn-danger',
                action: function(dWin) {
                    dWin.close();
                }
            }]
        });
		
	}
	
	function processData() {

        $.ajax({
            type: "POST",
            url: 'processDetalleArea.php',
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
						setIni(json);
						break;
					
					case "getComentarioDetalleSolicitud":

						setDetalle(json);
						
						break;
					
					case "saveComentarioDetalleResp":
						
						dialog.message(json.msg);
						detalles.ajax.reload();
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
