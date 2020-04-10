<?php
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Chequeo de pedido ------------------------ */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */
	ini_set( 'display_errors', 1 );	
	require ('../bd.php');
	include ('fn/fn-usuarios.php');

	$colaboradores = obtenerTodosColaboradores( $dbh );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Testers Cupfsa | Carga de inventario</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" href="../css1.css"/>
<script src="js/fn-carga-inventario.js"></script>
<style type="text/css">
	.sel_colab{ width: 220px; }
	.frm-fld{ text-align: left; padding: 12px 0; }
	.ctj_ok{ color: #147202; } .ctjerr{ color: #cf423b; } .inf_ok{ color: #216cb9; } .ctjwrn{ color: #ef9241; }
	.tabla_ctj{ display: none; }
	#tabla_matriz td{ vertical-align: top; }
	#leyenda_check_pedido li, .tx_confirmacion{ list-style: none; font-size: 12px; text-align: left }
	#response-carga-inventario, #response-carga-archivo, #bot_recargar{ display: none; } .sec_confirmacion{ padding: 8px 0;  }
	#response-carga-archivo{ padding: 30px 0 0px 0; }
	.icoleg{ margin-right: 2px; } .ileg{ margin-right: 9px; }
	.nro_cliente{ font-size: 12px; }

	<?php if( $pedido["Estatus"] == 1 ) { ?>
		.visible_estatus{ display: none; }
	<?php } ?>
</style>

</head>

<body>

<?php require ('header2.php'); ?>

<br>
	<center>

		<div id="Listado" class="listadoUsuarios">
			
			<div style="font-size:24px; margin-top:70px"> Carga inicial de inventario </div>
			
			<br>
			<div id="bloque_carga_archivo" class="visible_estatus">
				<form id="frm-inventario">
					<div class="form-group frm-fld">
						<label for="exp_inv">Seleccione colaborador</label>
						<select id="sel_colab" class="sel_colab" name="colaborador">
							<?php while( $c = mysqli_fetch_assoc( $colaboradores ) ){ ?>
								<option value="<?php echo $c['idColaborador']?>" data-ls="<?php echo $c['Listado']?>">
									<?php echo $c['Nombre']?>
								</option>
							<?php } ?>
						</select>
					</div>
					
					<div class="form-group frm-fld">
	                  <label for="exp_inv">Archivo de inventario</label>
	                  <input type="file" class="form-control" id="archivo" placeholder="Reporte" name="archivo">
	                </div>
				</form>
				<div id="response-inventario" align="center"></div>
				<div id="bot_frm_pedido" class="boton" style="margin: 5px 0;">
					<i class="fas fa-file" title="Cotejar pedido con archivo"></i> Cargar
				</div>
			</div>

			<div class="product-details__title"> </div>
			<form id="items_inventario_archivo">
				<input id="listado_colaborador" type="hidden" name="listado" value="">
				<input id="id_colaborador" type="hidden" name="colaborador" value="">
				<table id="tabla_inventario" align="center" class="testertable tabla_ctj">
					<thead>
						<tr>
							<th colspan="6">Cont. del archivo</th>
						</tr>
						<tr>
							<th colspan="3">Descripción</th>
							<th >Referencia</th>
							<th >Cantidad</th>
							<th ></th>
						</tr>
					</thead>
					<tbody id="inventario_archivo"></tbody>
				</table>
			</form>
			<div id="response-carga-archivo" align="center">
				<span class="tx_confirmacion">Confirmar carga de archivo</span>
				<div class="boton" id="bot_conf_inventario">
					<i class="fas fa-file" title="Volver a usuarios"></i> Confirmar 
				</div>
			</div>
			
			<div class="product-details__title"> </div>

			<div id="bot_recargar" onclick="window.location.reload();" style="padding: 5px 0;">
				<a href="#!"><i class="fas fa-redo" title="Cargar otro archivo"></i> Cargar otro archivo</a>
			</div>
			
		</div>
		
	</center>
</body>
</html>



