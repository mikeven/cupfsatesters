<?php
ini_set( 'display_errors', 1 );
require ('bd.php');

if(isset($_POST['login']))
	$login=$_POST['login'];
if(isset($_POST['password']))
	$pass=$_POST['password'];

$sql="SELECT * FROM Colaborador where Usuario = '$login' and Password = '$pass' and Activo = 1"; 
//echo $sql;
$Rs = mysqli_query ($dbh, $sql);
$rows = mysqli_num_rows($Rs);
if ($rows == 1) {

	$row = mysqli_fetch_assoc($Rs); 
	$idp = $row['idColaborador'];
	$nombre = $row['Nombre'];
	$email = $row['Email'];
	$unidades = $row['Unidades'];
	
	session_start();
	$_SESSION["acceso"] 	= "23";
	$_SESSION["idp"] 		= $idp;
	$_SESSION["nombre"] 	= $nombre ;
	$_SESSION["email"] 		= $email ;
	$_SESSION["unidades"] 	= $unidades;
	$_SESSION["listado"] 	= $row['Listado'];
	$_SESSION["firstday"] 	= date("Y-m-d", strtotime('monday this week')) . " 00:00:00";  
	$_SESSION["lastday"] 	= date("Y-m-d", strtotime('sunday this week')) . " 00:00:00";  
	print "1";

} else {
	print "2";
}

?>