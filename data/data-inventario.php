<?php
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Acceso a datos de inventario ------------- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */
	function ingresarSalidaInventario( $dbh, $iditem, $cantidad, $idcolaborador, $detalle ){
		//Devuelve el registro de las órdenes registradas
		$q = "insert into Inventario ( salida, fecha, idColaborador, idItem, detalle ) 
		values( $cantidad, NOW(), $idcolaborador, $iditem, '$detalle' )";
		
		return mysqli_query( $dbh, $q );
	}
	/* ----------------------------------------------------------------------------------- */
	//Registrar salida de inventario
	if( isset( $_POST["restaritem"] ) ){
		include( "../bd.php" );
		
		$detalle = "Registro desde cuenta de colaborador";
		echo ingresarSalidaInventario( $dbh, $_POST["restaritem"], $_POST["cantidad"], $_POST["idc"], $detalle );
	}
	/* ----------------------------------------------------------------------------------- */
?>