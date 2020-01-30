<?php
echo $_SERVER['HTTP_USER_AGENT'];
?>

<html><head>
<script>


var ua = window.navigator.userAgent;
var msie = ua.indexOf("MSIE ");

if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))  // If Internet Explorer, return version number
{
    alert(parseInt(ua.substring(msie + 5, ua.indexOf(".", msie))));
}
else  // If another browser, return 0
{
    alert('otherbrowser');
}

 
</script>
</head></html>
