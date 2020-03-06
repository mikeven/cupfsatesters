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
	function iconoCotejamientoArchivo( $valor ){
		// Devuelve el ícono de resultado de cotejamiento de acuerdo al valor de coincidencia
		$iconos = array(
			0 		=> "<i class='fa fa-times inf_ok'></i>",
			1 		=> "<i class='fa fa-exclamation ctjwrn'></i>",
			2 		=> "<i class='fa fa-check ctj_ok'></i>",
		);

		return $iconos[ $valor ];
	}
	/* ----------------------------------------------------------------------------------- */
	function iconoCotejamientoPedido( $valor ){
		// Devuelve el ícono de resultado de cotejamiento de acuerdo al valor de coincidencia
		$iconos = array(
			0 		=> "<i class='fa fa-times ctjerr'></i>",
			1 		=> "<i class='fa fa-exclamation ctjwrn'></i>",
			2 		=> "<i class='fa fa-check ctj_ok'></i>",
		);

		return $iconos[ $valor ];
	}
	/* ----------------------------------------------------------------------------------- */
	function filaCotejamientoArchivo( $item, $contenido ){
		// Devuelve una fila a imprimirse en la tabla de cotejamiento del archivo con los resultados del mismo

		$ref 	= $item["referencia"];
		$cant 	= $item["cantidad"];
		$icono 	= iconoCotejamientoArchivo( $contenido );

		$fila = "<tr><td>$ref</td><td>$cant</td><td>$icono</td>";

		return $fila;
	}
	/* ----------------------------------------------------------------------------------- */
	function filaCotejamientoPedido( $item, $contenido ){
		// Devuelve una fila a imprimirse en la tabla de cotejamiento del pedido con los resultados del mismo

		$ref 	= $item["referencia"];
		$cant 	= $item["cantidad"];
		$icono 	= iconoCotejamientoPedido( $contenido );

		$fila = "<tr><td>$ref</td><td>$cant</td><td>$icono</td>";

		return $fila;
	}
	/* ----------------------------------------------------------------------------------- */
	/*function elementosRegistroPedido( $items_registro ){
		// Devuelve los elementos a imprimirse en tabla pedido contenido en registro de BD
		$fila = "";
		foreach ( $items_registro as $reg ) {
			$ref 	= $reg["referencia"];
			$cant 	= $reg["cantidad"];
			$fila 	.= "<tr><td>$ref</td><td>$cant</td><td></td>";
		}

		return $fila;
	}*/
	/* ----------------------------------------------------------------------------------- */
	function contenidoEnRegistroPedido( $item, $items_registro ){
		// Devuelve si un item [ref y cantidad] se encuentra en los items de un pedido
		$coincide = 0;

		foreach ( $items_registro as $reg ) {
			if( $reg["referencia"] == $item["referencia"] ){
				$coincide = 1;
				if( $reg["cantidad"] == $item["cantidad"] )
					$coincide = 2; 
			}
		}

		return $coincide;
	}
	/* ----------------------------------------------------------------------------------- */
	function contenidoEnArchivo( $reg, $items_archivo ){
		// Devuelve si un item [ref y cantidad] del pedido se encuentra en el archivo leído
		$coincide = 0;

		foreach ( $items_archivo as $item ) {
			if( $reg["referencia"] == $item["referencia"] ){
				$coincide = 1;
				if( $reg["cantidad"] == $item["cantidad"] ){
					$coincide = 2; 
				}
			}
		}

		return $coincide;
	}
	/* ----------------------------------------------------------------------------------- */
	function realizarCotejamientoArchivoPedido( $items_archivo, $items_registro ){
		// Verifica si los ítems leídos por archivo coinciden con los ítems de registro del pedido

		$coincide = false;
		$cotejamiento_archivo = "";

		foreach ( $items_archivo as $item ){
			$contenido 					= contenidoEnRegistroPedido( $item, $items_registro );
			$cotejamiento_archivo 		.= filaCotejamientoArchivo( $item, $contenido );
		}

		$chequeo["coincidencia"] 		= 1;
		$chequeo["revision_archivo"] 	= $cotejamiento_archivo;
		$chequeo["revision_pedido"] 	= chequearItemsArchivo( $items_archivo, $items_registro );

		return $chequeo;
	}
	/* ----------------------------------------------------------------------------------- */
	function chequearItemsArchivo( $items_archivo, $items_registro ){
		// Verifica si los ítems del pedido están contenidos en el archivo leído

		$coincide = false;
		$cotejamiento_pedido = "";

		foreach ( $items_registro as $reg ){
			$contenido 					= contenidoEnArchivo( $reg, $items_archivo );
			$cotejamiento_pedido		.= filaCotejamientoPedido( $reg, $contenido );
		}

		return $cotejamiento_pedido;
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
			$archivo 		= guardarArchivo( $_FILES['file'] );
			$items_archivo 	= leerArchivo( $archivo, "" );
			$items_registro = obtenerDetallePedidoPorId( $dbh, $_POST["idp"] );
			$cotejamiento 	= realizarCotejamientoArchivoPedido( $items_archivo["items"], $items_registro );
			$rsp["ctj_arc"] = $cotejamiento["revision_archivo"];
			$rsp["ctj_ped"] = $cotejamiento["revision_pedido"];

			if( $chk["coincidencia"] ){
				$rsp["exito"] = 1;
				$rsp["imp"] = "<span class='ctj_ok'>Archivo coincide con pedido</span>";
				
			}else{
				$rsp["exito"] = -2;
				$rsp["imp"] = "<span class='ctjerr'>El archivo no coincide con el pedido</span>";
			}
		}else {
			$rsp["exito"] = -1;
			$rsp["imp"] = "Error en carga de archivo";
		}
		
		echo json_encode( $rsp );
	}
	/* ----------------------------------------------------------------------------------- */
?>