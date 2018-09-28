<?php
	//Abrimos la sesion creada
	session_start();
	//Si no existe sesión se va a logout.php
	if(!isset($_SESSION["smartCars"]) || !isset($_COOKIE["usuario"]) || !isset($_COOKIE["pass"])){
		header('Location: logout.php');
	}
	else{
		$usuario = $_COOKIE["usuario"];
		require_once "conexion.php";
	}
?>
<!DOCTYPE HTML>
<html lang="es">
	<head>
		<!-- HEAD -->
		<?php include 'head.php'; ?>
		
		<script src="js/menu_auxiliar.js" type="text/javascript"></script>
		
		<!--Mapa-->
		<script src="http://maps.googleapis.com/maps/api/js"></script> 
		<script src="js/mapa.js" type="text/javascript"></script>
	</head>
	<body>
		<div class="container">
			<!-- NAVBAR -->
			<?php include 'navbar.php'; ?>
			
			<main>
				<!-- Uso de la función pull, igual que push pero para ascender el puesto en que se verá-->
				<!-- <aside class="col-xs-12 col-sm-4 col-sm-pull-8 col-md-6 col-md-pull-6"> -->
				<!-- El formulario se mostrará primero en pantallas pequeñas, y a partir de sm se mostrará en segundo lugar (a la derecha),
				esto se hace con la función push, y se utiliza poniendo en los tamaños que se quieran modificar: col-xx-push-(y el complementario del
				tamaño establecido). Por ejemplo, para col-sm-8 sería: col-sm-8 col-sm-push-4. La instrucción complementaria es pull (ver más abajo) -->
				<!-- <section class="col-xs-12 col-sm-8 col-sm-push-4 col-md-6 col-md-push-6"> -->
				<section>
					<div class="cabeceraTarjetas"><h1>Contacte con nosotros</h1></div>
					<div class="contacto">
						<!--Añadimos el mapa-->
						<div id="mapa"></div>
						<br>
						<h3>smartCars Madrid</h3>
						<p>Calle Princesa, 42 - 28008</p>
						<p>91 236 XX XX</p>
						<br>
						<h3>smartCars Valencia</h3>
						<p>Avenida del Cid, 138 - 46014</p>
						<p>96 566 XX XX</p>
					</div>
				</section>
			</main>
			<!-- FOOTER -->
			<?php include 'footer.php'; ?>
		</div>
	</body>
</html>