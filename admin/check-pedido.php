<?php
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Chequeo de pedido ------------------------ */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */
	ini_set( 'display_errors', 1 );	
	require ('../bd.php');
	include( 'fn/fn-sesion.php' );
	include ('fn/fn-pedidos.php');

	$pedido 	= obtenerPedidoPorId( $dbh, $idpedido );
	$detalle 	= obtenerDetallePedidoPorId( $dbh, $idpedido );
	$idc 		= $pedido["idColaborador"];
	$total 		= cantidadPedido( $dbh, $idpedido );

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
	.ctj_ok{ color: #147202; } .ctjerr{ color: #cf423b; } .inf_ok{ color: #216cb9; } .ctjwrn{ color: #ef9241; }
	.tabla_ctj{ display: none; }
	#tabla_matriz td{ vertical-align: top; }
	#leyenda_check_pedido li, .tx_confirmacion{ list-style: none; font-size: 12px; text-align: left }
	#cnf_pedido_archivo, #leyenda_check_pedido, #bot_recargar{ display: none; } .sec_confirmacion{ padding: 8px 0;  }
	#response-pedido{ padding: 30px 0 0px 0; }
	.icoleg{ margin-right: 2px; } .ileg{ margin-right: 9px; }
	.nro_cliente{ font-size: 12px; }

	<?php if( $pedido["Estatus"] == 1 ) { ?>
		.visible_estatus{ display: none; }
	<?php } ?>
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
			<div id="bloque_carga_archivo" class="visible_estatus">
				<form id="frm-pedido" >
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
			</div>

			<div class="product-details__title"> </div>
			<form id="items_pedido_archivo">
				<table style="width: 55%;" id="tabla_matriz">
					<tr>
						<td>
							<div id="nro_cliente_pedido" class="nro_cliente"></div>
							<table id="tabla_cotejo_pedido" align="center" class="testertable tabla_ctj_">
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
								<tbody id="registro_pedido">
									<?php while( $item = mysqli_fetch_assoc( $detalle ) ){ ?>
										<tr><td><?php echo $item["Referencia1"]?></td>
										<td><?php echo $item["Cantidad1"]?></td><td></td>
									<?php } ?>
								</tbody>
							</table>
						</td>
						<td>
							
							<input type="hidden" name="idpedido_actarchivo" value="<?php echo $pedido["idPedido"]; ?>">
							<div id="nro_cliente_archivo" class="nro_cliente"></div>
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
					
					<tr class="visible_estatus">
						<td id="cnf_pedido_db" colspan="2" class="sec_confirmacion">
							<span class="tx_confirmacion"></span>
							<div class="boton" id="bot_conf_pedido_bd">
								<i class="fas fa-database" title="Confirmar pedido en sistema"></i> Confirmar
							</div>
						</td>
						<td id="cnf_pedido_archivo" colspan="2" class="sec_confirmacion">
							<span class="tx_confirmacion">Confirmar pedido por archivo</span>
							<div class="boton" id="bot_conf_pedido_archivo">
								<i class="fas fa-file" title="Confirmar pedido por archivo"></i> Confirmar 
							</div>
						</td>
					</tr>

					<tr>
						<td colspan="2">
							<div class="">
								<ul id="leyenda_check_pedido">
									<li><i class="fa fa-check ctj_ok icoleg"></i> Coincide en referencia y cantidad</li>
									<li><i class="fa fa-exclamation ctjwrn ileg"></i> Cantidades diferentes: se actualiza cantidad en pedido</li>
									<li><i class="fa fa-plus inf_ok icoleg"></i> Leído en archivo, no está pedido: se agrega al pedido</li>
									<li><i class="fa fa-minus ctjerr icoleg"></i> No está en archivo, cantidad en pedido pasa a cero (0)</li>
								</ul>
							</div>
						</td>
					</tr>
					
				</table>
			</form>
			<div id="response-confirmacion-pedido" align="center"></div>
			
			<div class="product-details__title"> </div>
			<div id="bot_recargar" onclick="window.location.reload();" style="padding: 5px 0;">
				<a href="#!"><i class="fas fa-redo" title="Cargar otro archivo"></i> Cargar otro archivo</a>
			</div>
			<div class="boton" onclick="javascript:location.href='pedidos.php'" style="margin: 50px 0;">
				<i class="fas fa-arrow-alt-circle-left" title="Volver a usuarios"></i> Volver
			</div>
			
		</div>
		
	</center>
</body>
</html>



