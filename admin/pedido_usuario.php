<?php
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Pedido de usuario ------------------------ */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */
	ini_set( 'display_errors', 1 );	
	require ('../bd.php');
	include ('fn/fn-pedidos.php');

	$pedido = obtenerPedidoPorId( $dbh, $idpedido );
	$idc = $pedido["idColaborador"];
	$total = cantidadPedido( $dbh, $idpedido );
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
</head>

<body>

<?php require ('header.php'); ?>

<br>
<div class="container">
	<div id="Listado">
		<center>
			<div style="font-size:24px; margin-top:70px"> <?php echo $pedido["Nombre"]; ?> </div>
			<div style="font-size:18px;"> <?php echo $pedido["Fecha"]." (".$total.")"; ?> </div>
			<br>
			<div class="row">
				<div class="col-sm-12 col-xs-12">
					<table id="productos" align="center" style="width:60%">
						<tr>
							<th colspan=4>Descripción</th>
							<th>Referencia</th>
						</tr>
						<?php 
							while( $item_d = mysqli_fetch_assoc( $items_pedido ) ) { 
								$familia = obtenerFamiliaPorId( $dbh, $item_d["idItem"] );
						?>
						<tr>
							<td><?php echo $familia ?></td>
							<td><?php echo $item_d["Descripcion1"] ?></td>
							<td><?php echo $item_d["Descripcion2"] ?></td>
							<td><?php echo $item_d["Descripcion3"] ?></td>
							
							<?php if ( $item_d["Referencia1"] <> "-" ) { ?>
								<td align="right"> 
									<?php echo $item_d["Referencia1"] ?>- <input type='text' value='<?php echo $item_d["Cantidad1"] ?>' readonly>
								</td>
							<?php } else { ?>
								<td>N/A</td>;
							<?php } ?>	
						</tr>
						<?php } ?>
					</table>
				</div>
			</div>
			<div class="boton" onclick="javascript:location.href='usuario.php?id=<?php echo $idc?>'" style="margin: 50px 0;">
				<i class="fas fa-arrow-alt-circle-left" title="Volver a usuarios"></i> Volver
			</div>
		</center>
	</div>
</div>

</center>
</body>
</html>



