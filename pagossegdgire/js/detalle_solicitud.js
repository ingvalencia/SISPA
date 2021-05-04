$(function() {
	
	var dataSend = {};
	var timeout = 100000;
	var solicitudes;
	var folio_sol = "";
	var cve_edo_pago="";
	var opt;
    
	$("#btnUpdateGrupo").hide();
    $("#btnEnableGrupo").hide();
    $("#btnListaAlumnos").hide();
	$("#btnDisableGrupo").hide();
	
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
		cve_edo_pago = $("#cve_edo_pago").val();
		$.download("excel.php", "folio_sol="+folio_sol+"&cve_edo_pago="+cve_edo_pago, "POST");
	});
	
	function createGrid(){
				
		solicitudes.destroy();
		
		$('#solicitudes tbody').empty();
		
		folio_sol = $("#folio_sol").val();
		cve_edo_pago = $("#cve_edo_pago").val();
		
		solicitudes = $('#solicitudes').DataTable( {
			"processing": true
			,"bFilter": false
			,"serverSide": true
			,"language": opLanguage
			,"scrollX": true
			,"order": [[ 0, "asc" ]]
			,"ajax": {
				"url": "processListSolicitudes.php"
				,"type": "POST"
				,"data": function ( d ) {
						d.folio_sol = folio_sol;
						d.cve_edo_pago = cve_edo_pago;
					}
			}
		} );

		
	}
	
	solicitudes = $('#solicitudes').DataTable( {
		"processing": true
		,"bFilter": false
        ,"serverSide": true
		,"language": opLanguage
		,"scrollX": true
		,"order": [[ 0, "asc" ]]
        ,"ajax": {
            "url": "processListSolicitudes.php"
            ,"type": "POST"
        }
    } );
		

	$('#solicitudes tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
	    }
        else {
            solicitudes.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

			folio_sol = solicitudes.row( this ).data()[0];
			
        }
    });
	
	
	$('#solicitudes tbody').dblclick(function() {
	
		dataSend = {};
		dataSend.opt="getDetalleSolicitud";
		dataSend.folio_sol = folio_sol;
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
		
		var row;
		
		$("#dvDetalle").show();
		
		$("#detalles tbody").empty();
		
		$.each(json.data.detalles, function(id, val){
			
			row = "<tr>";
			row+= "<td>"+val.id_concepto_pago+"</td>";
			row+= "<td>"+val.entregado_text+"</td>";
			row+= "<td>"+val.nom_concepto_pago+"</td>";
			row+= "<td>"+val.nom_area+"</td>";
			row+= "<td>"+val.importe+"</td>";
			row+= "<td>"+val.cant_requerida+"</td>";
			row+= "<td>"+val.precio_unitario+"</td>";
			row+= "<td>"+val.iva+"</td>";
			row+= "<td>"+val.monto_tot_conc+"</td>";
			row+= "</tr>";
			$("#detalles tbody").append(row);
      
		});
		
		$.each(json.data.montos, function(id, val){
			
			$("#"+id).html(val);
			
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

                if (json.error) {
                    dialog.alert(json.msg);
					console.log(json.debug);
                    return false;
                }
				
				switch(dataSend.opt){
					case "getIni":
						setIni(json);
						break;
					
				
					case "getDetalleSolicitud":

						setDetalle(json);
						
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
