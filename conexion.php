<?php
	function conectar(){
		$nombre_bd = "smartcars";
		$usuario = "root";
		$host = "localhost";
		$pass = "";
		$bd = @mysqli_connect($host, $usuario, $pass, $nombre_bd);
		if(!$bd){
			printf("Error %d: %s. <br>", mysqli_connect_errno(), mysqli_connect_error());
		}
		else{
			$bd->set_charset("utf8"); //Establecemos utf8 como charset de la BD
		}
		return $bd;
	}
	function desconectar($bd){
		if($bd){
			$ok = @mysqli_close($bd);
			if(!$ok){
				echo "Fallo en la desconexion. <br>";
			}
		}
		else{
			echo "Conexion no abierta. <br>";
		}
	}
?>