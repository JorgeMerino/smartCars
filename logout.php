<!DOCTYPE html>
<html>
	<head>
		<title>smartCars</title>
		<meta charset="UTF-8">
		<?php
			require_once "conexion.php";
			function cerrarSesion(){
				//Abrimos la sesion creada
				session_start();
				if(isset($_SESSION["smartCars"])){
					//Borramos los datos de la sesion
					session_unset($_SESSION["smartCars"]);
					//Borramos la sesion
					session_destroy();
				}
			}
			function borrarCookies(){
				//Cuando a una cookie se le asigna tiempo -1, esta se caduca automáticamente
				if(isset($_COOKIE["usuario"]))
					setcookie("usuario", "", time()-1);
				if(isset($_COOKIE["pass"]))
					setcookie("pass", "", time()-1);
			}
		?>
	</head>
	<body><div>
		<?php
			//Cerramos la sesion
			cerrarSesion();
			borrarCookies();
			header('Location: index.php');
		?>
		</div>
	</body>
</html>