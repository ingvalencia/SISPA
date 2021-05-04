$(function() {
	
	var dataSend = {};
	var timeout = 100000;
	var solicitantes = 0;
	var fcorreo_e = "";
	var fnombre="";


	
	$("#btnSearch").click(function(){
		createGrid();
	});
	
	
	$("#btnExcel").click(function(){
		fnombre = $("#fnombre").val();
		fcorreo= $("#fcorreo").val();
		$.download("excel.php", "fnombre="+fnombre+"&fcorreo="+fcorreo, "POST");
	});
	
	function createGrid(){
		
		solicitantes.destroy();
		
		$("#solicitantes tbody").empty();
		
		fcorreo_e = $("#fcorreo_e").val();
		fnombre = $("#fnombre").val();
		
		solicitantes = $('#solicitantes').DataTable( {
			"processing": true
			,"bFilter": false
			,"serverSide": true
			,"language": opLanguage
			,"scrollX": true
			,"order": [[ 0, "asc" ]]
			,"ajax": {
				"url": "processListaSolicitantes.php"
				,"type": "POST"
				,"data": function ( d ) {
						d.fnombre = fnombre;
						d.fcorreo_e = fcorreo_e;
					}
			}
		} );
		
	}
	
	fcorreo_e = $("#fcorreo_e").val();
	fnombre = $("#fnombre").val();
		
	solicitantes = $('#solicitantes').DataTable( {
		"processing": true
		,"bFilter": false
        ,"serverSide": true
		,"language": opLanguage
		,"scrollX": true
		,"order": [[ 0, "asc" ]]
        ,"ajax": {
            "url": "processListaSolicitantes.php"
            ,"type": "POST"
			,"data": function ( d ) {
						d.fnombre = fnombre;
						d.fcorreo_e = fcorreo_e;
					}
        }
    } );
		

	/*
	$('#solicitantes tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
	    }
        else {
            solicitantes.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');

			id_concepto_pago = solicitantes.row( this ).data()[0];
			nom_concepto_pago = solicitantes.row( this ).data()[2];
			
        }
    });
	*/
	
	
		    
});
