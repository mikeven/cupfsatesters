<?php 
	/* ----------------------------------------------------------- */
	/* Cupfsa Testers - Funciones sobre sesión de usuario -------- */
	/* ----------------------------------------------------------- */
	/* ----------------------------------------------------------- */

	session_start();
	// Reviso si la sesion caducó
	if( !isset( $_SESSION['idp'] ) ) 
		header( 'Location: login.php?s=0' );
	/* ----------------------------------------------------------- */
	$idpersona 	= $_SESSION["idp"];
	$nombre 	= $_SESSION["nombre"];
	$email 		= $_SESSION["email"];
	$unidades 	= $_SESSION['unidades'];
	$FirstDay 	= $_SESSION["firstday"];  
	$LastDay 	= $_SESSION["lastday"];
	
	/* ----------------------------------------------------------- */
	function diaValido(){
		// Devuelve falso si la fecha del día no coincide con los días indicados para permitir pedidos
		$valido = true;
		$dia = date( "l" );
		if ( ( $dia != "Monday" ) and ( $dia != "Tuesday" ) and ( $dia != "Wednesday" ) ) {
			echo 'swal("Semana cerrada", "Solo se puede hacer pedido los días lunes, martes y miércoles.", "warning")';
			$valido = false;
		}

		return $valido;
	}
?>