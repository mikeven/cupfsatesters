<?php
ini_set( 'display_errors', 1 );	

session_start();
//Reviso si la sesion caducó
if(!isset($_SESSION['idp'])) 
	header('Location: login.php?s=0');
	
require ('bd.php');

$titulo = "Solicitud de Testers";
	
$idpersona = $_SESSION["idp"];
$nombre = $_SESSION["nombre"];
$email = $_SESSION["email"];
$unidades = $_SESSION['unidades'];
$FirstDay = $_SESSION["firstday"];  
$LastDay = $_SESSION["lastday"];

//Verifico si ya hizo pedido
$sql = "SELECT * FROM Pedido where IdColaborador=$idpersona and Confirmado=1 and (Fecha BETWEEN '$FirstDay' AND '$LastDay')";
$Rs = mysqli_query ($dbh, $sql);
$rows = mysqli_num_rows($Rs);
if ($rows > 0) { 
	header("Location:verPedido.php?idp=$idpersona&p=$pass");
}
//Listo


//Limpio por si hay registros previos
$sql = "DELETE FROM Pedido where IdColaborador=$idpersona and Confirmado<>1 and (Fecha BETWEEN '$FirstDay' AND '$LastDay')";
$Rs = mysqli_query ($dbh, $sql);
$afe = mysqli_affected_rows($dbh);
//borra en cascada los registros de PedidoDetalle, configurado en MySQL
//Limpio

$sql = "INSERT INTO Pedido values (NULL, $idpersona, NOW(), 0, 0)";   
//echo $sql;
$Rs = mysqli_query ($dbh, $sql);
$afe = mysqli_affected_rows($dbh);
$pedido = mysqli_insert_id($dbh);
$_SESSION["pedido"] = $pedido;

foreach($_POST as $key => $value) {
		//echo "POST parameter nombre '$key' has '$value'<br>";
		$seleccion  = strtok($key, '-');
		$iditem = strtok('-');
		
		$cantidad1 = 0;
		
		if ($seleccion == "s1")
			$cantidad1 = $value;
	
		$sql = "INSERT INTO PedidoDetalle values (NULL, $pedido, $iditem, $cantidad1, NOW())";   
		//echo $sql;
		$Rs = mysqli_query ($dbh, $sql);
		$afe = mysqli_affected_rows($dbh);
}

if ($afe == -1) {
	echo "Ha ocurrido un error.";
	exit;
}
		
//Valido la cantidad

$sql = "SELECT SUM(Cantidad1) AS TotAcum FROM PedidoDetalle as d where d.idPedido = $pedido"; 
$Rs = mysqli_query ($dbh, $sql);
$row = mysqli_fetch_assoc($Rs); 
$sum = $row['TotAcum'];
/*
if ($sum > $unidades) {
	echo "La cantidad de unidades seleccionadas no cuadra con las disponibles.";
	exit;
}*/
//Valido

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

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
<title>Testers CUPFSA</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<link rel="stylesheet" type="text/css" href="css1.css"/>
<link rel="stylesheet" type="text/css" href="menu.css"/>

<script>

$( document ).ready(function() {
	 $( "#Listado" ).show( "slow", function() {
	 })		
});



function validar() {

swal({
  title: "¿Está seguro?",
  text: "UNA VEZ CONFIRMADO ESTE PEDIDO NO PODRA REALIZAR OTRO HASTA LA PROXIMA SEMANA",
  icon: "warning",
  buttons: true,
  dangerMode: true,
}).then(function (willDelete) {
  if (willDelete) {
   //Ajax
    $.ajax({
  		 url: 'fn_confirmar.php',
      	 success: function(data) {
		 console.log( data );
         	if (data == 1) {
					$( "#botones" ).hide( "slow", function() {
			 			swal("¡PEDIDO CONCLUIDO!", "", "success");
					 })		
			} else 
				if (data == 3)
					window.location.replace("login.php?s=0");
				else
					swal("Ha ocurrido un error", "Por favor intenta de nuevo", "error");
      	},
      error: function() {
      	swal("Ha ocurrido un error", "Por favor intenta de nuevo en unos minutos", "error");
      }
   });
   //
  } else {
    
  }
});
	
}


</script>

</head>

<body>

<?php require ('header.php'); ?>

<div id="cantidad">Unidades: <input type="text" id="cant" value="<?php echo $sum?>" readonly></div>

<form name="form1" id="form1" method="post" action="">
<div id="Listado" style="display:none">
<div id="titFragancias" class="product-details__title">Tu pedido</div>
<div id="Fragancias" class="listadoPedido">

	<table id="productos" align="center">
		<tr>
			<th colspan=3>Descripción</th>
			<th>Referencia</th>
		</tr>

<?php
$sql="SELECT * FROM PedidoDetalle as d, Item as i where d.idItem = i.idItem and d.idPedido = $pedido"; 
//echo $sql;

$Rs = mysqli_query ($dbh, $sql);
$rows = mysqli_num_rows($Rs);

mysqli_close($dbh);

while($row=mysqli_fetch_assoc($Rs)){ 
		$id = $row['idItem'];
		$des1 = $row['Descripcion1'];
		$des2 = $row['Descripcion2'];
		$des3 = $row['Descripcion3'];
		$ref1 = $row['Referencia1'];
		$cantidad1 = $row['Cantidad1'];

		echo "<tr>"; 
		echo "<td>" . $des1 . "</td>";
		echo "<td>" . $des2 . "</td>";
		echo "<td style='min-width: 113px;'>" . $des3 . "</td>";
		if ($ref1 <> "-") 
			echo "<td align=right>" . $ref1 . " <input type='text' value='" . $cantidad1 . "' readonly></td>";
		else
			echo "<td>N/A</td>";	
		
		echo "</tr>";

	}
	?>	
			
	</table>
	
</div>

<div class="product-details__title" style="margin-top:10px"></div>
</div> <!--Cierro el listado-->

<br />
<div id="botones" style="width: 340px;">
	<div class="boton" style="float:left" onclick="location.href='index.php'">MODIFICAR</div>
	<div class="boton" style="float:left; margin-left:10px" onclick="validar()">ENVIAR</div></div>
</div>
<br /><br />
</form>
</center>
</body>
</html>