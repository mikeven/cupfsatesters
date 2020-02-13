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
            /*res = jQuery.parseJSON(response);
            if( res.exito == 1 ){ 
               notificar( "Clientes", res.mje, "success" );
            }*/
        }
    });
}