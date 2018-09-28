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
		//Conectamos con la BD
		$bd = conectar();
		$matricula = $_GET["matricula"];
		//Hacemos un OUTER JOIN para unir "vehiculos" con "adquiere" y ver si está asociado a un cliente
		$sql = "SELECT *
			FROM vehiculos LEFT OUTER JOIN adquiere
			ON matricula = vehiculo
			WHERE matricula = '$matricula';";
		$sqlPermisosCliente = "SELECT permisoA, permisoB FROM clientes WHERE dni = '$usuario';";
		//Hacemos las consultas
		$query = mysqli_query($bd, $sql);
		$queryPermisosCliente = mysqli_query($bd, $sqlPermisosCliente);
		
		$vehiculo = mysqli_fetch_assoc($query);
		$nombre = $vehiculo["marca"]." ".$vehiculo["modelo"];
		$precio = number_format($vehiculo["precio"], 0, '', '.');
		$km = number_format($vehiculo["km"], 0, '', '.');

		//Desconectamos de la BD
		desconectar($bd);
		//A partir de ahora, aunque no haya conexion con la BD la consulta está guardada en $query,
		//por lo que podremos trabajar con ella sin problemas
	}
?>
<!DOCTYPE HTML>
<html lang="es">
	<head>
		<!-- HEAD -->
		<?php include 'head.php'; ?>
		
		<script src="js/menu_auxiliar.js" type="text/javascript"></script>
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
					<div class="cabeceraTarjetas"><h1><?php echo $nombre ?></h1></div>
					<div class="about_wrapper"><h1><?php echo "Precio: ".$precio." €"; if($vehiculo["tipo"]=="Renting"){echo "/mes";}?></h1></div>
					<div class="about-group">
						<div class="about-top">	
							<div class="grid images_3_of_1">
								<img src= "<?php echo $vehiculo["imagen"]?>" alt=""/>
							</div>
							<div>
								<h3> <?php echo $vehiculo["titulo"] ?> </h3>
								<p> <?php echo $vehiculo["descripcion"] ?> </p>
								<div class="clear"></div>
							</div>
						</div>
						<div class="clear"></div>
						<div class="links">
							<ul>
								<li><img src="images/anio.png" title="Año"><span><?php echo $vehiculo["anio"]?></span></li>
								<li><img src="images/color.png" title="Color"><span><?php echo $vehiculo["color"]?></span></li>
								<li><img src="images/potencia.jpg" title="Potencia"><span><?php echo $vehiculo["potencia"]?> cv</span></li>
								<li><img src="images/combustible.png" title="Combustible"><span><?php echo $vehiculo["combustible"]?></span></li>
								<li><img src="images/transmision.jpg" title="Transmisión"><span><?php echo $vehiculo["transmision"]?></span></li>
								<li><img src="images/km.png" title="Km"><span><?php echo $km?> km</span></a></li>
							</ul>
						</div>
						<?php
							$permisosCliente = mysqli_fetch_assoc($queryPermisosCliente);
							$permisoVehiculo = $vehiculo["permiso"];
							
							//Comprobación de permisos
							$permisosCompatibles = false;
							if($permisosCliente["permisoA"] == 1 && $permisoVehiculo == "A"){
								$permisosCompatibles = true;
							}
							if($permisosCliente["permisoB"] == 1 && $permisoVehiculo == "B"){
								$permisosCompatibles = true;
							}
						
							//Si está disponible mostramos el botón de "Adquirir"
							if($vehiculo["disponible"] == 1){
								//MUY IMPORTANTE: En la funcion onClick los parametros deben tener distintas comillas que la funcion
								//Si la funcion las tiene simples, los parametros dobles, o viceversa
								echo "<button type='button' class='btn btn-success' onClick='confirmarCompra(".'"'.$matricula.'", "'.$nombre.'", "'.$permisosCompatibles.'", "'.$permisoVehiculo.'"'.")'>";
								if($vehiculo["tipo"] == "Renting"){echo "Rent";}else{echo "Comprar";}
								echo "</button>";
							}
							//Si no lo está y el usuario no es el dueño, se le indicará que está "Agotado"
							else{
								if($vehiculo["cliente"] != $usuario){
									echo "<button type='button' class='btn btn-danger disabled'>Agotado</button>";
								}
							}
						?>
						<div class='clear'></div>
					</div>
				</section>
			</main>
			<!-- FOOTER -->
			<?php include 'footer.php'; ?>
		</div>
	</body>
</html>