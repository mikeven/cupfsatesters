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
	function iconoCoincidenciaCotejamiento( $valor ){
		// Devuelve el ícono de resultado de cotejamiento de acuerdo al valor de coincidencia
		$iconos = array(
			0 		=> "<i class='fa fa-times ctjerr'></i>",
			1 		=> "<i class='fa fa-check ctj_ok'></i>",
		);

		return $iconos[ $valor ];
	}
	/* ----------------------------------------------------------------------------------- */
	function elementoListadoCotejamiento( $item, $contenido ){
		// Devuelve una fila a imprimirse en la tabla de cotejamiento de un pedido con los resultados del mismo

		$ref 	= $item["referencia"];
		$cant 	= $item["cantidad"];
		$icono 	= iconoCoincidenciaCotejamiento( $contenido );

		$fila = "<tr><td>$ref</td><td>$cant</td><td>$icono</td>";

		return $fila;
	}
	/* ----------------------------------------------------------------------------------- */
	function elementosRegistroPedido( $items_registro ){
		// Devuelve los elementos a imprimirse en tabla pedido contenido en registro de BD
		$fila = "";
		foreach ( $items_registro as $reg ) {
			$ref 	= $reg["referencia"];
			$cant 	= $reg["cantidad"];
			$fila .= "<tr><td>$ref</td><td>$cant</td><td></td>";
		}

		return $fila;
	}
	/* ----------------------------------------------------------------------------------- */
	function contenidoEnRegistroPedido( $item, $items_registro ){
		// Devuelve si un item [ref y cantidad] se encuentra en los items de un pedido
		$contenido = 0;
		foreach ( $items_registro as $reg ) {
			if( $reg["referencia"] == $item["referencia"] )
				if( $reg["cantidad"] == $item["cantidad"] )
					$contenido = 1;
		}

		return $contenido;
	}
	/* ----------------------------------------------------------------------------------- */
	/*function chequearItemsPedido( $items_archivo, $items_registro ){
		// Verifica si los ítems leídos por archivo coinciden con los ítems de registro del pedido

		$coincidencias = 0;
		$coincide = false;
		$cotejamiento = "";

		foreach ( $items_archivo as $item ){
			$contenido = contenidoEnRegistroPedido( $item, $items_registro );
			$coincidencias 	+= $contenido;
			$cotejamiento 	.= elementoListadoCotejamiento( $item, $contenido );
		}
		if( count( $items_archivo ) == $coincidencias ) $coincide = true;
		
		if( count( $items_archivo ) != count( $items_registro ) ) $coincide = false;

		$chequeo["coincidencia"] = $coincide;
		$chequeo["cotejamiento"] = $cotejamiento;
		$chequeo["regis_pedido"] = elementosRegistroPedido( $items_registro );

		return $chequeo;
	}*/
	/* ----------------------------------------------------------------------------------- */
	function chequearItemsPedido( $items_archivo, $items_registro ){
		// Verifica si los ítems leídos por archivo coinciden con los ítems de registro del pedido

		$coincidencias = 0;
		$coincide = false;
		$cotejamiento = "";

		foreach ( $items_archivo as $item ){
			$contenido = contenidoEnRegistroPedido( $item, $items_registro );
			$coincidencias 	+= $contenido;
			$cotejamiento 	.= elementoListadoCotejamiento( $item, $contenido );
		}
		if( count( $items_archivo ) == $coincidencias ) $coincide = true;
		
		if( count( $items_archivo ) != count( $items_registro ) ) $coincide = false;

		$chequeo["coincidencia"] = $coincide;
		$chequeo["cotejamiento"] = $cotejamiento;
		$chequeo["regis_pedido"] = elementosRegistroPedido( $items_registro );

		return $chequeo;
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
			$chk 			= chequearItemsPedido( $items_archivo["items"], $items_registro );
			$rsp["ctj"] 	= $chk["cotejamiento"];
			$rsp["reg_p"] 	= $chk["regis_pedido"];

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