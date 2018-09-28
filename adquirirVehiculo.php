<?php
	//Abrimos la sesion creada
	session_start();
	//Si no existe sesión se va a index.php
	if(!isset($_SESSION["smartCars"])){
		header('Location: index.php');
	}
	else{
		$usuario = $_COOKIE["usuario"];
		$pass = $_COOKIE["pass"];
		$matricula = $_GET["matricula"];
		
		require_once "conexion.php";
		$bd = conectar();
		
		mysqli_autocommit($bd, FALSE); //Desactivamos el autocommit
		mysqli_commit($bd); //Hacemos commit, ya que en caso de rollback, deshará hasta aquí
		
		//Comparamos que el precio sea del vehiculo sea menor que el saldo de la cuenta
		$sql1 = "SELECT *
				FROM vehiculos
				WHERE matricula = '$matricula' AND
				precio <= (SELECT saldo
						FROM clientes INNER JOIN banco
							ON banco = cuenta
						WHERE dni = '$usuario');";
		//Hacemos las consultas
		$query1 = mysqli_query($bd, $sql1);

		//Si devuelve una fila, es decir si hay más saldo de lo que vale el vehiculo
		if(mysqli_num_rows($query1) == 1){
			//Añadimos en su garaje(tabla adquiere)
			//Y Actualizamos el saldo del cliente
			$sql2 = "INSERT INTO adquiere VALUES('$matricula', '$usuario');";
			$query2 = mysqli_query($bd, $sql2);
			if(!$query2){
				//Si hay fallos en el insert a la BD:
				//Sustituye cualquier caracter que no se contemple en el primer parametro, por vacios
				$falloBD = preg_replace("([^A-Za-z0-9-_@\.\s])","",mysqli_error($bd))."\n";
				echo $falloBD;
				mysqli_rollback($bd); //Si falla hacemos rollback
			}
			else{
				$sql3 = "UPDATE banco
						SET saldo = saldo - (SELECT precio FROM vehiculos WHERE matricula = '$matricula')
						WHERE cuenta = (SELECT banco FROM clientes WHERE dni = '$usuario');";
				$query3 = mysqli_query($bd, $sql3);
				if(!$query3){
					//Si hay fallos en el insert a la BD:
					//Sustituye cualquier caracter que no se contemple en el primer parametro, por vacios
					$falloBD = preg_replace("([^A-Za-z0-9-_@\.\s])","",mysqli_error($bd))."\n";
					echo $falloBD;
					mysqli_rollback($bd); //Si falla hacemos rollback
				}
				else{
					mysqli_commit($bd); //Si todo ha ido bien, confirmamos
					echo "true";
				}
			}
		}
		else
			echo "false";			

		//Desconectamos de la BD
		desconectar($bd);
	}
?>