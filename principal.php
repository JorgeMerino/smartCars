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
		$sql = "SELECT * FROM vehiculos WHERE tipo = 'Proximo';";
		$sqlCarrusel = "SELECT * FROM vehiculos ORDER BY precio DESC;";
		$sqlPermisosCliente = "SELECT permisoA, permisoB FROM clientes WHERE dni = '$usuario';";
		//Hacemos las consultas
		$query = mysqli_query($bd, $sql);
		$queryCarrusel = mysqli_query($bd, $sqlCarrusel);
		$queryPermisosCliente = mysqli_query($bd, $sqlPermisosCliente);
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
			<div id="myCarousel" class="carousel slide" data-ride="carousel">
				<!-- Indicators -->
				<ol class="carousel-indicators">
					<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
					<li data-target="#carousel-example-generic" data-slide-to="1"></li>
					<li data-target="#carousel-example-generic" data-slide-to="2"></li>
					<li data-target="#carousel-example-generic" data-slide-to="3"></li>
				</ol>
				<!--Wrapper for slides-->
				<div class="carousel-inner" role="listbox">
					<div class="item active">
						<img src="images/principal_01.png"/>
						<div class="carousel-caption  hidden-xs"> Porsche Panamera 4S </div>
					</div>
					<div class="item">
						<img src="images/principal_02.png"/>
						<div class="carousel-caption  hidden-xs"> Mercedes AMG GT </div>
					</div>
					<div class="item">
						<img src="images/principal_03.png"/>
						<div class="carousel-caption  hidden-xs"> Audi S5 Coupé </div>
					</div>
					<div class="item">
						<img src="images/principal_04.png"/>
						<div class="carousel-caption  hidden-xs"> Porsche 911 Carrera 4 </div>
					</div>						
				</div>

				<!--Controladores-->
				<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Anterior</span>
				</a>
				<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Siguiente</span>
				</a>
			</div>
			<main>
				<!-- Uso de la función pull, igual que push pero para ascender el puesto en que se verá-->
				<!-- <aside class="col-xs-12 col-sm-4 col-sm-pull-8 col-md-6 col-md-pull-6"> -->
				<!-- El formulario se mostrará primero en pantallas pequeñas, y a partir de sm se mostrará en segundo lugar (a la derecha),
				esto se hace con la función push, y se utiliza poniendo en los tamaños que se quieran modificar: col-xx-push-(y el complementario del
				tamaño establecido). Por ejemplo, para col-sm-8 sería: col-sm-8 col-sm-push-4. La instrucción complementaria es pull (ver más abajo) -->
				<!-- <section class="col-xs-12 col-sm-8 col-sm-push-4 col-md-6 col-md-push-6"> -->
				<section>		  
					<div class="cabeceraTarjetas"><h1>Próximamente</h1></div>
					<div class="row">							
						<?php
							$permisosCliente = mysqli_fetch_assoc($queryPermisosCliente);
							while($vehiculo = mysqli_fetch_assoc($query)){
								$nombre = $vehiculo["marca"]." ".$vehiculo["modelo"];
								$matricula = $vehiculo["matricula"];
								$imagen = $vehiculo["imagen"];
								$permisoVehiculo = $vehiculo["permiso"];
								//Añadimos separador de miles
								$precio = number_format($vehiculo["precio"], 0, '', '.');
								
								//Comprobación de permisos
								$permisosCompatibles = false;
								if($permisosCliente["permisoA"] == 1 && $permisoVehiculo == "A"){
									$permisosCompatibles = true;
								}
								if($permisosCliente["permisoB"] == 1 && $permisoVehiculo == "B"){
									$permisosCompatibles = true;
								}
								
								//La instruccion <a href='detalles.php?matricula=$matricula'> direcciona a la pagina detalles.php pasandole la variable matricula
								echo "<div class='col-xs-12 col-sm-6 col-md-4'>
										<div class='tarjeta'>
											<a href='detalles.php?matricula=$matricula'><img src='$imagen' class='img-responsive' title='Ver detalles'></a>
											<div>
												<p class='title'>$nombre</p>
												<div class='price'>
													 <span class='reducedfrom'>$precio €</span>
												</div>
												<div class='opciones'>
													<div class='btn-group'>
														<a href='detalles.php?matricula=$matricula' class='btn btn-default' role='button'>Detalles</a>";
														// <a href="..." ROLE="BUTTON"> Actua como un boton con un enlace
														if($vehiculo["disponible"] == 1){
															//MUY IMPORTANTE: En la funcion onClick los parametros deben tener distintas comillas que la funcion
															//Si la funcion las tiene simples, los parametros dobles, o viceversa
															echo "<button type='button' class='btn btn-success' onClick='confirmarCompra(".'"'.$matricula.'", "'.$nombre.'", "'.$permisosCompatibles.'", "'.$permisoVehiculo.'"'.")'>";
															if($vehiculo["tipo"] == "Renting"){echo "Rent";}else{echo "Comprar";}
															echo "</button>";
														}
														else
															echo "<button type='button' class='btn btn-danger disabled'>Agotado</button>";
														
													echo "</div>
												</div>
											</div>
										</div>
									</div>";
							}
						?>
					</div>
					<div class='clear'></div>					
				</section>
			</main>
			<!-- FOOTER -->
			<?php include 'footer.php'; ?>
		</div>
	</body>
</html>