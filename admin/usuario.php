<?php
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Listado de pedidos de usuario ------------ */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */
	ini_set( 'display_errors', 1 );	

	require ('../bd.php');
	include( 'fn/fn-sesion.php' );
	include( 'fn/fn-usuarios.php' );
	
	if( !isset( $_GET['id'] ) ) 
		header('Location: login.php?s=0');
	else{
		$usuario = obtenerColaboradorPorId( $dbh, $_GET['id'] );
		$pedidos = obtenerPedidosPorCliente( $dbh, $_GET['id'] );
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Usuarios - Cupfsa Testers</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" href="../css1.css" />
<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.css"/>

<script>
	function toggle (c) {
		$("#" + c).toggle(1000, "swing");
	}
</script>
<script type="text/javascript" src="js/fn-referencias.js"></script>
<script type="text/javascript" src="../vendor/bootstrap/js/bootstrap.js"></script>
</head>

<body>

<?php require ('header.php'); ?>

<br>
<div class="container">
	<center>
		<div style="font-size:24px; margin-top:70px"> 
			<?php echo $usuario["Nombre"]." (".$usuario["NroCliente"].")"; ?> 
		</div>
		<br>

		<div class="row">

			<div class="col-sm-12 col-xs-12">
				<div style="font-size:18px; margin:10px; color:#666;"> Lista de pedidos </div>
				
				<?php while( $p = mysqli_fetch_assoc( $pedidos ) ){ ?>
					
					<p>
						<a href="pedido_usuario.php?pedido=<?php echo $p["idPedido"]; ?>">
							#<?php echo $p["idPedido"]." - ".$p["Fecha"]; ?>
						</a>
					</p>
				
				<?php } ?>
			</div>
		</div>

		<div class="boton" onclick="javascript:location.href='usuarios.php'" style="margin-top: 50px;">
			<i class="fas fa-arrow-alt-circle-left" title="Volver a usuarios"></i> Volver
		</div>

	</center>
</div>
</body>
</html>



