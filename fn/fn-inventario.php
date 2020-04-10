<?php 
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Funciones sobre listado de productos ----- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */
	
	/* ----------------------------------------------------------- */
	function obtenerFamilias( $dbh ){
		// Devuelve los registros de familia
		$sql = "SELECT * FROM Familia"; 
		$Rs = mysqli_query( $dbh, $sql );

		return obtenerListaRegistros( $Rs );
	}
	/* ----------------------------------------------------------- */
	function obtenerItemsFamilia( $dbh, $familia ){
		// Devuelve los ítems de una familia
		$sql = "SELECT * FROM Item  where Familia = $familia"; 
		$Rs = mysqli_query ( $dbh, $sql );
		
		return obtenerListaRegistros( $Rs );
	}
	/* ----------------------------------------------------------- */
	function tieneMovimientoInventario( $reg_item_inv ){
		// Devuelve si un item de inventario posee algún movimiento asociado a un usuario
		
		return ( $reg_item_inv["entradas"] || $reg_item_inv["salidas"] );
	}
	/* ----------------------------------------------------------- */
	function obtenerItemsInventario( $dbh, $id_item, $id_colaborador ){
		// Devuelve el inventario disponible de un item para un usuario
		$sql = "select sum( ifnull(iv.entrada, 0)) as entradas,  sum( ifnull(iv.salida, 0)) as salidas 
				from Testers.Inventario iv where iv.idItem = $id_item and iv.idColaborador = $id_colaborador"; 

		return mysqli_fetch_assoc( mysqli_query( $dbh, $sql ) );
	}
	/* ----------------------------------------------------------- */
	function poseeValoresInventario( $dbh, $idColaborador, $items_familia ){
		// Devuelve verdadero si un usuario posee movimiento de inventario en items de una familia
		$posee_reg_inventario = false;
		
		foreach ( $items_familia as $item ) {
			$inventario = obtenerItemsInventario( $dbh, $item['idItem'], $idColaborador );
			if( tieneMovimientoInventario( $inventario ) ){
				$posee_reg_inventario = true;
				break;
			}
		}

		return $posee_reg_inventario;
	}
	/* ----------------------------------------------------------- */
	function totalDisponibleInv( $inventario_item ){
		// Devuelve la resta de todas las entradas y salidas de un ítem en inventario de colaborador
		
		return $inventario_item["entradas"] - $inventario_item["salidas"];
	}
	/* ----------------------------------------------------------- */
	function obtenerMovimientosItemColaborador( $dbh, $idc, $idi ){
		// Devuelve los registros de movimiento de inventario de un item asociado a un colaborador
		
		$sql = "SELECT i.entrada, i.salida, date_format( i.fecha, '%d/%m/%Y') as fecha, i.detalle, m.nombre as motivo 
		FROM Inventario i, Motivo m where i.idColaborador = $idc and i.idItem = $idi and i.idMotivo = m.idMotivo
		order by i.idInventario DESC";

		return obtenerListaRegistros( mysqli_query ( $dbh, $sql ) );
	}
	/* ----------------------------------------------------------- */
	function obtenerItemPorId( $dbh, $id ){
		// Devuelve los ítems de una familia
		$sql = "SELECT * FROM Item  where idItem = $id"; 

		return mysqli_fetch_assoc( mysqli_query( $dbh, $sql ) );
	}
	/* ----------------------------------------------------------- */
	function sop( $val_list, $val_reg ){
		//Retorna el parámetro 'selected' para opciones de listas desplegables: marcar como seleccionada
		$sel = "";
		if( $val_list == $val_reg ) $sel = "selected";
		return $sel;
	}
	/* ----------------------------------------------------------- */
	function obtenerItemsPedidoFamilia( $familia ){
		// Devuelve los ítems de una familia
		$sql = "SELECT * FROM Item  where Familia = $familia and Activo = 1"; 

		$Rs = mysqli_query ( $dbh, $sql );
		$rows = mysqli_num_rows( $Rs );
	}	
	/* ----------------------------------------------------------- */
	function entrada_salida( $registro ){
		// Devuelve la cantidad y signo de registro de movimiento en función de si es entrada o salida
		
		if( $registro["entrada"] ){
			$mov["cant"] = $registro["entrada"];
			$mov["icon"] = "<i class='fas fa-arrow-up fa-2x inv_ent' title='entrada'>";
		}
		if( $registro["salida"] ){
			$mov["cant"] = $registro["salida"];
			$mov["icon"] = "<i class='fas fa-arrow-down fa-2x inv_sal' title='salida'>";
		}

		return $mov;
	}
	/* ----------------------------------------------------------- */
	function obtenerListaRegistros( $data ){
		//Devuelve un arreglo con los resultados de un resultset de BD
		$lista_c = array();
		if( $data ){
			while( $c = mysqli_fetch_array( $data ) ){
				$lista_c[] = $c;	
			}
		}
		return $lista_c;
	}
	/* ----------------------------------------------------------- */
	function filaParImpar( $n ){
		// Devuelve el nombre de una clase para las filas de tabla, según paridad de número de fila
		$res = ( $n%2 == 0 ) ? "invpar" : "invimpar";
		echo $res;
	}
	/* ----------------------------------------------------------- */
	function topeUnidadesRestar( $dsp ){
		// Devuelve el máx de unidades que se pueden retirar de acuerdo a la disponibilidad
		$max = 5;
		return ( $dsp >= $max  ) ? $dsp : $dsp;
	}
	/* ----------------------------------------------------------- */
	function obtenerMotivosRetiroInventario( $dbh ){
		// Devuelve la lista de motivos de retiros en los registros de inventario
		$sql = "SELECT * FROM Motivo where opcion_usuario = 1"; 
		$Rs = mysqli_query( $dbh, $sql );

		return obtenerListaRegistros( $Rs );
	}
	/* ----------------------------------------------------------- */
?>