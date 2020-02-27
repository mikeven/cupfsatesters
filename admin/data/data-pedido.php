<?php
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Acceso a datos de pedidos ---------------- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------------------------------- */
	function obtenerDetallePedidoPorId( $dbh, $idp ){
		// Devuelve los registros de detalle de pedido dado su id
		$q = "select i.Referencia1 as referencia, d.Cantidad1 as cantidad
				from PedidoDetalle d, Item i where d.idItem = i.idItem and d.idPedido = $idp";
		$Rs = mysqli_query ( $dbh, $q );
		
		return obtenerListaRegistros( $Rs );
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerListaRegistros( $data ){
		// Devuelve un arreglo con los resultados de un resultset de BD
		$lista_c = array();
		if( $data ){
			while( $c = mysqli_fetch_array( $data ) ){
				$lista_c[] = $c;	
			}
		}
		return $lista_c;
	}
	/* ----------------------------------------------------------------------------------- */
	function ajusteFormatoReferencias( $ref ){
		// Devuelve un número sin los ceros a la izquierda
		return $ref;
	}
	/* ----------------------------------------------------------------------------------- */
	function contenidoEnRegistroPedido( $item, $items_registro ){
		
		
		foreach ( $items_registro as $reg ) {
			if( $reg["referencia"] == ajusteFormatoReferencias( $item["referencia"] ) ){
				echo $reg["referencia"]." = ".ajusteFormatoReferencias( $item["referencia"] )."<br>";
				if( $reg["cantidad"] == $item["cantidad"] ){}
			}
		}
	}
	/* ----------------------------------------------------------------------------------- */
	function chequarItemsPedido( $items_archivo, $items_registro ){
		// 

		foreach ( $items_archivo as $item ) {
			contenidoEnRegistroPedido( $item, $items_registro );
		}
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
		include( "../../bd.php" );

		if( isset( $_FILES['file'] ) ){
			$archivo = guardarArchivo( $_FILES['file'] );
			$items_archivo = leerArchivo( $archivo, "" );
			$items_registro = obtenerDetallePedidoPorId( $dbh, $_POST["idp"] );
			chequarItemsPedido( $items_archivo, $items_registro );
		}else {
			$rsp["exito"] = -1;
			$rsp["imp"] = "Error en carga de archivo";
		}
		
		echo json_encode( $rsp );
	}
	/* ----------------------------------------------------------------------------------- */
?>