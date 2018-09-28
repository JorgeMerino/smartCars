$(document).ready(function(){	
	$("input#nombreUsuario").blur(function(){
		if((this).value.match(/^[a-z áéíóúüñÁÉÍÓÚÑ \s]{2,30}$/i)){
			$(this).closest("div.form-group").removeClass("has-error");
		}
		else{
			$(this).closest("div.form-group").addClass("has-error");
		}
	});
});