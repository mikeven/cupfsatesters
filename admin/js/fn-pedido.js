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

    $("#bot_conf_pedido_bd").on( "click", function(){
       confirmarPedidoRegistrado();
    });

    $("#bot_conf_pedido_archivo").on( "click", function(){
       confirmarPedidoPorArchivo();
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
        contentType: false, cache: false, processData:false,
        beforeSend: function () {
            $("#response-pedido").attr( "align", "center" );
            $("#response-pedido").html( wait ); 
            $("#bot_frm_pedido").fadeOut(200);
            $(".sec_confirmacion").fadeOut();
        },
        success: function( data ) {
            
            console.log(data);
            res = jQuery.parseJSON( data );

            $(".tabla_ctj").fadeIn();
            
            $("#leyenda_check_pedido").fadeIn();
            $("#response-pedido").attr( "align", "center" );
            $("#response-pedido").html( res.imp );
            $("#pedido_cotejado").html( res.ctj_arc );
            $("#registro_pedido").html( res.ctj_ped );

            if( res.exito == 1 ){
                $("#cnf_pedido_archivo").fadeIn();
                $("#vrepte").attr( "href", res.lnk_r );
                $(".post_carga_r").fadeIn(200);
            }
        }
    });
}
/* ----------------------------------------------------------- */
function confirmarPedidoRegistrado(){
    // Invoca al servidor para confirmar pedido sin cambios por archivo
    var wait = "...";
    var idpedido = $("#id_pedido").val();

    $.ajax({
        url: "data/data-pedido.php",
        type: 'POST',
        data:{ pedido_confirmado: idpedido },
        beforeSend: function () {
            $("#response-pedido").html( wait ); 
            $("#cnf_pedido_db").fadeOut(200);
        },
        success: function( data ) {            
            console.log(data);
            res = jQuery.parseJSON( data );
            if( res.exito == 1 ){
                $("#response-confirmacion-pedido").html( res.imp );
            }
        }
    });
}
/* ----------------------------------------------------------- */
function confirmarPedidoPorArchivo(){
    // Invoca al servidor para confirmar pedido con los cambios indicados por archivo 
    var wait = "...";
    var frm_items_arch  = $('#items_pedido_archivo').serialize();

    $.ajax({
        url: "data/data-pedido.php",
        type: 'POST',
        data:{ pedido_archivo: frm_items_arch },
        beforeSend: function () {
            $("#response-pedido").html( wait ); 
            $("#cnf_pedido_archivo").fadeOut(200);
        },
        success: function( data ) {            
            console.log(data);
            res = jQuery.parseJSON( data );
            if( res.exito == 1 ){
                $("#response-confirmacion-pedido").html( res.imp );
            }
        }
    });
}
/* ----------------------------------------------------------- */