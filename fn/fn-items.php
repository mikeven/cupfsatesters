<?php 
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Funciones sobre listado de productos ----- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */

	session_start();
	// Reviso si la sesion caducó
	if(!isset($_SESSION['idp'])) 
	header('Location: login.php?s=0');
	
	/* ----------------------------------------------------------- */
	$idpersona = $_SESSION["idp"];
	$nombre = $_SESSION["nombre"];
	$email = $_SESSION["email"];
	$unidades = $_SESSION['unidades'];
	$FirstDay = $_SESSION["firstday"];  
	$LastDay = $_SESSION["lastday"];  
	
	/* ----------------------------------------------------------- */
	//Verifico si ya hizo pedido
	$sql = "SELECT * FROM Pedido where IdColaborador=$idpersona and Confirmado=1 and (Fecha BETWEEN '$FirstDay' AND '$LastDay')";
	//echo $sql;
	$Rs = mysqli_query ($dbh, $sql);
	$row = mysqli_fetch_assoc($Rs); 
	$rows = mysqli_num_rows($Rs);
	if ($rows > 0) { 
		$pedido = $row['idPedido'];
		header("Location:verPedido.php?c=$idpersona&p=$pedido");
	}
	/* ----------------------------------------------------------- */

	//Saco la cantidad por si ya hizo un pedido preliminar
	$sum = 0;
	$sql = "SELECT SUM(Cantidad1) AS TotAcum FROM PedidoDetalle as d, Pedido as p where p.idPedido = d.idPedido and p.IdColaborador=$idpersona and (p.Fecha BETWEEN '$FirstDay' AND '$LastDay')"; 
	$Rs = mysqli_query ($dbh, $sql);
	$row = mysqli_fetch_assoc($Rs); 
	if ($row['TotAcum'] > 0)
		$sum = $row['TotAcum'];
	//Listo
	/* ----------------------------------------------------------- */
	function obtenerFamilias( $dbh ){
		// Devuelve los registros de familia
		$sql = "SELECT * FROM Familia"; 
		$Rs = mysqli_query( $dbh, $sql );

		return $Rs;
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
	function obtenerItemsPedidoFamilia( $familia ){
		// Devuelve los ítems de una familia
		$sql = "SELECT * FROM Item  where Familia = $familia and Activo = 1"; 

		$Rs = mysqli_query ( $dbh, $sql );
		$rows = mysqli_num_rows( $Rs );
	}
	/* ----------------------------------------------------------- */
	$familias = obtenerFamilias( $dbh );
	/*$items["maquillaje"] = obtenerItemsFamilia( 1 );
	$items["maquillaje"] = obtenerItemsFamilia( 2 );
	$items["maquillaje"] = obtenerItemsFamilia( 3 );
	$items["maquillaje"] = obtenerItemsFamilia( 4 );
	$items["maquillaje"] = obtenerItemsFamilia( 5 );*/
?>