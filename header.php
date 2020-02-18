<div id="header">

	<nav role="navigation">
	  <div id="menuToggle">
	 
	    <input type="checkbox" />
		    <span></span>
		    <span></span>
		    <span></span>
	    
	    <ul id="menu">
	      <a href="index.php"><li>Solicitud</li></a>
	      <!-- <a href="inventario.php"><li>Inventario</li></a> -->
		  <a href="xsesion.php"><li>Salir</li></a>
	    </ul>
	    
	  </div>
	</nav>

	<div style="font-size:14px; text-align: right; padding-right: 15px; margin-top:-20px; line-height: 30px;">
		<div><?php echo $nombre?></div>
		
	</div>
</div>
<br>
<center>
<div style="font-size:24px; margin-top:70px"><?php echo $titulo ?></div>
<br>

<script type="text/javascript">

	$(document).ready(function() {	
		// Evento para invocar la activación de un ítem	
		$("#menuToggle").on( "click", function(){
	        $("#cantidad").fadeToggle();
	    });
	});

</script>