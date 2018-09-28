<?php
	require_once "conexion.php";

	$usuario = $_POST["usuario"];
	$pass = $_POST["pass"];
	
	//Conectamos a la Base de Datos
	$bd = conectar();
	$sql = "SELECT C.nombre, C.dni, P.metodo, P.iteraciones, P.sal, P.pass
			FROM clientes C INNER JOIN passwords P
			ON C.dni = P.cliente
			WHERE email = '$usuario';";
			
	//Hacemos la consulta
	$query = mysqli_query($bd, $sql);
	$cliente = mysqli_fetch_assoc($query);
	//Guardamos el nombre, para la sesion
	$nombre = $cliente["nombre"];
	//A pesar de que el usuario es el email, internamente trabajaremos con el DNI, ya que
	//al guardar el email en la cookie, el simbolo "@" da problemas.
	$usuario = $cliente["dni"];
	$metodoHash = $cliente["metodo"];
	$iteracionesHash = $cliente["iteraciones"];
	$sal = $cliente["sal"];
	$passCodificada = $cliente["pass"];	
	
	if($query){
		//Si coinciden inicia sesion
		//Concatena la sal y la contraseña introducida y las pasa por una función hash,
		// comprueba que sea igual a la contraseña codificada.
		//Esta funcion hash va asociada al algoritmo PBKDF2 que hashea un número limitado
		// de veces una misma contraseña. Con el fin de dar lentitud a la autenticación
		// y ralentizar cualquier tipo de ataque de fuerza bruta.
		if(mysqli_num_rows($query) == 1 && hash_pbkdf2("$metodoHash", $pass, $sal, $iteracionesHash) == $passCodificada){
			//Iniciamos la sesion
			iniciarSesion($nombre);
			//Creamos las cookies
			crearCookies($usuario, $cliente["pass"]); //La creamos con la cookie cifrada
			//Guardamos los datos relativos a la IP
			guardarIP($bd, $usuario);
			echo "true"; //Escribe sobre la página "true"
		}
		//Si no coinciden devuelve el fallo
		else{
			echo "false"; //Escribe sobre la página "false"
		}
	}
	else{
		printf("No ha podido loguearse: %s. <br>", mysqli_error($bd));
	}
	
	
	function iniciarSesion($nombre){
		//Abrimos la sesion
		session_start();
		//Creamos la sesion
		$_SESSION["smartCars"] = "$nombre";
	}
	
	function crearCookies($usuario, $pass){
		//La caducidad 0, se interpreta como hasta que se cierre el navegador
		setcookie("usuario", "$usuario", 0);
		setcookie("pass", "$pass", 0);
	}
	
	function guardarIP($bd, $cliente){
		$ip = $_SERVER["REMOTE_ADDR"]; //Obtenemos la IP
		
		//Usamos la api de ip-api.com, para obtener los datos de la ip. Ver documentación en la página
		$data = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
		/*$pais = $data['country'];
		$ciudad = $data['city'];*/
		
		$pais = "Cambiar";
		$ciudad = "Cambiar";
		
		$fecha = date("d-m-Y");
		$hora = date("H:i:s");
		
		$sql = "UPDATE accesos
				SET ip = '$ip',
				pais = '$pais',
				ciudad = '$ciudad',
				fecha = STR_TO_DATE('".$fecha."', '%d-%m-%Y' ),
				hora = '$hora'
				WHERE cliente = '$cliente';";
		$query = mysqli_query($bd, $sql);
	}	
?>