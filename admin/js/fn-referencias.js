/* ----------------------------------------------------------- */
/* Cupfsa Testers - Funciones sobre lista de referencias ----- */
/* ----------------------------------------------------------- */
/* ----------------------------------------------------------- */

$(document).ready(function() {	
	// Evento para invocar la activación de un ítem	
	$(".sel_act_item").on( "change", function(){
        var valor = $(this).val();
        var iditem = $(this).attr("data-id");
        activacionItem( iditem, valor );
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