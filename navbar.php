<?php 
	//Conectamos con la BD
	$bd = conectar();
	
	$sqlGaraje = "SELECT * FROM adquiere WHERE cliente = '$usuario';";
	$sqlBanco = "SELECT saldo
				FROM clientes INNER JOIN banco
					ON banco = cuenta
				WHERE dni = '$usuario';";
	$queryGaraje = mysqli_query($bd, $sqlGaraje);
	$queryBanco = mysqli_query($bd, $sqlBanco);
	
	$saldo = number_format(mysqli_fetch_assoc($queryBanco)['saldo'], 0, '', '.');
	
	//Desconectamos de la BD
	desconectar($bd);
?>	

<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="principal.php"><img alt="smartCars" src="images/logo.png"/></a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Categorías <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="principal.php">Inicio</a></li>
					<li><a href="renting.php">Renting</a></li>
					<li><a href="compra.php">Compra</a></li>
				</ul>
				</li>
			</ul>
			<form class="navbar-form navbar-left busqueda" action="buscar.php">
				<div class="input-group">
					<input type="search" class="form-control" name='txt-buscar' id='txt-buscar' placeholder='Introduzca su búsqueda' required/>
					<div class="input-group-btn">
						<button type="submit" class="btn btn-primary">
							<span class="glyphicon glyphicon-search"></span>
						</button>
					</div>
				</div>
			</form>
			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					<span class="hidden-sm"><?php echo $_SESSION["smartCars"];?><span class="caret"></span></span>
					<span class="glyphicon glyphicon-user visible-sm" aria-hidden="true"><span class="caret"></span></span>
					
				</a>
				<ul class="dropdown-menu">
					<li><a href="garaje.php">Garaje</a></li>
					<li><a href="#" data-toggle="modal" data-target="#modalBanco">Banco</a>
				</ul>
				</li>
				<li><a href="contacto.php">
					<span class="hidden-sm">Contacto</span><span class="glyphicon glyphicon-map-marker visible-sm" aria-hidden="true"></span>
				</a></li>
				<li><a href="logout.php">
					<span class="hidden-sm">Desconectar</span><span class="glyphicon glyphicon-off visible-sm" aria-hidden="true"></span>
				</a></li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>

<!-- Modal -->
<div class="modal fade" id="modalBanco" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Banco</h4>
			</div>
			<div class="modal-body">
				Saldo en la cuenta: <?php echo $saldo; ?> €
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>