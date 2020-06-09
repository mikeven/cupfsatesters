<?php
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Acceso a datos de pedidos ---------------- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------------------------------- */
	function obtenerDataPedidoPorId( $dbh, $id ){
		// Devuelve el registro de pedido dado su id

		$sql = "SELECT p.idPedido, c.idColaborador, date_format( p.Fecha, '%d/%m/%Y') as Fecha, 
		c.Nombre, c.Usuario, c.Email, c.NroCliente, c.Listado, p.Confirmado, p.Estatus FROM Pedido p, Colaborador c 
		where c.idColaborador = p.idColaborador and p.idPedido = $id";

		return mysqli_fetch_assoc( mysqli_query( $dbh, $sql ) );
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerDetallePedidoPorId( $dbh, $idp ){
		// Devuelve los registros de detalle de pedido dado su id
		$q = "select i.idItem, i.Referencia1 as referencia, d.Cantidad1 as cantidad
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
	function obtenerIdItemPorReferencia( $dbh, $ref, $listado ){
		// Devuelve el id de un ítem dado su referencia
		$sql = "SELECT idItem from Item where Referencia1 = '$ref' and Listado = '$listado'";
		return mysqli_fetch_assoc( mysqli_query( $dbh, $sql ) );
	}
	/* ----------------------------------------------------------------------------------- */
	function actualizarEstatusPedido( $dbh, $idpedido, $st ){
		// Actualiza un pedido a estatus confirmado
		$q = "update Pedido set Estatus = $st where idPedido = $idpedido";
		
		return mysqli_query( $dbh, $q );
	}
	/* ----------------------------------------------------------------------------------- */
	function ingresarItemsPedido( $dbh, $idpedido, $id_item, $cant ){
		// Registra nuevos ítems a detalle de pedido existente
		$q = "INSERT INTO PedidoDetalle values ( NULL, $idpedido, $id_item, $cant, NOW() )";   
		
		$Rs = mysqli_query ( $dbh, $q );
		$afe = mysqli_affected_rows( $dbh );
		
		return $afe;
	}
	/* ----------------------------------------------------------------------------------- */
	function actualizarItemPedido( $dbh, $idpedido, $id_item, $cant ){
		// Actualiza en bd la cantidad de un ítem dado id de pedido y referencia de ítem
		$q = "update PedidoDetalle set Cantidad1 = $cant where idItem = $id_item";
		
		return mysqli_query( $dbh, $q );
	}
	/* ----------------------------------------------------------------------------------- */
	function actualizarInventarioPorPedidoConfirmado( $dbh, $pedido, $detalle_pedido ){
		// Registra en inventario las entradas de ítems de acuerdo al pedido confirmado
		include( "data-inventario.php" );
		$det_inv 			= "Carga por pedido"; 

		foreach( $detalle_pedido as $item ){
			
			registrarEntradaInventario( $dbh, $item["idItem"], $item["cantidad"], $pedido["idColaborador"], $det_inv, 6 );
		}
	}
	/* ----------------------------------------------------------------------------------- */
	function actualizarInventarioPorPedidoArchivo( $dbh, $pedido, $idcolaborador, $listado ){
		// Registra en inventario las entradas de ítems de acuerdo al pedido confirmado
		include( "data-inventario.php" );

		$detalle 			= "Carga por pedido";
		$items 				= array_merge( $pedido["items"], $pedido["nitems"], $pedido["pitems"] );
							// Ítems que pasan a formar parte del inventario: 
							// items:  ítems que modificaron su cantidad. 
							// nitems: ítems agregados desde archivo que no estaban en el pedido.
							// pitems: ítems presentes en el pedido. 

		foreach( $items as $i ){
			list( $ref, $cantidad ) = explode( '-', $i );
			$data_item = obtenerIdItemPorReferencia( $dbh, $ref, $listado );
			registrarEntradaInventario( $dbh, $data_item["idItem"], $cantidad, $idcolaborador, $detalle, 6 );
		}
	}
	/* ----------------------------------------------------------------------------------- */
	function procesarActualizacionPedido( $dbh, $pedido, $listado ){
		// Procesa los ítems modificados desde archivo para actualizar pedido registrado

		$idpedido 			= $pedido["idpedido_actarchivo"];
		
		if( !isset( $pedido["ritems"] ) ) 
			$pedido["ritems"] = array();	// No hay items para eliminar (cantidades en cero):

		if( isset( $pedido["ritems"] ) ) 
			$nitems 			= $pedido["nitems"];
		else
			$nitems 			= array();

		if( isset( $pedido["items"] ) ) 
			$moditems 			= $pedido["items"];
		else
			$moditems 			= array();

		$items 				= array_merge( $moditems, $pedido["ritems"] ); 
		// items con cantidades a modificar: moditems (cantidades diferentes al pedido) + ritems: cantidades que pasan a cero

		$actualizaciones 	= 0;

		// Actualización de cantidades
		foreach ( $items as $it ) {
			list( $ref, $cant ) = explode( '-', $it );
			$data_item = obtenerIdItemPorReferencia( $dbh, $ref, $listado );
			$actualizaciones += actualizarItemPedido( $dbh, $idpedido, $data_item["idItem"], $cant );
		}
		
		// Registro de nuevos ítems
		foreach ( $nitems as $it ) {
			list( $ref, $cant ) = explode( '-', $it );
			$data_item = obtenerIdItemPorReferencia( $dbh, $ref, $listado );
			$actualizaciones += ingresarItemsPedido( $dbh, $idpedido, $data_item["idItem"], $cant );
		}
		
		return $actualizaciones;
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
			0 		=> "<i class='fa fa-plus inf_ok'></i>",
			1 		=> "<i class='fa fa-exclamation ctjwrn'></i>",
			2 		=> "<i class='fa fa-check ctj_ok'></i>",
		);

		return $iconos[ $valor ];
	}
	/* ----------------------------------------------------------------------------------- */
	function iconoCotejamientoPedido( $valor ){
		// Devuelve el ícono de resultado de cotejamiento de acuerdo al valor de coincidencia
		$iconos = array(
			-1 		=> "<i class='fa fa-minus ctjerr'></i>",
			0 		=> "",
			1 		=> "<i class='fa fa-exclamation ctjwrn'></i>",
			2 		=> "<i class='fa fa-check ctj_ok'></i>",
		);

		return $iconos[ $valor ];
	}
	/* ----------------------------------------------------------------------------------- */
	function filaCotejamientoArchivo( $item ){
		// Devuelve una fila a imprimirse en la tabla de cotejamiento del archivo con los resultados del mismo

		$ref 	= $item["referencia"];
		$cant 	= $item["cantidad"];
		$campo 	= "<input type='hidden' name='pitems[]' value='$ref-$cant'>";
		$icono 	= iconoCotejamientoArchivo( $item["est_contenido"] );
		
		if( $item["est_contenido"] == 1 ) // Coincide referencia, cambia la cantidad: Actualización de cantidad
			$campo 	= "<input type='hidden' name='items[]' value='$ref-$cant'>";
		if( $item["est_contenido"] == 0 ) // Nueva referencia, se agrega item al pedido existente
			$campo 	= "<input type='hidden' name='nitems[]' value='$ref-$cant'>";

		$fila = "<tr><td>$ref $campo</td><td>$cant</td><td>$icono</td>";

		return $fila;
	}
	/* ----------------------------------------------------------------------------------- */
	function filaCotejamientoPedido( $item  ){
		// Devuelve una fila a imprimirse en la tabla de cotejamiento del pedido con los resultados del mismo

		$ref 	= $item["referencia"];
		$cant 	= $item["cantidad"];
		$campo 	= "";
		$icono 	= iconoCotejamientoPedido( $item["est_contenido"] );

		if( $item["est_contenido"] == -1 ) // Item en pedido, no presente en archivo: se cancela la cantidad
			$campo 	= "<input type='hidden' name='ritems[]' value='$ref-0'>";

		$fila = "<tr><td>$ref $campo</td><td>$cant</td><td>$icono</td>";

		return $fila;
	}
	/* ----------------------------------------------------------------------------------- */
	function estaContenidoEnRegistroPedido( $item, $items_registro ){
		// Devuelve si un item [ref y cantidad] se encuentra en los items de un pedido
		$elem_item["est_contenido"] = 0;
		$elem_item["referencia"] 	= "-";
		$elem_item["cantidad"] 		= "-";

		foreach ( $items_registro as $reg ) {

			if( $reg["referencia"] == $item["referencia"] ){
				
				$elem_item["est_contenido"] = 1; 
				$elem_item["referencia"] 	= $item["referencia"];
				$elem_item["cantidad"] 		= $reg["cantidad"];

				if( $reg["cantidad"] == $item["cantidad"] ){
					$elem_item["est_contenido"] = 2; 
				}
			}

		}

		return $elem_item;
	}
	/* ----------------------------------------------------------------------------------- */
	function estaContenidoEnArchivo( $reg, $items_archivo ){
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
	function cotejarClienteArchivoPedido( $pedido, $ncliente ){
		// Verifica que el cliente leído por archivo coincida con el registrado en pedido
		
		return ( $pedido["NroCliente"] == $ncliente );
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerCotejamientoArchivoPedido( $vector_ctj_pedido, $vector_ctj_archivo, $pedido, $ncliente ){
		// Devuelve el resultado del cotejamiento del archivo con el registro del pedido

		$cotejamiento["revision_archivo"] = ""; 
		$cotejamiento["revision_pedido"] = "";

		foreach ( $vector_ctj_pedido as $item )
			$cotejamiento["revision_pedido"] 	.= filaCotejamientoPedido( $item  );
		foreach ( $vector_ctj_archivo as $item )
			$cotejamiento["revision_archivo"] 	.= filaCotejamientoArchivo( $item );

		$cotejamiento["revision_cliente"] = cotejarClienteArchivoPedido( $pedido, $ncliente );

		return $cotejamiento;
	}
	/* ----------------------------------------------------------------------------------- */
	function realizarCotejamientoArchivoPedido( $data_archivo, $items_registro, $pedido ){
		// Verifica si los ítems leídos por archivo coinciden con los ítems de registro del pedido

		$vector_ctj_archivo 	= array();
		$vector_ctj_pedido 		= array();
		$items_archivo 			= $data_archivo["items"];

		// Recorrido por cada item del archivo se coteja con el pedido
		foreach ( $items_archivo as $item ){
			$item_contenido 			= estaContenidoEnRegistroPedido( $item, $items_registro );
			$item["est_contenido"]		= $item_contenido["est_contenido"];
			$vector_ctj_pedido[]		= $item_contenido;
			$vector_ctj_archivo[]		= $item;		
		}

		$vector_ctj_pedido 		= cotejarPedidoConArchivo( $items_archivo, $items_registro, $vector_ctj_pedido );

		return obtenerCotejamientoArchivoPedido( $vector_ctj_pedido, $vector_ctj_archivo, $pedido, $data_archivo["cliente"] );
	}
	/* ----------------------------------------------------------------------------------- */
	function cotejarPedidoConArchivo( $items_archivo, $items_registro, $vector_ctj_pedido ){
		// Verifica si los ítems del pedido están contenidos en el archivo leído

		foreach ( $items_registro as $reg ){
			$coincidencia 				= estaContenidoEnArchivo( $reg, $items_archivo );
			if( $coincidencia == 0 ){
				$reg["est_contenido"]	= -1;
				$vector_ctj_pedido[] 	= $reg;
			}
		}

		return $vector_ctj_pedido;
	}
	/* ----------------------------------------------------------------------------------- */
	function guardarArchivoPedido( $file ){
		// Guarda el archivo en formato xls en ubicación determinada

		$sourcePath = $file['tmp_name'];
        $targetPath = "excl/".$file['name'];
       
        if( move_uploaded_file( $sourcePath, $targetPath ) ){
        	$uploadedFile = $targetPath;
        }

        return $uploadedFile;
	}
	/* ----------------------------------------------------------------------------------- */
	function extensionValidaArchivoPedido( $archivo ){
		// Chequea la extensión válida del archivo
		$valido = true;
		
		$arch_estr = ( explode( ".", $archivo ) );
		$extension = end( $arch_estr );
		
		if( $extension != "xlsx" ) 
			$valido = false;

		return $valido;
	}
	/* ----------------------------------------------------------------------------------- */
	//Carga de archivo excel para cotejar con pedido
	ini_set( 'display_errors', 1 );

	if( isset ( $_POST["idp"] ) ){
		include( "dataxls.php" );
		include( "../../bd.php" );
		$pedido = obtenerDataPedidoPorId( $dbh, $_POST["idp"] );

		if( isset( $_FILES['file'] ) ){

			if( extensionValidaArchivoPedido( $_FILES['file']['name'] ) ){
				
				$archivo 		= guardarArchivoPedido( $_FILES['file'] );
				$data_archivo 	= leerArchivo( $archivo, "" );
				$items_registro = obtenerDetallePedidoPorId( $dbh, $_POST["idp"] );
				$cotejamiento 	= realizarCotejamientoArchivoPedido( $data_archivo, $items_registro, $pedido );
				
				$rsp["ctj_arc"] = $cotejamiento["revision_archivo"];
				$rsp["ctj_ped"] = $cotejamiento["revision_pedido"];	
				$rsp["ncl_arc"] = "Cliente #".$data_archivo["cliente"];
				$rsp["ncl_ped"] = "Cliente #".$pedido["NroCliente"];
				$rsp["nc_cnst"] = $data_archivo["nc_const"];

				if( $cotejamiento["revision_cliente"] == true && $rsp["nc_cnst"] == true ){
					$rsp["exito"] 	= 1;
					$rsp["imp"] 	= "Archivo leído";
				}else{
					$rsp["exito"] 	= -2;
					$rsp["imp"] 	= "El número de cliente es incorrecto o no coincide con pedido";
				}

			}else{
				$rsp["exito"] 	= -3;
				$rsp["imp"] 	= "Error en lectura de archivo";
			}

		} else {
			$rsp["exito"] = -1;
			$rsp["imp"] = "No se indicó archivo";
		}
		
		echo json_encode( $rsp );
	}
	/* ----------------------------------------------------------------------------------- */
	if( isset( $_POST["pedido_confirmado"] ) ){
		include( "../../bd.php" );
		
		$idpedido 			= $_POST["pedido_confirmado"];
		$data_pedido 		= obtenerDataPedidoPorId( $dbh, $idpedido );
		$detalle_pedido 	= obtenerDetallePedidoPorId( $dbh, $idpedido );

		$resultado 			= actualizarEstatusPedido( $dbh, $idpedido, 1 );

		if( $resultado ){

			actualizarInventarioPorPedidoConfirmado( $dbh, $data_pedido, $detalle_pedido );

			$rsp["exito"] 	= 1;
			$rsp["imp"] 	= "<span class='ctj_ok'>Pedido confirmado con éxito</span>";

		} else {

			$rsp["exito"] 	= -1;
			$rsp["imp"] 	= "Error al confirmar pedido";
		}
		
		echo json_encode( $rsp );
	}
	/* ----------------------------------------------------------------------------------- */
	if( isset( $_POST["pedido_archivo"] ) ){
		include( "../../bd.php" );
	
		parse_str( $_POST["pedido_archivo"], $pedido );
		$data_pedido 	= obtenerDataPedidoPorId( $dbh, $pedido["idpedido_actarchivo"] );
		$listado 		= $data_pedido["Listado"];
		
		if( isset( $pedido["items"] ) || isset( $pedido["nitems"] ) || isset( $pedido["ritems"] ) ){
			$regs_act 	= procesarActualizacionPedido( $dbh, $pedido, $listado );
			
			if( $regs_act > 0 ){

				actualizarEstatusPedido( $dbh, $pedido["idpedido_actarchivo"], 1 );
				actualizarInventarioPorPedidoArchivo( $dbh, $pedido, $data_pedido["idColaborador"], $listado );

				$rsp["exito"] 	= 1;
				$rsp["imp"] 	= "<span class='ctj_ok'>Pedido confirmado con éxito</span>";

			} else {

				$rsp["exito"] 	= -1;
				$rsp["imp"] 	= "<span class='ctj_err'>Error al actualizar pedido</span>";

			}

		} else {
			actualizarEstatusPedido( $dbh, $pedido["idpedido_actarchivo"], 1 );
			$rsp["exito"] 	= 1;
			$rsp["imp"] 	= "Pedido confirmado sin cambios";
		}
		
		echo json_encode( $rsp );
		
	}
	/* ----------------------------------------------------------------------------------- */
?>