<?php
ini_set( 'display_errors', 1 );	
require ('../bd.php');
include( 'fn/fn-referencias.php' );

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
</head>

<body>

<?php require ('header.php'); ?>

<br>
<center>
<div style="font-size:24px; margin-top:70px"><?php echo $titulo ?></div>
<br>

<div id="Listado">

<?php 
	while( $f = mysqli_fetch_assoc( $familias ) ){ 
		$items_familia = obtenerItemsFamilia( $dbh, $f["idFamilia"] );
?>
	
	<div id="tit<?php echo $f["Nombre"]?>" class="product-details__title" onclick="toggle('<?php echo $f["Nombre"]?>')">
		<?php echo $f["Nombre"]?> &#8693;
	</div>
	<div id="<?php echo $f["Nombre"]?>" class="listadoPedido" style="display:none;">
		<table id="productos" align="center">
			<tr>
				<th colspan=4>Descripción</th>
				<th>Referencia</th>
			</tr>
			<?php 
				while( $item = mysqli_fetch_assoc( $items_familia ) ){ 
					$row 	= $item;
					$id 	= $row['idItem'];
				?>
					<tr>
						<td><?php echo $item['Descripcion1'] ?></td>
						<td><?php echo $item['Descripcion2']?></td>
						<td><?php echo $item['Descripcion3'] ?></td>
						<td><?php echo $item['Referencia1'] ?></td>
						<td><select><option value=1>Si</option><option value=0>No</></select></td>
					</tr>
			<?php } ?>	
		</table>
	</div>
	<div class="product-details__title" style="margin-top:10px"></div>
	<!--Fin bloque familia -->
<?php } ?>

</div> <!--Cierro el listado-->

</center>
</body>
</html>



