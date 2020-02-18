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
		include( "../../bd.php" );
		
		$detalle = "Indicado por cliente";
		$rsp = ingresarSalidaInventario( $dbh, $_POST["restaritem"], $_POST["cantidad"], $_POST["idc"], $detalle );

		if( ( $rsp != 0 ) && ( $rsp != "" ) ){
			$res["exito"] = 1;
			$res["mje"] = "Registro actualizado con éxito!!";
		} else {
			$res["exito"] = 0;
			$res["mje"] = "Error al actualizar inventario";
		}

		echo json_encode( $res );
	}
	/* ----------------------------------------------------------------------------------- */
?>