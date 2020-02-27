<?php
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Acceso a datos de inventario ------------- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------------------------------- */
	function obtenerBalanceInventario( $dbh, $id_item, $id_colaborador ){
		// Devuelve el inventario disponible de un item para un usuario
		$q = "select sum( ifnull(iv.entrada, 0)) as entradas,  sum( ifnull(iv.salida, 0)) as salidas 
				from Testers.Inventario iv where iv.idItem = $id_item and iv.idColaborador = $id_colaborador"; 
		
		return mysqli_fetch_array( mysqli_query( $dbh, $q ) );
	}
	/* ----------------------------------------------------------------------------------- */
	function ingresarSalidaInventario( $dbh, $iditem, $cantidad, $idcolaborador, $detalle, $motivo ){
		//Devuelve el registro de las órdenes registradas
		$q = "insert into Inventario ( salida, fecha, idColaborador, idItem, detalle, idMotivo ) 
		values( $cantidad, NOW(), $idcolaborador, $iditem, '$detalle', $motivo )";
		
		return mysqli_query( $dbh, $q );
	}
	/* ----------------------------------------------------------------------------------- */
	function esRestable( $dbh, $id_item, $id_colaborador, $cant ){
		// Devuelve verdadero si es válido registrar la salida de inventario si el balance actual es positivo

		$balance = obtenerBalanceInventario( $dbh, $id_item, $id_colaborador ); 
		
		return ( ( $balance["entradas"] - $balance["salidas"] ) >= $cant ); 
	}
	/* ----------------------------------------------------------------------------------- */
	//Registrar salida de inventario
	if( isset( $_POST["restaritem"] ) ){
		include( "../bd.php" );
		
		$detalle = "Indicado por administrador";

		if( esRestable( $dbh, $_POST["restaritem"], $_POST["idc"], $_POST["cantidad"] ) ){
			
			$rsp = ingresarSalidaInventario( $dbh, $_POST["restaritem"], $_POST["cantidad"], 
				$_POST["idc"], $detalle, $_POST["motivo"] );

			if( ( $rsp != 0 ) && ( $rsp != "" ) ){
				$res["exito"] = 1;
				$res["mje"] = "Registro actualizado con éxito!!";
			} else {
				$res["exito"] = 0;
				$res["mje"] = "Error al actualizar inventario";
			}

		}else{

			$res["exito"] = 0;
			$res["mje"] = "No se puede eliminar esta cantidad de su inventario";

		}

		echo json_encode( $res );
	}
	/* ----------------------------------------------------------------------------------- */
?>