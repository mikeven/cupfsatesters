<?php
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Acceso a datos de pedidos ---------------- */
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

		return ( ($balance["entradas"] - $balance["salidas"]) >= $cant ); 
	}
	/* ----------------------------------------------------------------------------------- */
	function guardarArchivo( $file ){
		// Guarda el archivo en formato xls en ubicación determinada

		$sourcePath = $file['tmp_name'];
        $targetPath = "excl/".$file['name'];
       
        if( move_uploaded_file( $sourcePath, $targetPath ) ){
        	$uploadedFile = $targetPath;
        }

        return $uploadedFile;
	}
	/* ----------------------------------------------------------------------------------- */
	//Carga de archivo excel para cotejar con pedido
	ini_set( 'display_errors', 1 );

	if( isset ( $_POST["idp"] ) ){
		include( "dataxls.php" );

		if( isset( $_FILES['file'] ) ){
			$archivo = guardarArchivo( $_FILES['file'] );
			$rsp = leerArchivo( $archivo, "" );
		}else {
			$rsp["exito"] = -1;
			$rsp["imp"] = "Error en carga de archivo";
		}
		
		echo json_encode( $rsp );
	}
	/* ----------------------------------------------------------------------------------- */
?>