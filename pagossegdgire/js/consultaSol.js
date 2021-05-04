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
	
	$("#folio_sol1").mask("99999999999");
	$("#folio_sol2").mask("99999999999");
	$("#fec_sol1").datepicker({ format: "yyyy-mm-dd", language: "es", autoclose: true});
	$("#fec_sol2").datepicker({ format: "yyyy-mm-dd", language: "es", autoclose: true});
	$("#fec_actualizacion1").datepicker({ format: "yyyy-mm-dd", language: "es", autoclose: true});
	$("#fec_actualizacion2").datepicker({ format: "yyyy-mm-dd", language: "es", autoclose: true});
	$("#fec_factura1").datepicker({ format: "yyyy-mm-dd", language: "es", autoclose: true});
	$("#fec_factura2").datepicker({ format: "yyyy-mm-dd", language: "es", autoclose: true});
	

	$(".search").change(function(){
		createGrid();
	});
	
	$("#btnSearch").click(function(){
		
		$("#dvDetalle").hide();
		createGrid();
		
	});
	
	$("#btnClear").click(function(){
		
		$("#fec_sol1").val("");
		$("#fec_sol2").val("");
		$("#fec_actualizacion1").val("");
		$("#fec_actualizacion2").val("");
		$("#folio_sol1").val("");
		$("#folio_sol2").val("");
		$("#folio_actualizacion1").val("");
		$("#folio_actualizacion2").val("");
		$("#fec_factura1").val("");
		$("#fec_factura2").val("");
		$("#folio_sol1").val("");
		$("#folio_sol2").val("");
		$("#id_concepto_pago").val("");
		$("#cve_edo_sol").val("");
		$("#cve_edo_pago").val("");
		$("#id_area").val("");
		createGrid();
		
	});
	
	$("#cve_edo_pago").change(function(){
		//$("#dvDetalle").hide();
		createGrid();
	});
	
	$("#btnExcel").click(function(){
		var cad  = "";
		fec_sol1 = $("#fec_sol1").val();
		fec_sol2 = $("#fec_sol2").val();
		fec_actualizacion1 = $("#fec_actualizacion1").val();
		fec_actualizacion2 = $("#fec_actualizacion2").val();
		fec_factura1 = $("#fec_factura1").val();
		fec_factura2 = $("#fec_factura2").val();
		folio_sol1 = $("#folio_sol1").val();
		folio_sol2 = $("#folio_sol2").val();
		id_concepto_pago = $("#id_concepto_pago").val();
		cve_edo_sol = $("#cve_edo_sol").val();
		cve_edo_pago = $("#cve_edo_pago").val();
		id_area = $("#id_area").val();
		
		cad+="fec_sol1="+fec_sol1
		cad+="&fec_sol2="+fec_sol2
		cad+="&fec_actualizacion1="+fec_actualizacion1
		cad+="&fec_actualizacion2="+fec_actualizacion2
		cad+="&fec_factura1="+fec_factura1
		cad+="&fec_factura2="+fec_factura2
		cad+="&folio_sol1="+folio_sol1
		cad+="&folio_sol2="+folio_sol2
		cad+="&id_concepto_pago="+id_concepto_pago
		cad+="&cve_edo_sol="+cve_edo_sol
		cad+="&cve_edo_pago="+cve_edo_pago
		cad+="&id_area="+id_area
		
		$.download("excel.php", cad, "POST");
	});
	
	function createGrid(){
				
		solicitudes.destroy();
		
		$('#solicitudes tbody').empty();
		
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
						d.fec_sol1 = $("#fec_sol1").val();
						d.fec_sol2 = $("#fec_sol2").val();
						d.fec_actualizacion1 = $("#fec_actualizacion1").val();
						d.fec_actualizacion2 = $("#fec_actualizacion2").val();
						d.fec_factura1 = $("#fec_factura1").val();
						d.fec_factura2 = $("#fec_factura2").val();
						d.folio_sol1 = $("#folio_sol1").val();
						d.folio_sol2 = $("#folio_sol2").val();
						d.id_concepto_pago = $("#id_concepto_pago").val();
						d.cve_edo_sol = $("#cve_edo_sol").val();
						d.cve_edo_pago = $("#cve_edo_pago").val();
						d.id_area = $("#id_area").val();
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
		
		$.each(json.edo_pago, function(id, val){
			item = "<option value='"+val.cve_edo_pago+"'>"+val.nom_edo_pago+"</option>";
			$("#cve_edo_pago").append(item);
		});
		
		$.each(json.edo_sol, function(id, val){
			item = "<option value='"+val.cve_edo_sol+"'>"+val.nom_edo_sol+"</option>";
			$("#cve_edo_sol").append(item);
		});
		
		$.each(json.conceptos, function(id, val){
			item = "<option value='"+val.id_concepto_pago+"'>"+val.id_concepto_pago+" - "+val.nom_concepto_pago+"</option>";
			$("#id_concepto_pago").append(item);
		});
		
		$.each(json.areas, function(id, val){
			item = "<option value='"+val.id_area+"'>"+val.nom_area+"</option>";
			$("#id_area").append(item);
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
