/*
var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
if (isMobile) {
	window.location.replace("mobile.html");
} 
*/
$(document).ready(function() {

		//Aqui marco la fila al cambiar los valores de los dropdowns
	    $('select').change(function(){
		var valor = 0;
			
		var classes = $(this).attr('class').split(' ');
		txtClass = classes[0];	//la clase 0 (la primera) es el id
		$("." + txtClass).each(function(){
		    valor += Number($(this).val());
		});
		
		var papa = $(this).parents("tr");
		
		if(valor > 0){
			$(this).parents("tr").addClass("marcada");
		}else{
			$(this).parents("tr").removeClass("marcada");
		}
		//Hasta aqui
		
	    var sum = 0;	
	    $('select :selected').each(function() {
	        sum += Number($(this).val());
	    });
	    $("#cant").val(sum);
		});
		
		$('#titFragancias').click(function(){
			$('#Fragancias').toggle(1000, "swing");
		});
		
		$('#titMaquillaje').click(function(){
			$('#Maquillaje').toggle(1000, "swing");
		});
		
		$('#titSkincare').click(function(){
			$('#Skincare').toggle(1000, "swing");
		});

		$('#titPLV').click(function(){
			$('#PLV').toggle(1000, "swing");
		});

		$('#titInsumos').click(function(){
			$('#Insumos').toggle(1000, "swing");
		});

        $(window).scroll(function(){
            if ($(this).scrollTop() > 100) {
                $('.scrollup').fadeIn();
				$('.scrolldown').fadeIn();
            } else {
                $('.scrollup').fadeOut();
				 $('.scrolldown').fadeOut();
            }
        });
  
        $('.scrollup').click(function(){
            $("html, body").animate({ scrollTop: 0 }, 600);
            return false;
        });
		
		$('.scrolldown').click(function(){
			$("html, body").animate({ scrollTop: $(document).height() }, 600);
            return false;
        });

});

function validar() {
	
	//Esta es la validacion de Skincare donde no puedes tomar el 100% de tu pedido de las misma linea
	//class selectSC es para tomar solo los selects de Skincare
	/*
	var linea;
	$('.selectSC').each(function() {
		
		linea = "";
		var suma = 0;
		var clase = "";
		var classes = $(this).attr('class').split(' ');
		clase = classes[1];	
		
		$("." + clase).each(function(){
		    suma += Number($(this).val());
		});
		
		if (suma >= <?php echo $unidades ?>) {
			linea = clase;
			return false;
		}
		
	});
	
	if (linea != "") {
		swal("No estamos listos", "No puedes pedir todas tus unidades de la misma línea: " + linea, "error");
		return false;	
	}
	//Hasta aqui
	*/
	
	var aux = Number($("#cant").val());
	/* no va para el primer envio
	if (aux < <?php echo $unidades ?>) {
        swal("No estamos listos", "Aún te quedan unidades por seleccionar.", "error");
        return false;
    }*/
	/* es para el 1er envio */
	if (aux == 0) {
        swal("No estamos listos", "No has hecho tu selección.", "error");
        return false;
    }
	/*
	if (aux > <?php echo $unidades ?>) {
        swal("No estamos listos", "Seleccionaste más unidades que las asignadas.", "error");
        return false;
    }
	*/
	
	$( "#btSig" ).hide( "slow", function() {
	  
	});

	$('select').each(function() {
	    
	   if (this.value == '0')
	        $(this).prop("disabled", true);
	   else
			$(this).prop("disabled", false);
	   
	});
	
	$( "#Listado" ).fadeOut( "slow", function() {
	    $( "#form1" ).submit();
	});
	
}