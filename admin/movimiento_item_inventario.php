<?php
	ini_set( 'display_errors', 1 );

	require ('../bd.php');
	include( 'fn/fn-sesion.php' );
	include( 'fn/fn-usuarios.php' );
	include( 'fn/fn-inventario.php' );

	if( !isset( $_GET['idc'], $_GET['iditem'] ) ) 
		header('Location: login.php?s=0');
	else{
		$item = obtenerItemPorId( $dbh, $_GET['iditem'] );
		$titulo = "Movimientos de Inventario";
		$colaborador = obtenerColaboradorPorId( $dbh, $_GET['idc'] );
		$inventario = obtenerItemsInventario( $dbh, $_GET['iditem'], $_GET['idc'] );
		if( tieneMovimientoInventario( $inventario ) )
			$inventario_item = $inventario;
		$movimientos = obtenerMovimientosItemColaborador( $dbh, $_GET['idc'], $_GET['iditem'] );
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Movimiento - Cupfsa Testers</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" href="../css1.css" />
<style type="text/css">
	
</style>

<script>
	function toggle (c) {
		$("#" + c).toggle(1000, "swing");
	}
</script>
<script type="text/javascript" src="js/fn-referencias.js"></script>
</head>

<body>

	<?php require ('header.php'); ?>

	<br>
	<center>
		<div style="font-size:24px; margin-top:70px"><?php echo $titulo; ?></div>
		<div style="font-size:18px;"><?php echo $colaborador["Nombre"]."  (".$colaborador["NroCliente"].")"; ?></div>
		<div style="font-size:16px;"> Total inventario: <b><?php echo totalDisponibleInv( $inventario_item ); ?></b></div>

		<br>

		<div id="Listado" class="listadoUsuarios">
			<div id="" class="product-details__title">
				#<?php 
				echo $item["Referencia1"]."  -  ".$item["Descripcion1"]."/".$item["Descripcion2"]."/".$item["Descripcion3"]; ?>
			</div>
			<table id="lista_usuarios" align="center" class="testertable">
				<tr>
					<th align="left" width="40%">Fecha</th>
					<th align="center" width="10%">Cantidad</th>
					<th width="20%" style="text-align: center;">Movimiento</th>
					<th align="left" width="30%">Detalle</th>
				</tr>
				<?php foreach( $movimientos as $m ){  $e_s = entrada_salida( $m ); ?>
					<tr>
						<td align="left"><?php echo $m["fecha"] ?></td>
						<td align="center"><?php echo $e_s['cant'] ?></td>
						<td align="center"><?php echo $e_s['icon'] ?></td>
						<td align="left"><?php echo $m['detalle'] ?></td>
					</tr>
				<?php } ?>	
			</table>	
			<div class="product-details__title" style="margin-top:10px"></div>
		</div> <!--Cierre listado-->

		<div class="boton" onclick="javascript:location.href='inventario.php?idu=<?php echo $colaborador["idColaborador"]; ?>'" 
			style="margin-top: 50px;">
			<i class="fas fa-arrow-alt-circle-left" title="Volver a usuarios"></i> Volver
		</div>

	</center>
</body>

</html>



