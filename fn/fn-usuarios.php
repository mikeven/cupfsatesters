<?php 
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Funciones sobre usuarios ----------------- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */

	/* ----------------------------------------------------------- */
	function obtenerColaboradores( $dbh ){
		// Devuelve los registros de usuarios
		$sql = "SELECT * FROM Colaborador"; 
		$Rs = mysqli_query( $dbh, $sql );

		return $Rs;
	}
	/* ----------------------------------------------------------- */
	function obtenerColaboradorPorId( $dbh, $id ){
		// Devuelve los registros de usuarios
		$sql = "SELECT * FROM Colaborador where idColaborador = $id"; 
		return mysqli_fetch_assoc( mysqli_query( $dbh, $sql ) );
	}
	/* ----------------------------------------------------------- */
	function obtenerPedidosPorCliente( $dbh, $id ){
		// Devuelve los registros de pedidos de un usuario
		$sql = "SELECT idPedido, date_format( Fecha, '%d/%m/%Y') as Fecha FROM Pedido 
		where idColaborador = $id order by idPedido DESC;";

		return mysqli_query( $dbh, $sql );
	}
	/* ----------------------------------------------------------- */
	function existeArchivoImagen( $ref ){
		$archivo = "../fotos/$ref".".JPEG";
		return file_exists( $archivo );
	}
	/* ----------------------------------------------------------- */
	$usuarios = obtenerColaboradores( $dbh );
?>