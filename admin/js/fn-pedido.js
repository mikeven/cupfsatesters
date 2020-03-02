/* ----------------------------------------------------------- */
/* Cupfsa Testers - Funciones sobre pedidos ------------------ */
/* ----------------------------------------------------------- */
/* ----------------------------------------------------------- */

$(document).ready(function() {
    // Invoca el envío del formulario	
	$("#bot_frm_pedido").on( "click", function(){
       $("#frm-pedido").submit();
    });

    $("#frm-pedido").on( "submit", function(e){
        e.preventDefault();
        cargarPedido( $(this) );
    });
});
/* ----------------------------------------------------------- */
function cargarPedido( frm ){
    //Envía al servidor la petición para reasignar contraseña de usuario   
    
    var wait = "...";
    var file_data = $('#archivo').prop('files')[0];
    var idpedido = $("#id_pedido").val();
    var form_data = new FormData();                  
    form_data.append('file', file_data );
    form_data.append('idp', idpedido);
    
    $.ajax({
        url: "data/data-pedido.php",
        type: 'POST',
        data: form_data,
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function () {
            $("#response-pedido").attr( "align", "center" );
            $("#response-pedido").html( wait ); 
            $("#bot_frm_pedido").fadeOut(200);
        },
        success: function( data ) {
            
            console.log(data);
            res = jQuery.parseJSON( data );

            $(".tabla_ctj").fadeIn();
            $("#response-pedido").attr( "align", "center" );
            $("#response-pedido").html( res.imp );
            $("#pedido_cotejado").html( res.ctj );
            $("#registro_pedido").html( res.reg_p );

            if( res.exito == 1 ){
                $("#vrepte").attr( "href", res.lnk_r );
                $(".post_carga_r").fadeIn(200);
            }
        }
    });
}
/* ----------------------------------------------------------- */