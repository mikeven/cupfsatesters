<?php
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Acceso a datos de referencias ------------ */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */
	function activarItem( $dbh, $id, $valor ){
		//Devuelve el registro de las órdenes registradas
		$q = "update Item set Activo = $valor where idItem = $id";
		$data = mysqli_query( $dbh, $q );
		var_dump($data);
		return $data;
	}
	/* ----------------------------------------------------------------------------------- */
	//Registrar confirmación/entrega de pedido
	if( isset( $_POST["item_activacion"] ) ){
		include( "../../bd.php" );
		echo activarItem( $dbh, $_POST["item_activacion"], $_POST["val"] );
	}
	/* ----------------------------------------------------------------------------------- */
?>