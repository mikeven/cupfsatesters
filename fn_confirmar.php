<?php
ini_set( 'display_errors', 1 );	

session_start();
//Reviso si la sesion caducó
if(!isset($_SESSION['idp'])) 
	print("3");

require ('bd.php');
	
$idpersona = $_SESSION["idp"];
$nombre = $_SESSION["nombre"];
$email = $_SESSION["email"];
$unidades = $_SESSION['unidades'];
$pedido = $_SESSION["pedido"];

//Confirmo el pedido
$sql = "UPDATE Pedido set Confirmado = 1 where idPedido=$pedido"; 
$Rs = mysqli_query ($dbh, $sql);
$afe = mysqli_affected_rows($dbh);

if ($afe == -1) {
	print ("2");
	exit;
} else {
	print ("1");
}

//Envio el email
$subject = $nombre . " | Tu pedido";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: Testers Cupfsa <pedidotester@gmail.com>" . "\r\n";
$headers .= 'Cc: eixa.rizcalla@cupfsa.com; gianfranco.quiodetti@cupfsa.com' . "\r\n";
$body = '
<html>
<head>
<link rel="stylesheet" type="text/css" href="https://digital.cupfsa.com/testers/css1.css" />
</head>

<body style="font-family:Arial">

<center>
<div><img src="https://digital.cupfsa.com/testers/images/logo.jpg" width="150px" height="150px"></div>
<br>
<div style="font-size:24px;">' . $titulo . '</div>
</center>
<br><br>
Estimad@ ' . $nombre . '.<br>
<div>Puedes ver tu pedido <a href="http://digital.cupfsa.com/testers/verPedido.php?c=' . $idpersona . '&p=' . $pedido . '"> aquí</a>.</div>
<br><br>
Cualquier consulta, no dudes en comunicarte con Eixa Rizcalla (eixa.rizcalla@cupfsa.com). 

</body>
</html>
';

//Cierro el body

mail($email,$subject,$body,$headers);
?>