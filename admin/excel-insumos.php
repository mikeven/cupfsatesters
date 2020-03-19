<?php
//output headers so that the file is downloaded rather than displayed
//header('Content-Encoding: UTF-8');
//header('Content-Type: text/csv; charset=iso-8859-1');

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=insumos.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
//fputcsv($output, array('id','Descripción', 'Descripción2', 'Descripción3', 'Referencia', 'Registro Sanitario', 'QTY'));

// fetch the data
require('../bd.php');

$inicio = $_GET['i'];
$fin = $_GET['f'];

$inicio = strtotime('first day of this month'); 
$fin = strtotime('last day of this month');

$FirstDay = date("Y-m-d", $inicio) . " 00:00:00";  
$LastDay = date("Y-m-d", $fin) . " 00:00:00";  


//Imprimo la línea de los insumos
$sql = "SELECT * FROM Item where Familia = 5";
$Rs = mysqli_query ($dbh, $sql);
$rows = mysqli_num_rows($Rs);

$items_completo = array("");
$items = array();
while($row=mysqli_fetch_assoc($Rs)){
	$ref1 = $row['Referencia1'];
	$iditem = $row['idItem'];
	$descrip = $row['Descripcion2'];
	//Lo paso a ANSI para evitar problemas con los acentos y ñ
	$descrip2 = iconv( mb_detect_encoding( $descrip ), 'Windows-1252//TRANSLIT', $descrip );
	settype($ref1, "string"); //Para que no le quite los ceros a la izq
	
	$items_completo[] = $ref1 . " - " . $descrip2; //añado cada referencia
	$items[] = $iditem; //añado el id de cada referencia
	
}

//saco el numero de elementos
$cant_items = count($items);	

fputcsv($output, $items_completo);
//hasta aqui

//Saco los pedidos del período que tienen insumos (familia 5)
$sql = "SELECT * FROM Pedido as p, PedidoDetalle as d, Item as i, Colaborador as c where p.idPedido = d.idPedido and d.idItem = i.idItem and i.Familia = 5 and c.idColaborador = p.idColaborador and p.Confirmado=1 and (p.Fecha BETWEEN '$FirstDay' AND '$LastDay') group by c.idColaborador";
$Rs = mysqli_query ($dbh, $sql);
$rows = mysqli_num_rows($Rs);

while($row=mysqli_fetch_assoc($Rs)){
	$idpersona = $row['idColaborador'];
	$nombre = $row['Nombre'];
	$nrocliente = $row['NroCliente']; 
	$pedido = $row['idPedido'];
	
	$resultado = array($nombre);
	for($i=0; $i<$cant_items; $i++) {
	
		$sql="SELECT * FROM PedidoDetalle as d where d.idPedido = $pedido and d.idItem = " . $items[$i] . ""; 
		$Rs2 = mysqli_query ($dbh, $sql);
		$row2 = mysqli_fetch_assoc($Rs2); 
		$rows2 = mysqli_num_rows($Rs2);
	
		$cantidad = 0;
		if ($rows2 > 0) 
			$cantidad = $row2['Cantidad1'];
		else
			$cantidad = 0;
		
		$resultado[] = $cantidad;
		
	}
	fputcsv($output, $resultado);
	
}

fclose($output);
mysqli_close($dbh);
?>