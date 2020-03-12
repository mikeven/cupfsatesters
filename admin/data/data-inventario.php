<?php
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Acceso a datos de inventario ------------- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------------------------------- */
	function obtenerBalanceInventario( $dbh, $id_item, $id_colaborador ){
		// Devuelve el inventario disponible de un item para un usuario
		$sql = "select sum( ifnull(iv.entrada, 0)) as entradas,  sum( ifnull(iv.salida, 0)) as salidas 
				from Testers.Inventario iv where iv.idItem = $id_item and iv.idColaborador = $id_colaborador"; 

		return mysqli_fetch_assoc( mysqli_query( $dbh, $sql ) );
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerDataColaboradorPorId( $dbh, $id ){
		// Devuelve los datos de un colaborador dado su id
		$sql = "SELECT * FROM Colaborador where idColaborador = $id"; 
		return mysqli_fetch_assoc( mysqli_query( $dbh, $sql ) );
	}
	/* ----------------------------------------------------------------------------------- */
	function registrarSalidaInventario( $dbh, $iditem, $cantidad, $idcolaborador, $detalle, $motivo ){
		// Registra la salida de una cantidad de unidades del inventario de un colaborador
		$q = "insert into Inventario ( salida, fecha, idColaborador, idItem, detalle, idMotivo ) 
		values( $cantidad, NOW(), $idcolaborador, $iditem, '$detalle', $motivo )";
		
		return mysqli_query( $dbh, $q );
	}
	/* ----------------------------------------------------------------------------------- */
	function registrarEntradaInventario( $dbh, $iditem, $cantidad, $idcolaborador, $detalle, $motivo ){
		// Registra la entrada de una cantidad de unidades al inventario de un colaborador
		$q = "insert into Inventario ( entrada, fecha, idColaborador, idItem, detalle, idMotivo ) 
		values( $cantidad, NOW(), $idcolaborador, $iditem, '$detalle', $motivo )";
		
		return mysqli_query( $dbh, $q );
	}
	/* ----------------------------------------------------------------------------------- */
	function esRestable( $dbh, $id_item, $id_colaborador, $cant ){
		// Devuelve verdadero si es válido registrar la salida de inventario si el balance actual es positivo
		$balance = obtenerBalanceInventario( $dbh, $id_item, $id_colaborador ); 

		return ( ($balance["entradas"] - $balance["salidas"]) >= $cant ); 
	}
	/* ----------------------------------------------------------------------------------- */
	function procesarSalidaInventario( $dbh, $item, $cantidad, $idc, $detalle, $motivo, $coltraspaso ){
		// Procesa el registro de entrada y/o salida de unidades en el inventario de un colaborador según motivo

		$col_emisor 	= obtenerDataColaboradorPorId( $dbh, $idc );
		$col_recept 	= obtenerDataColaboradorPorId( $dbh, $coltraspaso );
		$detalle_salida = $detalle;

		if( $motivo == 5 ){
			$detalle_entrada = $detalle." Traspaso realizado por: ".$col_emisor["Nombre"];
			registrarEntradaInventario( $dbh, $item, $cantidad, $coltraspaso, $detalle_entrada, $motivo );
			$detalle_salida = $detalle." Traspaso realizado para: ".$col_recept["Nombre"];
		}
		$rsp = registrarSalidaInventario( $dbh, $item, $cantidad, $idc, $detalle_salida, $motivo );

		return $rsp;
	}
	/* ----------------------------------------------------------------------------------- */
	//Registrar salida de inventario
	if( isset( $_POST["restaritem"] ) ){
		include( "../../bd.php" );
		
		$detalle = "Indicado por administrador";

		if( esRestable( $dbh, $_POST["restaritem"], $_POST["idc"], $_POST["cantidad"] ) ){
			$rsp = procesarSalidaInventario( $dbh, $_POST["restaritem"], $_POST["cantidad"], 
				$_POST["idc"], $detalle, $_POST["motivo"], $_POST["coltraspaso"] );

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