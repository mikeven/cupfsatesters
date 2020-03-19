<?php
ini_set( 'display_errors', 1 );	
require ('../bd.php');
include( 'fn/fn-sesion.php' );

$dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

//Funcion iterar en las semanas
function semanas( $inicio, $fin ) {

	global $dbh;
	global $dias;
	global $meses;
	$id_admin 		= $_SESSION["Admin"]["idAdmin"];
	
	$inicio2 		= strtolower( date( 'd', $inicio )." ".$meses[date( 'n', $inicio ) - 1]." ".date( 'Y', $inicio ) );
	$fin2 			= strtolower( date( 'd', $fin )." ".$meses[date( 'n', $fin ) - 1]." ".date( 'Y', $fin ) );
	
	$FirstDay 		= date( "Y-m-d", $inicio ) . " 00:00:00";  
	$LastDay 		= date( "Y-m-d", $fin ) . " 00:00:00";  
	
	//cantidad de pedidos
	$sql = "SELECT * FROM Pedido as p where p.Confirmado = 1 and ( p.Fecha BETWEEN '$FirstDay' AND '$LastDay' ) 
	and p.idColaborador in ( select idColaborador from Admin_Colab where idAdmin = $id_admin )";
	//echo $sql;
	$Rs2 			= mysqli_query ( $dbh, $sql );
	$rows2 			= mysqli_num_rows( $Rs2 );
	
	echo "<div><div class='product-details__title'>$inicio2 - $fin2 ($rows2)</div>";

	$cont 	= 0;
	$sql 	= "SELECT * FROM Pedido as p, Colaborador as c 
				where c.idColaborador = p.idColaborador and p.Confirmado = 1 and ( p.Fecha BETWEEN '$FirstDay' AND '$LastDay' ) 
				and p.idColaborador in ( select idColaborador from Admin_Colab where idAdmin = $id_admin )";
	
	$Rs 			= mysqli_query ( $dbh, $sql );
	$rows 			= mysqli_num_rows( $Rs );

	while( $row = mysqli_fetch_assoc( $Rs ) ){

		$idcliente 	= $row['idColaborador'];
		$nombre 	= $row['Nombre']; 
		$pedido 	= $row['idPedido'];
		
	//Saco la cantidad de unidades de su pedido
		$sum = 0; //unidades pedidas en su pedido
		$sql = "SELECT SUM(Cantidad1) AS TotAcum FROM PedidoDetalle where idPedido=$pedido"; 
		$Rs2 = mysqli_query ( $dbh, $sql );
		$row2 = mysqli_fetch_assoc( $Rs2 ); 
		if ( $row2['TotAcum'] > 0 )
			$sum = $row2['TotAcum'];	
	//Listo
	?>

	<div class="product-details__title" onclick="toggle(<?php echo $idcliente ?>)">
		<?php echo $nombre ?> (<?php echo $sum ?>)</div>

		<?php
		$sql = "SELECT * FROM PedidoDetalle as d, Item as i where d.idPedido = $pedido and d.idItem = i.idItem";  
		//echo $sql;
		$Rs2 		= mysqli_query ( $dbh, $sql );
		$rows2 		= mysqli_num_rows( $Rs2 );
		
		if ( $rows2 > 0 ) { //Tiene pedido
			$cont += 1;
		?>
			<div id="<?php echo $idcliente ?>" class="listadoPedido" style="display:none">

			<table id="productos" align="center" style="width=100%">
				<tr>
					<th colspan=3>Descripción</th>
					<th>Referencia</th>
				</tr>
			
		<?php
		}

			while( $row2 = mysqli_fetch_assoc( $Rs2 ) ){ 
				$id = $row2['idItem'];
				$des1 = $row2['Descripcion1'];
				$des2 = $row2['Descripcion2'];
				$des3 = $row2['Descripcion3'];
				$ref1 = $row2['Referencia1'];
				$cantidad1 = $row2['Cantidad1'];

				echo "<tr>"; 
				echo "<td>" . $des1 . "</td>";
				echo "<td>" . $des2 . "</td>";
				echo "<td>" . $des3 . "</td>";
				if ($ref1 <> "-") 
					echo "<td align=right>" . $ref1 . "- <input type='text' value='" . $cantidad1 . "' readonly></td>";
				else
					echo "<td>N/A</td>";	
				
				echo "</tr>";
			}
				
			echo "</table>";
			echo "</div>";
			echo '<div class="product-details__title" style="margin-top:10px"></div></div>';
	}
	echo "<br><div class='boton' onclick=javascript:location.href='excel.php?i=" . $inicio . "&f=" . $fin . "'>Testers</div>&nbsp;&nbsp;";
	echo "<div class='boton' onclick=javascript:location.href='excel-plv.php?i=" . $inicio . "&f=" . $fin . "'>PLV</div>&nbsp;&nbsp;";
	echo "<div class='boton' onclick=javascript:location.href='excel-insumos.php?i=" . $inicio . "&f=" . $fin . "'>Insumos</div><br><br>";
}// Cierro la función	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Testers Cupfsa</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" href="../css1.css" />

<script>

function toggle (c) {
	$("#" + c).toggle(1000, "swing");
}

$(document).ready(function() {

	
});
</script>

<style>


</style>

</head>

<body>

<?php require ('header.php'); ?>

<br>
<center>
<div style="font-size:24px; margin-top:70px">Pedidos</div>
<br>

<div id="Listado">

<?php

//$inicio = date("l jS F Y", strtotime('monday this week'));
//$fin = date("Y-m-d", strtotime('sunday this week'));
$day = date('D');

$inicio = strtotime('first day of this month');
$fin = strtotime('last day of this month');
semanas( $inicio, $fin );

/**/

$inicio = strtotime('first day of this month -1 month'); 
$fin = strtotime('last day of this month -1 month');
echo "<br><br>";
semanas( $inicio, $fin );

/**/

$inicio = strtotime('first day of this month -2 month');   

$fin = strtotime('last day of this month -2 month');
echo "<br><br>";
semanas( $inicio, $fin );
	
/**/

$inicio = strtotime('first day of this month -3 month');   

$fin = strtotime('last day of this month -3 month'); 

echo "<br><br>";
semanas( $inicio, $fin );

/**/

$inicio = strtotime('first day of this month -4 month');   

$fin = strtotime('last day of this month -4 month'); 

echo "<br><br>";
semanas( $inicio, $fin );

/**/

$inicio = strtotime('first day of this month -5 month');   

$fin = strtotime('last day of this month -5 month'); 

echo "<br><br>";
semanas( $inicio, $fin );

/**/

$inicio = strtotime('first day of this month -6 month');   

$fin = strtotime('last day of this month -6 month');  


echo "<br><br>";
semanas( $inicio, $fin );

/**/
	
mysqli_close( $dbh );
?>	
			

</div> <!--Cierro el listado-->

<div id="totales"></div>
<div id="totales2"></div>

</center>
</body>
</html>



