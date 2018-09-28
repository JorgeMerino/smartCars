<?php
	//En index todavía no hay cookies ni sesion creadas, por lo que no habrá que comprobarlas
	require_once "conexion.php";
	//Conectamos con la BD
	$bd = conectar();
	$sqlCoches = "SELECT * FROM vehiculos ORDER BY precio DESC;";
	//Hacemos la consulta
	$queryCoches = mysqli_query($bd, $sqlCoches);
	//Desconectamos de la BD
	desconectar($bd);
	//A partir de ahora, aunque no haya conexion con la BD la consulta está guardada en $query,
	//por lo que podremos trabajar con ella sin problemas
?>
<!DOCTYPE HTML>
<html lang="es">
	<head>
		<!-- HEAD -->
		<?php include 'head.php'; ?>
		
		<script type="text/javascript">  
			$(document).ready(function(){
				//Al hacer click en el botón login:
				$("#login").click(function(){  
					//Guarda en las variables usuario y contraseña la información del formulario (id)
					usuario=$("#usuarioLogin").val();  
					pass=$("#passLogin").val();  
					$.ajax({  
						type: "POST", 
						url: "login.php",  //Llama al login (en Ajax se ejecuta en segundo plano)
						data: 	"usuario=" + usuario + //Aquí ponemos los nombres de las varialbes como se llaman en login.php
								"&pass=" + pass,  //Le pasa a login.php las variables necesarias(usuario y contraseña)
						success: function(html){  //Lee el html de login.php
							if(html == "true"){
								window.location = "principal.php";
							}  
							else if(html == "false"){  
								alert("Usuario o contraseña incorrectos.");  
							}
							else{
								alert("ERROR: " + html);
							}
						},  
						beforeSend: function(){  
							$("#add_err").html("Cargando...");
						}  
					});  
					return false;  
				}); 
				
				$("#registro").click(function(){  
					//Guarda en las variables del formulario de registro (id)
					nombre=$("#nombreUsuario").val();
					apellido=$("#apellidoUsuario").val();
					dni=$("#dniUsuario").val();
					fechaNacimiento=$("#fechaNacimientoUsuario").val();
					if($("#permisoA").is(":checked")) permisoA = 1; else permisoA = 0;
					if($("#permisoB").is(":checked")) permisoB = 1; else permisoB = 0;
					email=$("#emailUsuario").val();
					pass=$("#pass1Usuario").val();
					pass2=$("#pass2Usuario").val();
					
					$.ajax({  
						type: "POST", 
						url: "enviarRegistro.php",  //Llama al login (en Ajax se ejecuta en segundo plano)
						data: 	"nombreUsuario=" + nombre + //Aquí ponemos los nombres de las varialbes como se llaman en enviarRegistro.php
								"&apellidoUsuario=" + apellido +
								"&dniUsuario=" + dni +
								"&fechaNacimientoUsuario=" + fechaNacimiento +
								"&permisoA=" + permisoA +
								"&permisoB=" + permisoB +
								"&emailUsuario=" + email +
								"&pass1Usuario=" + pass +
								"&pass2Usuario=" + pass2,  //Le pasa a login.php las variables necesarias(usuario y contraseña)
						success: function(html){  //Lee el html de enviarRegistro.php
							if(html == ""){
								alert("¡Enhorabuena, ha completado su registro! Su usuario es: " + email);
								$('#datos')[0].reset(); //Resetea el formulario "datos"
								$("#crearCuenta").modal("hide"); //Oculta el formulario de Registro
								$("#iniciarSesion").modal("show"); //Muestra el formulario de Iniciar Sesión
								window.scroll(0, 0); //Se va arriba de la página
								$("#usuarioLogin").val(email); //Mete el email en el campo usuario del login
								$("#passLogin").focus(); //Pone el foco en la contraseña
							}
							else{
								alert("ATENCIÓN\n\n" + html);
							}
						},  
						beforeSend: function(){  
							$("#add_err").html("Cargando...");
						}  
					});  
					return false;  
				}); 
			});  
		</script> 
	</head>
	<body class="indice">
		<header class="row">
			<div class="logo col-xs-12 col-sm-6">
				<a href="principal.php"><img src="images/logo_index.png" class="img-responsive"alt="Logo"/></a>
			</div>
			<div class="linkSesion col-xs-12 col-sm-6">
				<a data-toggle="modal" data-target="#iniciarSesion"> Entrar </a>
				<a data-toggle="modal" data-target="#crearCuenta"> Registro </a>
				<!-- Modal Iniciar Sesión-->
				<div class="modal fade" id="iniciarSesion" tabindex="-1" role="dialog" aria-labelledby="iniciarSesionLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="iniciarSesionLabel">Iniciar Sesión</h4>
							</div>
							<div class="modal-body">
								<form id="formIniciarSesion">
									<div class="form-group">
										<label for="usuarioLogin" class="control-label">Usuario</label>
										<input type="email" class="form-control" name="usuarioLogin" id="usuarioLogin" required/>
									</div>
									<div class="form-group">
										<label for="passLogin" class="control-label">Contraseña</label>
										<input type="password" class="form-control" name="passLogin" id="passLogin" required/>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
								<button type="submit" class="btn btn-primary" form="formIniciarSesion" id ="login">Entrar</button>
							</div>
						</div>
					</div>
				</div>
				<!-- Modal Registro-->
				<div class="modal fade" id="crearCuenta" tabindex="-1" role="dialog" aria-labelledby="registroLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="registroLabel">Registro</h4>
							</div>
							<div class="modal-body">
								<form id="datos">
									<div class="form-group">
										<label for="nombreUsuario">Nombre</label>
										<div class="row">
											<div class="col-xs-12 col-sm-6">
												<input type="text" class="form-control" name="nombreUsuario" id="nombreUsuario" placeholder="Nombre" required/>
											</div>
											<div class="col-xs-12 col-sm-6">
												<input type="text" class="form-control" name="apellidoUsuario" id="apellidoUsuario" placeholder="Apellidos" required/>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="dniUsuario">DNI</label>
										<input type="text" class="form-control" name="dniUsuario" id="dniUsuario" required/>
									</div>
									<div class="form-group">
										<label for="fechaNacimientoUsuario">Fecha de nacimiento</label>
										<input type="text" class="form-control" name="fechaNacimientoUsuario" id="fechaNacimientoUsuario" placeholder="DD/MM/AAAA" required/>
									</div>
									<div class="form-group">
										<label>¿De qué permiso(s) de conducir dispone?</label><br>
										<div class="material-switch">
											<input type="checkbox" id="permisoA" name="permisoA"/>
											<label for="permisoA" class="interruptor label-primary"></label>
											<label for="permisoA">Permiso A</label>
										</div>
										<div class="material-switch">
											<input type="checkbox" id="permisoB" name="permisoB"/>
											<label for="permisoB" class="interruptor label-primary"></label>
											<label for="permisoB">Permiso B</label>
										</div>
									</div>
									<div class="form-group">
										<label for="emailUsuario">E-mail</label>
										<input type="email" class="form-control" name="emailUsuario" id="emailUsuario" required/>
									</div>					
									<div class="form-group">
										<label for="pass1Usuario">Contraseña</label>
										<input type="password" class="form-control" name="pass1Usuario" id="pass1Usuario" required/>
									</div>
									<div class="form-group">
										<label for="pass2Usuario">Confirmar contraseña</label>
										<input type="password" class="form-control" name="pass2Usuario" id="pass2Usuario" required/>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
								<button type="submit" class="btn btn-primary" form="datos" id="registro">Registrar</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>
		<div>
			<img src="images/index_01.jpg"/>
			<div class="descripcion">
				<p> ~ Somos una empresa dedicada a la venta y renting de automóviles de alta gama, con más de 30 años de experiencia en el sector. 
				Llevamos desde 1985 ofreciendo el mejor de los servicios y el mayor de los compromisos a nuestros clientes ~ 
				</p>
			</div>
			
			<img src="images/index_02.jpg"/>
			<div class="descripcion">
				<p> ~ Nuestra experiencia, junto a una atención personalizada a nuestros clientes, nos convierte actualmente 
				en especialistas en el mercado de vehículos de alta gama, deportivos y para coleccionistas ~ 
				</p>
			</div>
			<img src="images/index_03.jpg"/>
			<div class="descripcion">
				<p> ~ <a data-toggle="modal" data-target="#iniciarSesion"> Entra </a> o
				<a data-toggle="modal" data-target="#crearCuenta"> Regístrate </a> ahora en smartCars y descubre nuestra gama de automóviles, para nuestros clientes más exigentes ~ 
				</p>
			</div>			
		</div>
		<!-- FOOTER -->
		<?php include 'footer.php'; ?>
	</body>
</html>