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
		mysqli_query( $dbh, $q );

		return mysqli_insert_id( $dbh );
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
	function procesarCargaInventario( $dbh, $inventario ){
		// Procesa los ítems cargados desde archivo para ingresarlos a la base de datos.
		$items_inventario = $inventario["items"];
		$error = false;

		foreach ( $items_inventario as $item ) {
			list( $ref, $cant ) = explode( '-', $item );
			$data_item = obtenerItemPorReferencia( $dbh, $ref, $inventario["listado"] );
			$id_reg = registrarEntradaInventario( $dbh, $data_item["idItem"], $cant, $inventario["colaborador"], $detalle, 7 );	
			if( $id_reg == 0 ) 
				$error = true;
		}

		return $error;
	}	
	/* ----------------------------------------------------------------------------------- */
	function obtenerItemPorReferencia( $dbh, $ref, $listado ){
		// Devuelve el registro de un ítem dado su número de referencia y listado
		$sql = "select * from Item where Referencia1 = '$ref' and listado = '$listado'";
		return mysqli_fetch_assoc( mysqli_query( $dbh, $sql ) );
	} 
	/* ----------------------------------------------------------------------------------- */
	function iconoListadoInventario( $reg_item ){
		// Devuelve el ícono correspondiente al ítem listado en el inventario, dependiendo si existe en sistema o no
		$icono = "<i class='fa fa-times ctjerr'></i>";

		if( isset( $reg_item["idItem"] ) )
			$icono = "<i class='fa fa-check ctj_ok'></i>";

		return $icono;
	}
	/* ----------------------------------------------------------------------------------- */
	function filaLecturaInventario( $item, $reg_item ){
		// Devuelve una fila a imprimirse en la tabla de cotejamiento del pedido con los resultados del mismo
		
		$d1 	= $item["desc1"];
		$d2 	= $item["desc2"];
		$d3 	= $item["desc3"];
		$ref 	= $item["referencia"];
		$cant 	= $item["cantidad"];

		$icono 	= iconoListadoInventario( $reg_item );
		$campo 	= "<input type='hidden' name='items[]' value='$ref-$cant'>";
		
		$fila = "<tr> <td>$d1 $campo</td> <td>$d2</td> <td>$d3</td> <td>$ref</td> <td>$cant</td> <td>$icono</td> </tr>";

		return $fila;
	}
	/* ----------------------------------------------------------------------------------- */
	function obtenerLecturaInventario( $dbh, $data_archivo, $listado ){
		// Procesa la lectura del archivo y devuelve los datos a mostrar de la carga de inventario leída
		$lectura_inventario = "";

		foreach ( $data_archivo as $item ){
			$reg_item 			= obtenerItemPorReferencia( $dbh, $item["referencia"], $listado );
			$lectura_inventario .= filaLecturaInventario( $item, $reg_item );
		}

		return $lectura_inventario;
	}
	/* ----------------------------------------------------------------------------------- */
	function extensionValidaArchivoInventario( $archivo ){
		// Chequea la extensión válida del archivo
		$valido = true;
		
		$arch_estr = ( explode( ".", $archivo ) );
		$extension = end( $arch_estr );
		
		if( $extension != "xlsx" ) 
			$valido = false;

		return $valido;
	}
	/* ----------------------------------------------------------------------------------- */
	function guardarArchivoInventario( $file ){
		// Guarda el archivo en formato xls en ubicación determinada

		$sourcePath = $file['tmp_name'];
        $targetPath = "excl/".$file['name'];
       
        if( move_uploaded_file( $sourcePath, $targetPath ) ){
        	$uploadedFile = $targetPath;
        }

        return $uploadedFile;
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
	if( isset ( $_POST["inv_colaborador"] ) ){
		// Invocación a mostrar contenido de archivo para la carga de inventario

		include( "dataxls.php" );
		include( "../../bd.php" );
		$listado = $_POST["listado_c"];

		if( isset( $_FILES['file'] ) ){

			if( extensionValidaArchivoInventario( $_FILES['file']['name'] ) ){
				
				$archivo 		= guardarArchivoInventario( $_FILES['file'] );
				$data_archivo 	= leerArchivoInventario( $archivo, "" );
				$lectura_inv 	= obtenerLecturaInventario( $dbh, $data_archivo["items"], $listado );

				if( $data_archivo["exito"] ){
					$rsp["exito"] 	= 1;
					$rsp["imp"] 	= "Archivo leído";
					$rsp["tabla"]	= $lectura_inv;
					$rsp["lista"]	= $listado;
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
	if( isset( $_POST["inventario_archivo"] ) ){
		include( "../../bd.php" );
	
		parse_str( $_POST["inventario_archivo"], $inventario );

		$error_carga = procesarCargaInventario( $dbh, $inventario );

		if( $error_carga == false ){
			$rsp["exito"] = 1;
			$rsp["imp"] = "<span class='ctj_ok'>Inventario cargado con éxito</span>";
		}else{
			$rsp["exito"] = -1;
			$rsp["imp"] = "<span class='ctj_err'>Error al actualizar pedido</span>";
		}
		
		echo json_encode( $rsp );
	}
	/* ----------------------------------------------------------------------------------- */
?>