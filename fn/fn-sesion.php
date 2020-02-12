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
	$idpersona = $_SESSION["idp"];
	$nombre = $_SESSION["nombre"];
	$email = $_SESSION["email"];
	$unidades = $_SESSION['unidades'];
	$FirstDay = $_SESSION["firstday"];  
	$LastDay = $_SESSION["lastday"];
?>