<?php 
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Funciones sobre listado de productos ----- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */

	session_start();
	// Reviso si la sesion caducó
	if(!isset($_SESSION['acceso'])) 
	header('Location: login.php?s=0');
	
	/* ----------------------------------------------------------- */
	$dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
	$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");  
	/* ----------------------------------------------------------- */
	function obtenerFamilias( $dbh ){
		// Devuelve los registros de familia
		$sql = "SELECT * FROM Familia"; 
		$Rs = mysqli_query( $dbh, $sql );

		return $Rs;
	}
	/* ----------------------------------------------------------- */
	function obtenerFamiliaPorId( $dbh, $id ){
		// Devuelve el registro de familia de un item dado id de item
		$sql = "SELECT f.idFamilia, f.Nombre FROM Familia f, Item i 
		where i.Familia = f.idFamilia and i.idItem = $id";

		$Rs = mysqli_query( $dbh, $sql );
		$data = mysqli_fetch_assoc( $Rs );

		return $data["Nombre"];
	}
	/* ----------------------------------------------------------- */
	function obtenerItemsFamilia( $dbh, $familia ){
		// Devuelve los ítems de una familia
		$sql = "SELECT * FROM Item  where Familia = $familia and Activo = 1"; 

		$Rs = mysqli_query ( $dbh, $sql );
		$rows = mysqli_num_rows( $Rs );

		return $Rs;
	}
	/* ----------------------------------------------------------- */
	function obtenerPedidoPorId( $dbh, $id ){
		// Devuelve el registro de pedido dado su id

		$sql = "SELECT p.idPedido, c.idColaborador, date_format( p.Fecha, '%d/%m/%Y') as Fecha, 
		c.Nombre, c.Usuario, c.Email, p.Confirmado FROM Pedido p, Colaborador c 
		where c.idColaborador = p.idColaborador and p.idPedido = $id";

		return mysqli_fetch_assoc( mysqli_query( $dbh, $sql ) );
	}
	/* ----------------------------------------------------------- */
	function obtenerItemsPedidoFamilia( $familia ){
		// Devuelve los ítems de una familia
		$sql = "SELECT * FROM Item  where Familia = $familia and Activo = 1"; 

		$Rs = mysqli_query ( $dbh, $sql );
		$rows = mysqli_num_rows( $Rs );
	}
	/* ----------------------------------------------------------- */
	function cantidadPedido( $dbh, $idp ){
		// Devuelve la suma total de las cantidades de un pedido
		$sql = "SELECT SUM(Cantidad1) AS TotAcum FROM PedidoDetalle where idPedido = $idp"; 
		$Rs2 = mysqli_query ($dbh, $sql);
		$row2 = mysqli_fetch_assoc($Rs2); 
		if ( $row2['TotAcum'] > 0 )
			$sum = $row2['TotAcum'];

		return $sum;
	}
	/* ----------------------------------------------------------- */
	//Funcion iterar en las semanas
	function semanas( $dbh, $inicio, $fin ) {
		
		global $dbh;
		global $dias;
		global $meses;
		
		$inicio2 = strtolower($dias[date('w',$inicio)]." ".date('d',$inicio)." de ".$meses[date('n',$inicio)-1]);
		$fin2 = strtolower($dias[date('w',$fin)]." ".date('d',$fin)." de ".$meses[date('n',$fin)-1]);
		
		$FirstDay = date("Y-m-d", $inicio) . " 00:00:00";  
		$LastDay = date("Y-m-d", $fin) . " 00:00:00";  
		
		//cantidad de pedidos
		$sql = "SELECT * FROM Pedido as p where p.Confirmado=1 and (p.Fecha BETWEEN '$FirstDay' AND '$LastDay')";
		//echo $sql;
		$Rs2 = mysqli_query ($dbh, $sql);
		$rows2 = mysqli_num_rows($Rs2);
		

		echo "<div><div class='product-details__title'>$inicio2 - $fin2 ($rows2)</div>";

		$cont = 0;
		$sql = "SELECT * FROM Pedido as p, Colaborador as c where c.idColaborador = p.idColaborador and p.Confirmado=1 and (p.Fecha BETWEEN '$FirstDay' AND '$LastDay')";
		//echo $sql;
		$Rs = mysqli_query ( $dbh, $sql );
		$rows = mysqli_num_rows( $Rs );

		while( $row = mysqli_fetch_assoc( $Rs ) ){

			$idcliente = $row['idColaborador'];
			$nombre = $row['Nombre']; 
			$pedido = $row['idPedido'];
			
			//Saco la cantidad de unidades de su pedido
			$sum = 0; //unidades pedidas en su pedido
			$sql = "SELECT SUM(Cantidad1) AS TotAcum FROM PedidoDetalle where idPedido=$pedido"; 
			$Rs2 = mysqli_query ($dbh, $sql);
			$row2 = mysqli_fetch_assoc($Rs2); 
			if ( $row2['TotAcum'] > 0 )
				$sum = $row2['TotAcum'];	
			
			//Listo
		?>

		<div class="product-details__title" onclick="toggle(<?php echo $idcliente ?>)"><?php echo $nombre ?> (<?php echo $sum ?>)</div>

		<?php
			$sql="SELECT * FROM PedidoDetalle as d, Item as i where d.idPedido = $pedido and d.idItem = i.idItem ";  
			//echo $sql;
			$Rs2 = mysqli_query ($dbh, $sql);
			$rows2 = mysqli_num_rows($Rs2);
			
			if ($rows2 > 0) { //Tiene pedido
				$cont += 1;
			?>
			<div id="<?php echo $idcliente ?>" class="listadoPedido" style="display:none">

			<table id="productos" align="center" style="width=100%">
				<tr>
					<th colspan=4>Descripción</th>
					<th>Referencia</th>
				</tr>
				
			<?php
			}

			while($row2=mysqli_fetch_assoc($Rs2)){ 
					$id = $row2['idItem'];
					$familia = obtenerFamiliaPorId( $dbh, $id );
					$des1 = $row2['Descripcion1'];
					$des2 = $row2['Descripcion2'];
					$des3 = $row2['Descripcion3'];
					$ref1 = $row2['Referencia1'];
					$cantidad1 = $row2['Cantidad1'];

					echo "<tr>"; 
					echo "<td>" . $familia . "</td>";
					echo "<td>" . $des1 . "</td>";
					echo "<td>" . $des2 . "</td>";
					echo "<td>" . $des3 . "</td>";
					if ($ref1 <> "-") 
						echo "<td align=right>" . $ref1 . "- <input type='text' value='" . $cantidad1 . "' readonly></td>";
					else
						echo "<td>N/A</td>";	
					
					echo "</tr>";
				}
				
					echo "</table>";
			echo "</div>";
			echo '<div class="product-details__title" style="margin-top:10px"></div></div>';
		}
		echo "<br><div class='boton' onclick=javascript:location.href='excel.php?i=" . $inicio . "&f=" . $fin . "'>Descargar</div><br><br>";
	}// Cierro la función
	/* ----------------------------------------------------------- */
	if( isset( $_GET["pedido"] ) ){

		$idpedido =  $_GET["pedido"];
		$sql = "SELECT * FROM PedidoDetalle as d, Item as i where d.idPedido = $idpedido and d.idItem = i.idItem";  
		$items_pedido = mysqli_query ( $dbh, $sql );

	}
?>