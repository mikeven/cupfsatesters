<?php
ini_set( 'display_errors', 1 );
//require ('bd.php');

if(isset($_POST['login']))
	$login=$_POST['login'];
if(isset($_POST['password']))
	$pass=$_POST['password'];

if (($pass == "CupfsA-2019") && ($login == "admin")) {
	session_start();
	$_SESSION["acceso"] = "23";
	print "1";
} else
	print "2";

	
?>