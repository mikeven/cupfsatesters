<?php
	ini_set( 'display_errors', 1 );
	require ('bd.php');

	if( isset( $_POST['login'] ) )
		$login = $_POST['login'];
	if( isset( $_POST['password'] ) )
		$pass = $_POST['password'];

	$sql = "SELECT * FROM Colaborador where Usuario = '$login' and Password = '$pass' and Activo = 1"; 
	
	$Rs = mysqli_query ( $dbh, $sql );
	$rows = mysqli_num_rows( $Rs );

	if ( $rows == 1 ) {

		$row = mysqli_fetch_assoc( $Rs ); 
		
		session_start();
		$_SESSION["acceso"] 	= "23";
		$_SESSION["idp"] 		= $row['idColaborador'];
		$_SESSION["nombre"] 	= $row['Nombre'];
		$_SESSION["email"] 		= $row['Email'];

		$_SESSION["max_maq"] 	= $row['MaxMU'];
		$_SESSION["max_skc"] 	= $row['MaxSC'];
		$_SESSION["max_frg"] 	= $row['MaxFR'];
		$_SESSION["max_plv"] 	= $row['MaxPLV'];
		$_SESSION["max_ins"] 	= $row['MaxINS'];

		$_SESSION["listado"] 	= $row['Listado'];
		$_SESSION["accesoinv"] 	= $row['ModInventario'];
		$_SESSION["firstday"] 	= date("Y-m-d", strtotime('first day of this month')) . " 00:00:00";  
		$_SESSION["lastday"] 	= date("Y-m-d", strtotime('last day of this month')) . " 00:00:00";  
		print "1";
	} 
	else
		print "2";
?>