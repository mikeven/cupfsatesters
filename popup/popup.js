
$("#close").click(function(){
	$('.popScroll').fadeOut(900, function(){ 
		$(this).remove();
		$('body').toggleClass('overlay');
		
		$('#tt1').fadeIn(900);
		
		setTimeout(function() {
			$('#tt1').fadeOut(900);
		}, 8000);
	});
  
});
  
