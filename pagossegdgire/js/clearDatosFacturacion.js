$(function() {
	
	var dataSend = {}
		,timeout = 100000
		,datos_facturacion = 0
		,fcorreo_e = ""
		,fnombre=""
		,isValid = true
		,id_solicitante
		,rfc
		;
	
	$("#btnSearch").click(function(){
		$("#btnDeleteRFC").hide();
		createGrid();
	});
	
	function createGrid(){
		
		datos_facturacion.destroy();
		
		$("#datos_facturacion tbody").empty();
		
		rfc = $("#rfc").val();
		fcorreo_e = $("#fcorreo_e").val();
		fnombre = $("#fnombre").val();
		
		datos_facturacion = $('#datos_facturacion').DataTable( {
			"processing": true
			,"bFilter": false
			,"serverSide": true
			,"language": opLanguage
			,"scrollX": true
			,"order": [[ 0, "asc" ]]
			,"ajax": {
				"url": "processListaRFC.php"
				,"type": "POST"
				,"data": function ( d ) {
						d.rfc = rfc;
						d.fnombre = fnombre;
						d.fcorreo_e = fcorreo_e;
					}
			}
		} );
		
		datos_facturacion.column(0).visible(false);
		
	}
	
	datos_facturacion = $('#datos_facturacion').DataTable( {
		"processing": true
		,"bFilter": false
        ,"serverSide": true
		,"language": opLanguage
		,"scrollX": true
		,"order": [[ 0, "asc" ]]
        ,"ajax": {
            "url": "processListaRFC.php"
            ,"type": "POST"
        }
    } );
	
	datos_facturacion.column(0).visible(false);
	
	$('#datos_facturacion tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
	    }
        else {
            datos_facturacion.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
			$("#btnDeleteRFC").show();

			id_solicitante = datos_facturacion.row( this ).data()[0];
			rfc = datos_facturacion.row( this ).data()[2];
			fnombre = datos_facturacion.row( this ).data()[3];
			fcorreo = datos_facturacion.row( this ).data()[1];
			//login = ct_usuarios.row( this ).data()[1];
        }
    });
	
	$("#btnDeleteRFC").click(function(){
		
		dialog.confirm("¿Esta seguro que quiere eliminar el RFC: <strong>"+rfc+"</strong> del solicitante <strong>"+fcorreo	+"</strong>?"
			,function(dWin){
				dataSend = {};
				dataSend.opt = "deleteRFC"
				dataSend.id_solicitante = id_solicitante;
				dataSend.rfc = rfc;
				processData();
				dWin.close();
			}
		);
	});
	
	
	
	
	
	
	
	
	function processData() {

        $.ajax({
            type: "POST",
            url: 'processRFC.php',
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
                    return false;
                }
				
				switch(dataSend.opt){
					
					case "deleteRFC":
						
						dialog.message(json.msg);
						createGrid();
						$("#btnDeleteRFC").hide();

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
	
	
	
	
	
	
	
	
});
