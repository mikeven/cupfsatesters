<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Testers Cupfsa</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" href="css1.css"/>

<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="Testers Cupfsa">
<link rel="apple-touch-icon" sizes="152x152" href="images/icon.png">
<!-- Chrome, Firefox OS and Opera -->
<meta name="theme-color" content="#ffffff">

<style>
#login {
	border: 5px solid black;
	width: 100%;
	max-width: 450px;
    padding-top: 40px;
	padding-bottom: 40px;
	border-radius: 10px;
	background-color: #ffffff;
}

input[type=text], input[type=password] {
    width: 180px;
    height: 30px;
    padding: 7px 5px;
    box-sizing: border-box;
    border: 1px solid black;
    font-size: 14px;
    text-align: left;
}

// Small devices (landscape phones, 576px and up)
@media (min-width: 576px) { ... }

// Medium devices (tablets, 768px and up)
@media (min-width: 768px) { ... }

// Large devices (desktops, 992px and up)
@media (min-width: 992px) { ... }

// Extra large devices (large desktops, 1200px and up)
@media (min-width: 1200px) { ... }

@media screen and (max-width: 768px) {
	#login {
		width: 100%;
		max-width: 350px;
	}
}
</style>

<script>

$( document ).ready(function() {

	<?php //Si la sesion caducó
	if( isset( $_GET['s'] ) ) 
		echo 'swal("La sesión ha expirado", "Por favor vuelve a ingresar", "warning")';
	?>
	
	<?php
	/* $dia = date("l");
		if (($dia != "Monday") and ($dia != "Tuesday") and ($dia != "Wednesday") ) {
			echo 'swal("Semana cerrada", "Solo se puede hacer pedido los días lunes, martes y miércoles.", "warning")';
			$cerrado = 1;
		}*/
	?>

}); //Cierro el ready

function validar() {
	
	 $.ajax({
	 	 type: "POST",
  		 url: 'fn_ingreso.php',
		 data: $('#form1').serialize(),
      	 success: function(data) {
         	if (data == 1) {
				window.location.href="index.php";
			} else
				swal("Datos de acceso incorrectos", "", "error");
      	},
      error: function() {
      	swal("Ha ocurrido un error!", "Por favor intenta de nuevo", "error");
      }
   });

}

</script>

</head>

<body bgcolor="#000">

<center>
<div class="container">
	<div><img style="margin-top:25px" src="images/logo_white.png" width="180px"></div>
	<br><br>

	<form id="form1">
	<div id	="login">
		<table cellpadding="5" cellspacing="5">
		<tr>
		<td>Login</td><td><input type="text" name="login"></td>
		</tr>
		<tr>
		<td>Password</td><td><input type="password" name="password"></td>
		</tr>
		</table>
		<br /><br />
		<?php if ($cerrado <> 1)
		echo '<div class="boton" onclick="validar()">ENTRAR</div>';
		?>
	</div>
	</form>
</div>
</center>
</body>
</html>