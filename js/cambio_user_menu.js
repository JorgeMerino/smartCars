$(document).ready(function(){
	$(".user_menu li:not(.hola_usuario)").click(function(){
		$(".user_menu li:not(.hola_usuario)").removeClass("act first");
		$(this).addClass("act first");
	});
});