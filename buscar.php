<?php
	//Abrimos la sesion creada
	session_start();
	//Si no existe sesión se va a logout.php
	if(!isset($_SESSION["smartCars"]) || !isset($_COOKIE["usuario"]) || !isset($_COOKIE["pass"])){
		header('Location: logout.php');
	}
	else{
		$usuario = $_COOKIE["usuario"];
		//Siempre muestra un resultado, ya que en el input txt-buscar hay definido un required
		$palabrasClave = trim($_GET["txt-buscar"]);
		
		require_once "conexion.php";
		//Conectamos con la BD
		$bd = conectar();
		//Concatenamos la marca y el modelo con un espacio en medio y comprobamos que contenga la palabra clave
		$sql = "SELECT * FROM vehiculos WHERE CONCAT(marca, ' ', modelo) LIKE '%$palabrasClave%';";
		
		$sqlPermisosCliente = "SELECT permisoA, permisoB FROM clientes WHERE dni = '$usuario';";
		//Hacemos las consultas
		$query = mysqli_query($bd, $sql);
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
			
			<main>
				<!-- Uso de la función pull, igual que push pero para ascender el puesto en que se verá-->
				<!-- <aside class="col-xs-12 col-sm-4 col-sm-pull-8 col-md-6 col-md-pull-6"> -->
				<!-- El formulario se mostrará primero en pantallas pequeñas, y a partir de sm se mostrará en segundo lugar (a la derecha),
				esto se hace con la función push, y se utiliza poniendo en los tamaños que se quieran modificar: col-xx-push-(y el complementario del
				tamaño establecido). Por ejemplo, para col-sm-8 sería: col-sm-8 col-sm-push-4. La instrucción complementaria es pull (ver más abajo) -->
				<!-- <section class="col-xs-12 col-sm-8 col-sm-push-4 col-md-6 col-md-push-6"> -->
				<section>		  
					<div class="cabeceraTarjetas">
						<h1>Mi búsqueda: <?php echo '"'.$palabrasClave.'" - '.mysqli_num_rows($query)." resultado"; if(mysqli_num_rows($query)!=1){echo "s";}?></h1>
					</div>							
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