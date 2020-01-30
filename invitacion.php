<?php
ini_set( 'display_errors', 1 );	
require ('bd.php');

if ($_GET['p'] <> "Cupfsa01")
 exit;

//Data del email
$subject = $titulo;
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: HUMAN RESOURCES <hrdept@cupfsa.com>' . "\r\n";
//$headers .= 'Cc: flepage@cupfsa.com' . "\r\n";

$cont = 0;
$sql="SELECT * FROM Colaborador where Invitar = 1 and idColaborador = 87"; 
//$sql="SELECT * FROM Colaborador where Invitar = 1"; 
$Rs = mysqli_query ($dbh, $sql);
$rows = mysqli_num_rows($Rs);
while($row=mysqli_fetch_assoc($Rs)){ 
		$idpersona = $row['idColaborador'];
		$nombre = $row['Nombre'];
		$pass = $row['Password'];
		$email = $row['Email'];
		$mensaje = $row['Mensaje'];
		//$email ="flepage@cupfsa.com";

$body = '
<html>
<head>
<link rel="stylesheet" type="text/css" href="https://digital.cupfsa.com/freegoods/css1.css" />
</head>

<body style="font-family:Arial">

<center>
<div><img src="https://digital.cupfsa.com/freegoods/images/logo.jpg" width="150px" height="150px"></div>
<br>
<div style="font-size:24px;">' . $titulo . '</div>
</center>
<br><br>
<div>
Estimad@ ' . $nombre . '.<br>
Ha llegado el momento de ordenar los primeros free goods del año. Puedes hacer tu pedido <a href="http://digital.cupfsa.com/freegoods/index.php?email=' . $email . '&p=' . $pass . '"> aquí</a> hasta el <b>lunes 11 de marzo de 2019</b> desde tu computadora o celular. Cada usuario tiene un link de acceso único, favor no compartirlo.
' . $mensaje .'<br><br>
Para tu mejor referencia puedes obtener la Política de Free Goods en la <b>J:\DATA\APLIC\RECURSOS HUMANOS\BENEFICIOS</b>.<br><br>

Cualquier consulta, no dudes en comunicarte con Lilia Rodríguez en el Departamento de Recursos Humanos.
</div>

</body>
</html>
';

//Cierro el body

if(@mail($email,$subject,$body,$headers))
{
	$cont += 1;
	echo $email . "<br>";	
	
	$sql="UPDATE Colaborador set Invitado = NOW() where idColaborador = $idpersona"; 
	$Rs2 = mysqli_query ($dbh, $sql);
	$afe = mysqli_affected_rows($dbh);
	if ($afe == -1) {
		echo "Ha ocurrido un error en update de " . $idpersona;
	}
	
}else{
  echo "Error con: " . $email;
}

} //Cierro el while

echo "<br>Se enviaron " . $cont . " emails";
?>