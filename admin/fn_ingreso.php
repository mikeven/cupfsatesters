<?php
ini_set( 'display_errors', 1 );
//require ('bd.php');
session_start();

if(isset($_POST['login']))
	$login=$_POST['login'];
if(isset($_POST['password']))
	$pass=$_POST['password'];
/*
if (($pass == "CupfsA-2019") && ($login == "admin")) {
	session_start();
	$_SESSION["acceso"] = "23";
	$_SESSION["items"] = "Panama";
	print "1";
} else
	print "2";
*/
	
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

?>