<?php
ini_set( 'display_errors', 1 );
require ('../bd.php');
session_start();

if( isset( $_POST['login'] ) )
	$login = $_POST['login'];
if( isset( $_POST['password'] ) )
	$pass = $_POST['password'];
/*
if (($pass == "CupfsA-2019") && ($login == "admin")) {
	session_start();
	$_SESSION["acceso"] = "23";
	$_SESSION["items"] = "Panama";
	print "1";
} else
	print "2";

	
switch ($login) {
    case "admin":
        if ($pass == "CupfsA-2019") {
        	$_SESSION["acceso"] = "23";
			$_SESSION["items"] = "Panama";
			print "1";
		}
		break;
    case "anette":
        if ($pass == "SAB-2020") {
        	$_SESSION["acceso"] = "23";
			$_SESSION["items"] = "PABoutiques";
			print "1";
		}
        break;
   
    default:
        print "2";
}	
*/

$sql = "SELECT * FROM Admin where Username = '$login' and Password = '$pass' and Activo = 1"; 

$Rs 	= mysqli_query ( $dbh, $sql );
$rows 	= mysqli_num_rows( $Rs );

if ( $rows <> 0 ) {

	$row = mysqli_fetch_assoc( $Rs ); 
	$_SESSION["acceso"] 	= "23";
	$_SESSION["Admin"] 		= $row;
	print "1";

} else {
	print "2";
}
?>