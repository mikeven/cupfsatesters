<?php
ini_set( 'display_errors', 1 );
	
session_start();
//Reviso si la sesion caducó
if(!isset($_SESSION['idp'])) 
	header('Location: login.php?s=0');

require ('bd.php');

$idpersona = $_SESSION["idp"];
$nombre = $_SESSION["nombre"];
$email = $_SESSION["email"];
$unidades = $_SESSION['unidades'];
$FirstDay = $_SESSION["firstday"];  
$LastDay = $_SESSION["lastday"];  
 
//Verifico si ya hizo pedido
$sql = "SELECT * FROM Pedido where IdColaborador=$idpersona and Confirmado=1 and (Fecha BETWEEN '$FirstDay' AND '$LastDay')";
//echo $sql;
$Rs = mysqli_query ($dbh, $sql);
$row = mysqli_fetch_assoc($Rs); 
$rows = mysqli_num_rows($Rs);
if ($rows > 0) { 
	$pedido = $row['idPedido'];
	header("Location:verPedido.php?c=$idpersona&p=$pedido");
}
//Listo

//Cerrado
//header("Location:cerrado.html");
//exit;

//Saco la cantidad por si ya hizo un pedido preliminar
$sum = 0;
$sql = "SELECT SUM(Cantidad1) AS TotAcum FROM PedidoDetalle as d, Pedido as p where p.idPedido = d.idPedido and p.IdColaborador=$idpersona and (p.Fecha BETWEEN '$FirstDay' AND '$LastDay')"; 
$Rs = mysqli_query ($dbh, $sql);
$row = mysqli_fetch_assoc($Rs); 
if ($row['TotAcum'] > 0)
	$sum = $row['TotAcum'];
//Listo

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-118040064-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-118040064-3');
</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Testers Cupfsa</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="popup/popup.css" />

<link rel="stylesheet" type="text/css" href="css1.css" />

<style>
<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
	echo "select{font-size: 16px;}";
	}
//Hasta aqui
?>


.scrollup{
    width:40px;
    height:40px;
    position:fixed;
    bottom:90px;
    right:60px;
    display:none;
    text-indent:-9999px;
    background: url('images/icon_top.png') no-repeat;
}
.scrolldown{
    width:40px;
    height:40px;
    position:fixed;
    bottom:40px;
    right:60px;
    display:none;
    text-indent:-9999px;
    background: url('images/icon_bottom.png') no-repeat;
}
.marcada {
	background-color: rgb(195, 216, 231) !important;
}

.tooltip {
    position: fixed;
    top: 7em;
    right: 0em;
    color: #fff;
    background-color: #4b81e8;
    opacity: 1;
    font-size: 13px;
    width: 300px;
    height: 30px;
    padding: 28px;
    text-align: center;	
	z-index: 999;
	display: none;
}
.tooltip:before {
    content:"\A";
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 15px solid #4b81e8;
    position: absolute;
    left: 260px;
    top: -15px;
	z-index: 999;
	
}
</style>

<script>
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
		
		$('#titMakeup').click(function(){
			$('#Makeup').toggle(1000, "swing");
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

</script>

<script type="text/javascript">
function play(){
	var audio = document.getElementById("welcome");
	audio.play();
}
</script>
</head>

<body class="overlay">

<audio id="welcome">
  <source src="sounds/ariel.mp3" type="audio/mpeg">
</audio>

<!-- welcome popup -->
<div class="popScroll">
    <div class="popup">
        <span class="ribbon top-left ribbon-primary">
        <small>2019</small>
        </span> 
        <h1>Testers Cupfsa</h1>
        
        <p><br /><br />bienvenido <br> <?php echo $nombre ?><br /><br />
        RECUERDEN TOMAR SUS PEDIDO DE FORMA ADECUADA EN BASE A LAS NECESIDADES DE SU PUNTO DE VENTA</p>
        
        <div style="position: absolute; bottom: 30px; left:0; right:0">
            <a id="close" class="boxi" onclick="play()">Comenzar</a>
        </div>
    </div>
</div>
<!-- welcome popup -->

<?php require ('header.php'); ?>

<div class="tooltip" id="tt1">Aquí podrás ver la cantidad de unidades seleccionadas.</div>

<div><a href="#" class="scrollup">Scroll</a><a href="#" class="scrolldown">Scroll</a></div>
<form name="form1" id="form1" method="post" action="registraPedido.php">
<div id="Listado">

<!--Empieza Makeup -->
<div id="titMakeup" class="product-details__title">Maquillaje &#8693;</div>
<div id="Makeup" class="listadoPedido" style="display:none;">

	<table id="productos" align="center">
		<tr>
			<th colspan=3>Descripción</th>
			<th>Referencia</th>
		</tr>
<?php
$sql="SELECT * FROM Item  where Familia = 1 and Activo = 1"; 
//echo $sql;

$Rs = mysqli_query ($dbh, $sql);
$rows = mysqli_num_rows($Rs);

while($row=mysqli_fetch_assoc($Rs)){ 
	$id = $row['idItem'];
	$des1 = $row['Descripcion1'];
	$des2 = $row['Descripcion2'];
	$des3 = $row['Descripcion3'];
	$ref1 = $row['Referencia1'];
	
	//Empiezo loop del pedido de esa referencia
	$marcada = "";
	$cantidad1 = 0;
	$sql="SELECT * FROM Pedido as p, PedidoDetalle as d where p.idPedido = d.idPedido and p.idColaborador = $idpersona and d.idItem = $id and (p.Fecha BETWEEN '$FirstDay' AND '$LastDay')"; 
	$Rs2 = mysqli_query ($dbh, $sql);
	$rows2 = mysqli_num_rows($Rs2);
	
	if ($rows2 > 0) {
		$row2 = mysqli_fetch_assoc($Rs2); 
		$cantidad1 = $row2['Cantidad1'];
		$marcada = " class='marcada'";
	}

		echo "<tr" . $marcada . ">"; 
		echo "<td>" . $des1 . "</td>";
		echo "<td>" . $des2 . "</td>";
		echo "<td>" . $des3 . "</td>";
		if ($ref1 <> "-") {
				echo "<td align=right>" . $ref1 . " <select class='" . $id . "' name='s1-" . $id . "'>";
				for ($x = 0; $x <= $unidades - 1; $x++) {
					?>
					<option value=<?php echo $x ?><?php if($cantidad1 == $x): ?> selected="selected"<?php endif; ?>><?php echo $x ?></option>
					<?php
				}
				echo "</select></td>";
		} else 
				echo "<td>N/A</td>";	
		echo "</tr>";

	}
	?>	
		
	</table>
	
</div>

<div class="product-details__title" style="margin-top:10px"></div>
<!--Hasta aqui Makeup -->

<!--Empieza Skincare -->
<div id="titSkincare" class="product-details__title">Skincare &#8693;</div>
<div id="Skincare" class="listadoPedido" style="display:none;">

	<table id="productos" align="center">
		<tr>
			<th colspan=3>Descripción</th>
			<th>Referencia</th>
		</tr>
<?php
$sql="SELECT * FROM Item  where Familia = 2 and Activo = 1"; 
//echo $sql;

$Rs = mysqli_query ($dbh, $sql);
$rows = mysqli_num_rows($Rs);

while($row=mysqli_fetch_assoc($Rs)){ 
	$id = $row['idItem'];
	$des1 = $row['Descripcion1'];
	$des2 = $row['Descripcion2'];
	$des3 = $row['Descripcion3'];
	$ref1 = $row['Referencia1'];

	$des2pegada = "";
	$des2pegada = preg_replace('/\s+/', '', $des2);
	
	//Empiezo loop del pedido de esa referencia
	$marcada = "";
	$cantidad1 = 0;
	$sql="SELECT * FROM Pedido as p, PedidoDetalle as d where p.idPedido = d.idPedido and p.idColaborador = $idpersona and d.idItem = $id and (p.Fecha BETWEEN '$FirstDay' AND '$LastDay')"; 
	$Rs2 = mysqli_query ($dbh, $sql);
	$rows2 = mysqli_num_rows($Rs2);
	
	if ($rows2 > 0) {
		$row2 = mysqli_fetch_assoc($Rs2); 
		$cantidad1 = $row2['Cantidad1'];
		$marcada = " class='marcada'";
	}

		echo "<tr" . $marcada . ">"; 
		echo "<td>" . $des1 . "</td>";
		echo "<td>" . $des2 . "</td>";
		echo "<td>" . $des3 . "</td>";
		if ($ref1 <> "-") {
				echo "<td align=right>" . $ref1 . " <select class='" . $id . " " . $des2pegada . " selectSC' name='s1-" . $id . "'>";
				for ($x = 0; $x <= $unidades - 1; $x++) {
					?>
					<option value=<?php echo $x ?><?php if($cantidad1 == $x): ?> selected="selected"<?php endif; ?>><?php echo $x ?></option>
					<?php
				}
				echo "</select></td>";
		} else 
				echo "<td>N/A</td>";	
		
		echo "</tr>";

	}
	?>	
		
	</table>
	
</div>

<div class="product-details__title" style="margin-top:10px"></div>
<!--Hasta aqui Skincare -->

<!--Empieza Fragancias -->
<div id="titFragancias" class="product-details__title">Fragancias &#8693;</div>
<div id="Fragancias" class="listadoPedido" style="display:none;">

	<table id="productos" align="center">
		<tr>
			<th colspan=3>Descripción</th>
			<th>Referencia</th>
		</tr>
<?php
$sql="SELECT * FROM Item  where Familia = 3 and Activo = 1"; 
//echo $sql;

$Rs = mysqli_query ($dbh, $sql);
$rows = mysqli_num_rows($Rs);

while($row=mysqli_fetch_assoc($Rs)){ 
	$id = $row['idItem'];
	$des1 = $row['Descripcion1'];
	$des2 = $row['Descripcion2'];
	$des3 = $row['Descripcion3'];
	$ref1 = $row['Referencia1'];
	
	//Empiezo loop del pedido de esa referencia
	$marcada = "";
	$cantidad1 = 0;
	$sql="SELECT * FROM Pedido as p, PedidoDetalle as d where p.idPedido = d.idPedido and p.idColaborador = $idpersona and d.idItem = $id and (p.Fecha BETWEEN '$FirstDay' AND '$LastDay')"; 
	$Rs2 = mysqli_query ($dbh, $sql);
	$rows2 = mysqli_num_rows($Rs2);
	
	if ($rows2 > 0) {
		$row2 = mysqli_fetch_assoc($Rs2); 
		$cantidad1 = $row2['Cantidad1'];
		$marcada = " class='marcada'";
	}

		echo "<tr" . $marcada . ">"; 
		echo "<td>" . $des1 . "</td>";
		echo "<td>" . $des2 . "</td>";
		echo "<td style='min-width: 113px;'>" . $des3 . "</td>";
		if ($ref1 <> "-") {
				echo "<td align=right>" . $ref1 . " <select class='" . $id . "' name='s1-" . $id . "'>";
				for ($x = 0; $x <= $unidades - 1; $x++) {
					?>
					<option value=<?php echo $x ?><?php if($cantidad1 == $x): ?> selected="selected"<?php endif; ?>><?php echo $x ?></option>
					<?php
				}
				echo "</select></td>";
		} else 
				echo "<td>N/A</td>";	
		echo "</tr>";

	}
	?>	
		
	</table>
	
</div>

<div class="product-details__title" style="margin-top:10px"></div>
<!--Hasta aqui Fragancias -->


<!--Empieza PLV -->
<div id="titPLV" class="product-details__title">PLV &#8693;</div>
<div id="PLV" class="listadoPedido" style="display:none;">

	<table id="productos" align="center">
		<tr>
			<th colspan=3>Descripción</th>
			<th>Referencia</th>
		</tr>
<?php
$sql="SELECT * FROM Item  where Familia = 4 and Activo = 1"; 
//echo $sql;

$Rs = mysqli_query ($dbh, $sql);
$rows = mysqli_num_rows($Rs);

while($row=mysqli_fetch_assoc($Rs)){ 
	$id = $row['idItem'];
	$des1 = $row['Descripcion1'];
	$des2 = $row['Descripcion2'];
	$des3 = $row['Descripcion3'];
	$ref1 = $row['Referencia1'];
	
	//Empiezo loop del pedido de esa referencia
	$marcada = "";
	$cantidad1 = 0;
	$sql="SELECT * FROM Pedido as p, PedidoDetalle as d where p.idPedido = d.idPedido and p.idColaborador = $idpersona and d.idItem = $id and (p.Fecha BETWEEN '$FirstDay' AND '$LastDay')"; 
	$Rs2 = mysqli_query ($dbh, $sql);
	$rows2 = mysqli_num_rows($Rs2);
	
	if ($rows2 > 0) {
		$row2 = mysqli_fetch_assoc($Rs2); 
		$cantidad1 = $row2['Cantidad1'];
		$marcada = " class='marcada'";
	}

		echo "<tr" . $marcada . ">"; 
		echo "<td>" . $des1 . "</td>";
		echo "<td>" . $des2 . "</td>";
		echo "<td style='min-width: 113px;'>" . $des3 . "</td>";
		if ($ref1 <> "-") {
				echo "<td align=right>" . $ref1 . " <select class='" . $id . "' name='s1-" . $id . "'>";
				for ($x = 0; $x <= $unidades - 1; $x++) {
					?>
					<option value=<?php echo $x ?><?php if($cantidad1 == $x): ?> selected="selected"<?php endif; ?>><?php echo $x ?></option>
					<?php
				}
				echo "</select></td>";
		} else 
				echo "<td>N/A</td>";	
		echo "</tr>";

	}
	?>	
		
	</table>
	
</div>

<div class="product-details__title" style="margin-top:10px"></div>
<!--Hasta aqui PLV -->

<!--Empieza Insumos -->
<div id="titInsumos" class="product-details__title">Insumos &#8693;</div>
<div id="Insumos" class="listadoPedido" style="display:none;">

	<table id="productos" align="center">
		<tr>
			<th colspan=3>Descripción</th>
			<th>Referencia</th>
		</tr>
<?php
$sql="SELECT * FROM Item  where Familia = 5 and Activo = 1"; 
//echo $sql;

$Rs = mysqli_query ($dbh, $sql);
$rows = mysqli_num_rows($Rs);

while($row=mysqli_fetch_assoc($Rs)){ 
	$id = $row['idItem'];
	$des1 = $row['Descripcion1'];
	$des2 = $row['Descripcion2'];
	$des3 = $row['Descripcion3'];
	$ref1 = $row['Referencia1'];
	
	//Empiezo loop del pedido de esa referencia
	$marcada = "";
	$cantidad1 = 0;
	$sql="SELECT * FROM Pedido as p, PedidoDetalle as d where p.idPedido = d.idPedido and p.idColaborador = $idpersona and d.idItem = $id and (p.Fecha BETWEEN '$FirstDay' AND '$LastDay')"; 
	$Rs2 = mysqli_query ($dbh, $sql);
	$rows2 = mysqli_num_rows($Rs2);
	
	if ($rows2 > 0) {
		$row2 = mysqli_fetch_assoc($Rs2); 
		$cantidad1 = $row2['Cantidad1'];
		$marcada = " class='marcada'";
	}

		echo "<tr" . $marcada . ">"; 
		echo "<td>" . $des1 . "</td>";
		echo "<td>" . $des2 . "</td>";
		echo "<td style='min-width: 113px;'>" . $des3 . "</td>";
		if ($ref1 <> "-") {
				echo "<td align=right>" . $ref1 . " <select class='" . $id . "' name='s1-" . $id . "'>";
				for ($x = 0; $x <= $unidades - 1; $x++) {
					?>
					<option value=<?php echo $x ?><?php if($cantidad1 == $x): ?> selected="selected"<?php endif; ?>><?php echo $x ?></option>
					<?php
				}
				echo "</select></td>";
		} else 
				echo "<td>N/A</td>";	
		echo "</tr>";

	}
	?>	
		
	</table>
	
</div>

<div class="product-details__title" style="margin-top:10px"></div>
<!--Hasta aqui Insumos -->

</div> <!--Cierro el listado-->
<?php
mysqli_close($dbh);
?>

<br />
<div id="btSig" class="boton" onclick="validar()">SIGUIENTE</div>
<br /><br />

</form>
</center>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="popup/popup.js"></script>

</body>
</html>
