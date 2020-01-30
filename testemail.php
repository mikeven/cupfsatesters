<?php
//Data del email
$subject = "Te damos la bienvenida a CHANEL";
//$subject = '=?UTF-8?B?'.base64_encode($subject).'?=';
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= 'From: CHANEL INDUCTION <iamchanel@cupfsa.com>' . "\r\n";
//$headers .= 'Cc: flepage@cupfsa.com' . "\r\n";
$email = "flepage@cupfsa.com";
//$email = "flepage@gmail.com";
$body = "Hola";

if(@mail($email,$subject,$body,$headers))
{
	$cont += 1;
	echo $email . "<br>";	
	
}else{
  echo "Error con: " . $email;
}


?>