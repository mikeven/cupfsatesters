<?php
ini_set( 'display_errors', 1 );	
require ('../bd.php');
include( 'fn/fn-usuarios.php' );

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
<div style="font-size:24px; margin-top:70px">USUARIOS</div>
<br>

<div id="Listado" class="listadoUsuarios">
	<table id="lista_usuarios" align="center" class="testertable">
		<tr>
			<th align="left">Nombre</th>
			<th align="left">Usuario</th>
			<th align="left">N° Cliente</th>
			<th align="left">Email</th>
		</tr>
		<?php while( $u = mysqli_fetch_assoc( $usuarios ) ){ ?>
			<tr>
				<td align="left">
					<a href="usuario.php?id=<?php echo $u["idColaborador"] ?>">
						<?php echo $u["Nombre"] ?>
					</a>
				</td>
				<td align="left"><?php echo $u['Usuario'] ?></td>
				<td align="left"><?php echo $u['NroCliente'] ?></td>
				<td align="left"><?php echo $u['Email'] ?></td>
			</tr>
		<?php } ?>	
	</table>	

</div> <!--Cierro el listado-->

</center>
</body>
</html>



