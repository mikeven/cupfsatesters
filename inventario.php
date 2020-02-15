<?php
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Inventario de Colaborador ---------------- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */
	ini_set( 'display_errors', 1 );	
	require ('bd.php');
	include( 'fn/fn-sesion.php' );
	include( 'fn/fn-usuarios.php' );
	include( 'fn/fn-inventario.php' );
	$familias = obtenerFamilias( $dbh );
	$titulo = "Inventario";

	if( !isset( $idpersona ) ) 
		header('Location: login.php?s=0');
	else{
		$usuario = obtenerColaboradorPorId( $dbh, $idpersona );
	}
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

<link rel="stylesheet" type="text/css" href="css1.css" />
<link rel="stylesheet" type="text/css" href="menu.css" />

<style type="text/css">
	.target_ajinv{ display: none; }
	.invimpar{ background: #f2f2f2 !important; }
	.invpar{ background: #fff !important; }
	.ajuste_existencias_inv{ height: 25px; }
	.bot_cnfajuste{ 
		vertical-align: bottom; 
		margin-right: 16px;
    	border-right: 1px solid #000;
    	padding-right: 4px; 
	}
	.innerfila, .target_ajinv{ background: #dcdada !important; }
</style>

<script>
	function toggle (c) {
		$("#" + c).toggle(1000, "swing");
	}
</script>
<script type="text/javascript" src="js/fn-inventario.js"></script>
</head>

<body>

<?php require ('header.php'); ?>

<br>
<center>

	<div id="Listado">
		<input id="idcolaborador" type="hidden" name="id_colaborador" value="<?php echo $idpersona ?>">
		<?php 
			foreach ( $familias as $f ) {
				$items_familia = obtenerItemsFamilia( $dbh, $f["idFamilia"] );
				if( poseeValoresInventario( $dbh, $usuario["idColaborador"], $items_familia ) ){
					$fila = 0;
		?>
			
			<div id="tit<?php echo $f["Nombre"]?>" class="product-details__title">
				<?php echo $f["Nombre"]?>
			</div>
			<div id="<?php echo $f["Nombre"]?>" class="listadoPedido" style="position: relative;">
				<table id="productos" align="center">
					<tr>
						<th colspan="2">Descripción</th>
						<th>Referencia</th>
						<th colspan="3">Inventario</th>
					</tr>
					<?php 
						foreach ( $items_familia as $item ) {
							$lnk_mov = "movimiento_item_inventario.php?idc=$usuario[idColaborador]&iditem=$item[idItem]";
							$inventario = obtenerItemsInventario( $dbh,  $item['idItem'], $usuario["idColaborador"] );
							
							if( tieneMovimientoInventario($inventario) ){
								$cant = $inventario['entradas']-$inventario['salidas']; $fila++;
								$top_u = topeUnidadesRestar( $cant );
						?>
							<tr class="<?php filaParImpar( $fila ) ?>">
								<td> 
									<?php if( existeArchivoImagen( $item['Referencia1'] ) ) { ?>
										<img src="../fotos/<?php echo $item['Referencia1']?>.JPEG" width="60px"> 
									<?php } ?>
								</td>
								<td align="left"> 
									<?php echo $item['Descripcion1']."-".$item['Descripcion2']."-".$item['Descripcion3'] ?> 
								</td>
								<td> <?php echo $item['Referencia1'] ?> </td>
								<td> <?php echo $cant ?> </td>
								<td> 
									<a href="<?php echo $lnk_mov; ?>">
										<i class="fas fa-list fa-2x" title="Movimientos del item <?php echo $item['idItem']?>"></i>
									</a> 
								</td>
								<td> 
									<?php if( $cant > 0 ) { ?>
										<a href="#!" class="opc_ajuste_inv" data-t="trg<?php echo $item['idItem']?>">
											<i class="fas fa-minus-square fa-2x" title="Restar existencia"></i>
										</a>
									<?php } ?>
								</td>
							</tr>
							<?php include( "fn/ajuste_existencias.php" ) ?>
							
					<?php } } ?>	
				</table>
			</div>
			<div class="product-details__title" style="margin-top:10px"></div>
			<!--Fin bloque familia -->
		<?php 
			} // Cierre if poseeValoresInventario

		} //Cierre While $familias 
		?>

	</div> <!--Cierro el listado-->
	<div class="boton" onclick="javascript:location.href='index.php'" style="margin-top: 50px;">
			<i class="fas fa-arrow-alt-circle-left" title="Volver a solicitud"></i> Volver
	</div>
</center>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="popup/popup.js"></script>

</body>
</html>



