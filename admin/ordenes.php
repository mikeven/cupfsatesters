<?php
ini_set( 'display_errors', 1 );	
require ('../bd.php');
include( 'fn/fn-sesion.php' );
include ('fn/fn-pedidos.php');
	
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

<link rel="stylesheet" type="text/css" href="../css1.css" />

<script>

function toggle (c) {
	$("#" + c).toggle(1000, "swing");
}

$(document).ready(function() {

	
});
</script>

<style>


</style>

</head>

<body>

<?php require ('header.php'); ?>

<br>
<center>
<div style="font-size:24px; margin-top:70px">Pedidos</div>
<br>

<div id="Listado">

<?php

//$inicio = date("l jS F Y", strtotime('monday this week'));
//$fin = date("Y-m-d", strtotime('sunday this week'));
$day = date('D');

$inicio = strtotime('monday this week');
$fin = strtotime('sunday this week');
semanas ( $dbh, $inicio, $fin );


if($day == 'Mon') //Esto es pq si es lunes la cuenta es distinta
  $inicio = strtotime('monday -1 week');
else
  $inicio = strtotime('monday -2 week');  

$fin = strtotime('sunday -1 week');
echo "<br><br>";
semanas ( $dbh, $inicio, $fin );


if($day == 'Mon') //Esto es pq si es lunes la cuenta es distinta
  $inicio = strtotime('monday -2 week');
else
  $inicio = strtotime('monday -3 week');  

$fin = strtotime('sunday -2 week');
echo "<br><br>";
semanas ( $dbh, $inicio, $fin );
	


if($day == 'Mon') //Esto es pq si es lunes la cuenta es distinta
  $inicio = strtotime('monday -3 week');
else
  $inicio = strtotime('monday -4 week');  

$fin = strtotime('sunday -3 week');
echo "<br><br>";
semanas ( $dbh, $inicio, $fin );

if($day == 'Mon') //Esto es pq si es lunes la cuenta es distinta
  $inicio = strtotime('monday -4 week');
else
  $inicio = strtotime('monday -5 week');  

$fin = strtotime('sunday -4 week');
echo "<br><br>";
semanas ( $dbh, $inicio, $fin );

if($day == 'Mon') //Esto es pq si es lunes la cuenta es distinta
  $inicio = strtotime('monday -5 week');
else
  $inicio = strtotime('monday -6 week');  

$fin = strtotime('sunday -5 week');
echo "<br><br>";
semanas ( $dbh, $inicio, $fin );

if($day == 'Mon') //Esto es pq si es lunes la cuenta es distinta
  $inicio = strtotime('monday -6 week');
else
  $inicio = strtotime('monday -7 week');  

$fin = strtotime('sunday -6 week');
echo "<br><br>";
semanas ( $dbh, $inicio, $fin );
	
mysqli_close($dbh);
?>	
			

</div> <!--Cierro el listado-->

<div id="totales"></div>
<div id="totales2"></div>

</center>
</body>
</html>



