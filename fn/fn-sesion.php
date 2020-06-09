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
	
	$FirstDay 	= $_SESSION["firstday"];  
	$LastDay 	= $_SESSION["lastday"];
	/* ----------------------------------------------------------- */
	function diaValido(){
		// Devuelve falso si la fecha del día no coincide con los días indicados para permitir pedidos
		$valido = true;
		$dia = date( "l" );
		if ( ( $dia != "Monday" ) and ( $dia != "Tuesday" ) and ( $dia != "Wednesday" ) and ( $dia != "Thursday" ) ) {
			echo 'swal("Semana cerrada", "Solo se puede hacer pedido los días lunes, martes y miércoles.", "warning")';
			$valido = false;
		}

		return $valido;
	}
	/* ----------------------------------------------------------- */
	function obtenerLimitePorCategoriaItem( $id_fam ){
		// Devuelve el límite de ítems que puede solicitar un colaborador de acuerdo a la categoría de ítem
		
		$limites = array(

			1	=> $_SESSION["max_maq"],	// Maquillaje
			2	=> $_SESSION["max_skc"],	// SkinCare
			3	=> $_SESSION["max_frg"],	// Fragancias
			4	=> $_SESSION["max_plv"],	// PLV
			5	=> $_SESSION["max_ins"]		// Insumos
			
		);

		return $limites[ $id_fam ];
	}
	/* ----------------------------------------------------------- */
?>