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

$inicio = strtotime('first day of this month');
$fin = strtotime('last day of this month');
semanas( $dbh, $inicio, $fin );


/**/

$inicio = strtotime('first day of this month -1 month'); 
$fin = strtotime('last day of this month -1 month');
echo "<br><br>";
semanas( $dbh, $inicio, $fin );

/**/

$inicio = strtotime('first day of this month -2 month');   
$fin = strtotime('last day of this month -2 month');
echo "<br><br>";
semanas( $dbh, $inicio, $fin );
  
/**/
	
$inicio = strtotime('first day of this month -3 month');   
$fin = strtotime('last day of this month -3 month'); 
echo "<br><br>";
semanas( $dbh, $inicio, $fin );

/**/

$inicio = strtotime('first day of this month -4 month');   
$fin = strtotime('last day of this month -4 month'); 
echo "<br><br>";
semanas( $dbh, $inicio, $fin );

/**/

$inicio = strtotime('first day of this month -5 month');   
$fin = strtotime('last day of this month -5 month'); 
echo "<br><br>";
semanas( $dbh, $inicio, $fin );

/**/

$inicio = strtotime('first day of this month -6 month');   
$fin = strtotime('last day of this month -6 month');
echo "<br><br>";
semanas( $dbh, $inicio, $fin );

/**/
	
mysqli_close($dbh);
?>	
			

</div> <!--Cierro el listado-->

<div id="totales"></div>
<div id="totales2"></div>

</center>
</body>
</html>



