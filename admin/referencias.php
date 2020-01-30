<?php
ini_set( 'display_errors', 1 );	
require ('../bd.php');

session_start();
//Reviso si la sesion caducó
if(!isset($_SESSION['acceso'])) 
	header('Location: login.php?s=0');

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
<div style="font-size:24px; margin-top:70px"><?php echo $titulo ?></div>
<br>

<div id="Listado">

<!--Empieza Makeup -->
<div id="titMakeup" class="product-details__title" onclick="toggle('Maquillaje')">Maquillaje &#8693;</div>
<div id="Maquillaje" class="listadoPedido" style="display:none;">

	<table id="productos" align="center">
		<tr>
			<th colspan=3>Descripción</th>
			<th>Referencia</th>
			<th>Activo</th>
		</tr>
<?php
$sql="SELECT * FROM Item  where Familia = 1"; 
//echo $sql;

$Rs = mysqli_query ($dbh, $sql);
$rows = mysqli_num_rows($Rs);

while($row=mysqli_fetch_assoc($Rs)){ 
	$id = $row['idItem'];
	$des1 = $row['Descripcion1'];
	$des2 = $row['Descripcion2'];
	$des3 = $row['Descripcion3'];
	$ref1 = $row['Referencia1'];
	
	echo "<tr>"; 
	echo "<td>" . $des1 . "</td>";
	echo "<td>" . $des2 . "</td>";
	echo "<td>" . $des3 . "</td>";
	echo "<td>" . $ref1 . "</td>";	
	echo "<td><select><option value=1>Si</optio><option value=0>No</></select></td>";
	echo "</tr>";

}
	?>	
		
	</table>
	
</div>

<div class="product-details__title" style="margin-top:10px"></div>
<!--Hasta aqui Makeup -->

</div> <!--Cierro el listado-->

</center>
</body>
</html>



