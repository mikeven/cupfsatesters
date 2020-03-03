<?php
ini_set( 'display_errors', 1 );	
require ('bd.php');
include( 'fn/fn-sesion.php' );


if(isset($_GET['c'])) {
    $idpersona=$_GET["c"];
} else {
	require('fr_accesonp.php');
}
if(isset($_GET['p'])) {

    $pedido=$_GET["p"];
    $titulo = "Pedido #".$pedido;
} else {

	require('fr_accesonp.php');
	
}

$sql="SELECT * FROM Colaborador as c, Pedido as p where c.idColaborador = p.idColaborador and c.idColaborador = $idpersona and p.idPedido = $pedido and p.Confirmado=1"; 
//echo $sql;
$Rs = mysqli_query ($dbh, $sql);
$rows = mysqli_num_rows($Rs);
if ($rows == 1) {
	$row = mysqli_fetch_assoc($Rs); 
	//$idpersona = $row['idColaborador'];
	$nombre = $row['Nombre'];
	$unidades = $row['Unidades'];
}else {
	require('fr_accesonp.php');
}

//Saco la cantidad del pedido
$sum = 0;
$sql = "SELECT SUM(Cantidad1) AS TotAcum FROM PedidoDetalle where idPedido=$pedido"; 
$Rs = mysqli_query ($dbh, $sql);
$row = mysqli_fetch_assoc($Rs); 
$sum = $row['TotAcum'];
//Listo

$sql="SELECT * FROM PedidoDetalle as d, Item as i where d.idPedido = $pedido and d.idItem = i.idItem "; 
//echo $sql;
$Rs = mysqli_query ($dbh, $sql);
$rows = mysqli_num_rows($Rs);

mysqli_close($dbh);

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
<title>Testers Cupfsa</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<link rel="stylesheet" type="text/css" href="css1.css" />
<link rel="stylesheet" type="text/css" href="menu.css" />

<script>

$( document ).ready(function() {
	 $( "#Listado" ).show( "slow", function() {
	 
	 })		
});

</script>

</head>

<body>

<?php require ('header.php'); ?>
<div id="cantidad">Unidades: <input type="text" id="cant" value="<?php echo $sum?>" readonly></div>

<div id="Listado" style="display:none">
<div id="titFragancias" class="product-details__title">Tu pedido del mes</div>
<div id="Fragancias" class="listadoPedido">

	<table id="productos" align="center">
		<tr>
			<th colspan=3>Descripción</th>
			<th>Referencia</th>
		</tr>

<?php

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

</center>
</body>
</html>