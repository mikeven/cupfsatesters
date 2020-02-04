<?php 
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Funciones sobre listado de productos ----- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */

	session_start();
	// Reviso si la sesion caducó
	if(!isset($_SESSION['acceso'])) 
	header('Location: login.php?s=0');
	
	/* ----------------------------------------------------------- */
	function obtenerFamilias( $dbh ){
		// Devuelve los registros de familia
		$sql = "SELECT * FROM Familia"; 
		$Rs = mysqli_query( $dbh, $sql );

		return $Rs;
	}
	/* ----------------------------------------------------------- */
	function obtenerItemsFamilia( $dbh, $familia ){
		// Devuelve los ítems de una familia
		$sql = "SELECT * FROM Item  where Familia = $familia"; 

		$Rs = mysqli_query ( $dbh, $sql );
		$rows = mysqli_num_rows( $Rs );

		return $Rs;
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
	function existeArchivoImagen( $ref ){
		$archivo = "../fotos/$ref".".JPEG";
		return file_exists( $archivo );
	}
	/* ----------------------------------------------------------- */
	$familias = obtenerFamilias( $dbh );
?>