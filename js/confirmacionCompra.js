
function confirmarCompra(matricula, nombre, permisosCompatibles, permisoVehiculo){
	if(permisosCompatibles){
		var respuesta = confirm("¿Realmente desea adquirir el siguiente vehículo: " + nombre + "?");
		if(respuesta == true){
			$.ajax({  
				type: "GET", 
				url: "adquirirVehiculo.php",  //Llama a adquirirVehiculo.php (en Ajax se ejecuta en segundo plano)
				data: "matricula=" + matricula,  //Le pasa a adquirirVehiculo.php las variables necesarias(matricula)
				success: function(html){  //Lee el html de login.php
					if(html == "true"){
						alert("Vehículo adquirido satisfactoriamente.");
						location.reload(); 
					}  
					else if(html == "false"){  
						alert("No dispone de suficiente efectivo para poder adquirir este vehículo.");
					}
					else{
						alert("ERROR: " + html);
					}
				},  
				beforeSend: function(){  
					$("#add_err").html("Cargando...");
				}  
			});			
		}
	}
	else
		alert("No puede adquirir este vehículo, necesita el permiso de conducir Tipo " + permisoVehiculo + ".");
}