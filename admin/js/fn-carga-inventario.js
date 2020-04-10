/* ----------------------------------------------------------- */
/* Cupfsa Testers - Funciones sobre carga de inventario ------ */
/* ----------------------------------------------------------- */
/* ----------------------------------------------------------- */

$(document).ready(function() {
    // Invoca el envío del formulario con el archivo	
	$("#bot_frm_pedido").on( "click", function(){
       $("#frm-inventario").submit();
    });

    // A partir del envío del formulario se invoca la función para cargar el archivo
    $("#frm-inventario").on( "submit", function(e){
        e.preventDefault();
        cargarInventario( $(this) );
    });

    // Invoca el la confirmación de carga de inventario
    $("#bot_conf_inventario").on( "click", function(){
       confirmarInventarioPorArchivo();
    });

    $("#sel_colab").on( "change", function(){
        // Evento que muestra/oculta lista de colaboradores dependiendo del motivo
        $("#listado_colaborador").val( $(this).find('option:selected').data('ls') );
        $("#id_colaborador").val( $(this).val() );
    });
});
/* ----------------------------------------------------------- */
function cargarInventario( frm ){
    //Envía al servidor la petición para reasignar contraseña de usuario   
    
    var wait            = "...";
    var file_data       = $('#archivo').prop('files')[0];
    var idcolaborador   = $("#sel_colab").val();
    var listado         = $("#sel_colab").find('option:selected').data('ls');
    var form_data       = new FormData();

    form_data.append( 'file', file_data );
    form_data.append( 'inv_colaborador', idcolaborador );
    form_data.append( 'listado_c', listado );

    $.ajax({
        url: "data/data-inventario.php",
        type: 'POST',
        data: form_data,
        contentType: false, cache: false, processData:false,
        beforeSend: function () {
            $("#response-inventario").attr( "align", "center" );
            $("#response-inventario").html( wait ); 
            $("#bot_frm_pedido").fadeOut(200);
            $("#frm-pedido").fadeOut(200);
            $(".sec_confirmacion").fadeOut();
        },
        success: function( data ) {
            
            console.log(data);
            res = jQuery.parseJSON( data );

            $("#response-carga-archivo").fadeIn();
            $("#bot_recargar").fadeIn();
            $("#response-inventario").html( res.imp );
            
            if( res.exito == 1 ){
                $("#tabla_inventario").fadeIn();
                $("#response-inventario").attr( "align", "center" );
                $("#inventario_archivo").html( res.tabla );
                
                $(".post_carga_r").fadeIn(200);
            }
        }
    });
}
/* ----------------------------------------------------------- */
function confirmarInventarioPorArchivo(){
    // Invoca al servidor para confirmar la carga de inventario por archivo 
    var wait = "...";
    var frm_items_arch  = $('#items_inventario_archivo').serialize();

    $.ajax({
        url: "data/data-inventario.php",
        type: 'POST',
        data:{ inventario_archivo: frm_items_arch },
        beforeSend: function () {
            $("#response-carga-archivo").html( wait ); 
        },
        success: function( data ) {            
            console.log(data);
            res = jQuery.parseJSON( data );
            if( res.exito == 1 ){
                $("#response-carga-archivo").html( res.imp );
            }
        }
    });
}
/* ----------------------------------------------------------- */