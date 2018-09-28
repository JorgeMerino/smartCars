$(document).ready(function(){	
	$("input#nombreUsuario").blur(function(){
		if((this).value.match(/^[a-z áéíóúüñÁÉÍÓÚÑ \s]{2,30}$/i)){
			$(this).closest("div.form-group").removeClass("has-error");
		}
		else{
			$(this).closest("div.form-group").addClass("has-error");
		}
	});
	$("input#apellidoUsuario").blur(function(){
		if((this).value.match(/^[a-z áéíóúüñÁÉÍÓÚÑ \s]{2,30}$/i)){
			$(this).closest("div.form-group").removeClass("has-error");
		}
		else{
			$(this).closest("div.form-group").addClass("has-error");
		}
	});
	$("input#dniUsuario").blur(function(){
		if((this).value.match(/^\d{8}[A-Z]$/i)){
			$(this).closest("div.form-group").removeClass("has-error");
		}
		else{
			$(this).closest("div.form-group").addClass("has-error");
		}
	});
	$("input#fechaNacimientoUsuario").blur(function(){
		if((this).value.match(/^\d{1,2}\/\d{1,2}\/(19|20)\d{2}$/i)){
			$(this).closest("div.form-group").removeClass("has-error");
		}
		else{
			$(this).closest("div.form-group").addClass("has-error");
		}
	});
	$("input#emailUsuario").blur(function(){
		if((this).value.match(/^[a-z]+[\._a-z0-9-]*@[a-z]+(\.[a-z]{2,4})$/i)){
			$(this).closest("div.form-group").removeClass("has-error");
		}
		else{
			$(this).closest("div.form-group").addClass("has-error");
		}
	});
	$("input#pass1Usuario").blur(function(){
		if((this).value.match(/^[a-zA-Z0-9]{6,15}$/)){
			$(this).closest("div.form-group").removeClass("has-error");
		}
		else{
			$(this).closest("div.form-group").addClass("has-error");
		}
	});
	$("input#pass2Usuario").blur(function(){
		if((this).value.match(/^[a-zA-Z0-9]{6,15}$/)){
			$(this).closest("div.form-group").removeClass("has-error");
		}
		else{
			$(this).closest("div.form-group").addClass("has-error");
		}
	});
});