/* ----------------------------------------------------------- */
/* Cupfsa Testers - Funciones sobre lista de referencias ----- */
/* ----------------------------------------------------------- */
/* ----------------------------------------------------------- */

$(document).ready(function() {	
	// Evento para invocar la activación de un ítem	
	$(".opc_ajuste_inv").on( "click", function(){
        var datai = $(this).attr( "data-t" );
        $("#" + datai).slideToggle(50);
        //$( "#" + datai ).animate( {height:'toggle'}, 100 );
    });

    $(".ex_ajusteinv").on( "click", function(){
        // Evento para invocar restar unidades de inventario en ítems
        var iditem = $(this).attr( "data-item" );
        var cant = $( "#e" + iditem ).val();
        var idcol = $( "#idcolaborador" ).val();
        if( cant > 0 )
            restarUnidadesInventario( iditem, cant, idcol );
        else
            swal( "Indique una cantidad válida", "", "error" );
    });
});
/* ----------------------------------------------------------- */
function activacionItem( idc, valor ){
	//Invocación al servidor para modificar el estado de activación de un ítem
	var fs = $('#frm_ngrupocliente').serialize();
	
	$.ajax({
        type:"POST",
        url:"data/data-referencias.php",
        data:{ item_activacion: idc, val:valor },
        success: function( response ){
			console.log( response );
        }
    });
}
/* ----------------------------------------------------------- */
function restarUnidadesInventario( iditem, cant, idcol ){
    //Invocación al servidor para restar unidades de ítems en inventario
    //(Registrar salida de inventario)
    
    $.ajax({
        type:"POST",
        url:"data/data-inventario.php",
        data:{ restaritem: iditem, cantidad: cant, idc: idcol },
        success: function( response ){
            console.log( response );
            res = jQuery.parseJSON( response );
            if( res.exito == 1 ){
                swal( res.mje, "", "success" ).then(function() {
                    // Acción ejecutada después de cerrar alerta de mensaje
                    location.reload();
                });
            }
            else
                swal( res.mje, "", "error" );
        }
    });
}
/* ----------------------------------------------------------- */