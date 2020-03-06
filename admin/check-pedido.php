<?php
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Chequeo de pedido ------------------------ */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */
	ini_set( 'display_errors', 1 );	
	require ('../bd.php');
	include( 'fn/fn-sesion.php' );
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" href="../css1.css"/>
<script src="js/fn-pedido.js"></script>
<style type="text/css">
	.ctj_ok{ color: #147202; } .ctjerr{ color: #cf423b; } .inf_ok{ color: #ccc; } .ctjwrn{ color: #ef9241; }
	.tabla_ctj{ display: none; }
	#tabla_matriz td{ vertical-align: top; }
</style>

</head>

<body>

<?php require ('header.php'); ?>

<br>
	<center>

		<div id="Listado" class="listadoUsuarios">
			
			<div style="font-size:24px; margin-top:70px"> <?php echo $pedido["Nombre"]; ?> </div>
			<div style="font-size:18px;"> <?php echo $pedido["Fecha"]." (".$total.")"; ?> </div>
			<br>
			<form id="frm-pedido">
				<input id="id_pedido" type="hidden" name="pedido" value="<?php echo $pedido["idPedido"]; ?>">
				<div class="form-group">
                  <label for="exp_inv">Archivo de hoja de cálculo</label>
                  <input type="file" class="form-control" id="archivo" placeholder="Reporte" name="archivo">
                </div>
			</form>
			<div id="response-pedido" align="center"></div>
			<div id="bot_frm_pedido" class="boton" style="margin: 5px 0;">
				<i class="fas fa-file" title="Cotejar pedido con archivo"></i> Cotejar
			</div>

			<div class="product-details__title"> </div>
			<table style="width: 50%;" id="tabla_matriz">
				<tr>
					<td>
						<table id="tabla_cotejo_pedido" align="center" class="testertable tabla_ctj">
							<thead>
								<tr>
									<th colspan="3">Cont. del pedido</th>
								</tr>
								<tr>
									<th width="80%">Referencia</th>
									<th width="10%">Cantidad</th>
									<th width="10%"></th>
								</tr>
							</thead>
							<tbody id="registro_pedido"></tbody>
						</table>
					</td>
					<td>
						<table id="tabla_cotejo_archivo" align="center" class="testertable tabla_ctj" >
							<thead>
								<tr>
									<th colspan="3">Cont. del archivo</th>
								</tr>
								<tr>
									<th width="80%">Referencia</th>
									<th width="10%">Cantidad</th>
									<th width="10%"></th>
								</tr>
							</thead>
							<tbody id="pedido_cotejado"></tbody>
						</table>
					</td>
				</tr>
				
			</table>
						

			<div class="product-details__title"> </div>
			<div class="boton" onclick="javascript:location.href='usuario.php?id=<?php echo $idc?>'" style="margin: 50px 0;">
				<i class="fas fa-arrow-alt-circle-left" title="Volver a usuarios"></i> Volver
			</div>
			
		</div>
		
	</center>
</body>
</html>



