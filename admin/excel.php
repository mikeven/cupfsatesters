<?php
//output headers so that the file is downloaded rather than displayed
//header('Content-Encoding: UTF-8');
//header('Content-Type: text/csv; charset=iso-8859-1');

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=testers.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
//fputcsv($output, array('id','Descripción', 'Descripción2', 'Descripción3', 'Referencia', 'Registro Sanitario', 'QTY'));

// fetch the data
require('../bd.php');

$inicio = $_GET['i'];
$fin = $_GET['f'];

$FirstDay = date("Y-m-d", $inicio) . " 00:00:00";  
$LastDay = date("Y-m-d", $fin) . " 00:00:00";  

$cont = 0;
$sql = "SELECT * FROM Pedido as p, Colaborador as c where c.idColaborador = p.idColaborador and p.Confirmado=1 and (p.Fecha BETWEEN '$FirstDay' AND '$LastDay')";
//echo $sql;
$Rs = mysqli_query ($dbh, $sql);
$rows = mysqli_num_rows($Rs);

while($row=mysqli_fetch_assoc($Rs)){
	$idpersona = $row['idColaborador'];
	$nombre = $row['Nombre'];
	$nrocliente = $row['NroCliente']; 
	$pedido = $row['idPedido'];

	$sql="SELECT * FROM PedidoDetalle as d, Item as i where d.idPedido = $pedido and d.idItem = i.idItem ";  
	//echo $sql;
	$Rs2 = mysqli_query ($dbh, $sql);
	$rows2 = mysqli_num_rows($Rs2);
	
	$cont = 1; //Para el nro de lineas
	while($row2=mysqli_fetch_assoc($Rs2)){ 
		$ref1 = $row2['Referencia1'];
		$cantidad1 = $row2['Cantidad1'];
		settype($ref1, "string");
		
		fputcsv($output, array($cont,"EA", "1", "3", $nrocliente, $ref1, $cantidad1, $cantidad1, "PLV"));
		$cont = $cont + 1;
	}
	
}

fclose($output);
mysqli_close($dbh);
?>