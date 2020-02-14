<?php

$servidor = "localhost";
$usuariobd = "root";
$passbd = "89312qIWk!";
$basedatos = "Testers";

$dbh = mysqli_connect ( $servidor, $usuariobd, $passbd, $basedatos ) or die('No se puede conectar a '.$servidor.": ". mysqli_error());

mysqli_query($dbh, 'SET CHARACTER SET utf8'); 
mysqli_query($dbh, "SET NAMES 'utf8'");
/*-----------------------------------------------------------------------------------------------------------------------*/

//$titulo = "Solicitud de Testers";
?>