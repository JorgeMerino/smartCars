<?php
	//----FUNCIÓN PRINCIPAL----
	require_once "conexion.php";
	$valido = array();
	$sal;
	$banco;
	$metodoHash = "sha512";
	//Las iteraciones que haremos en el hash seran Diez Mil
	$iteracionesHash = 10000;
	$cliente = array("nombre" => "",
					 "apellido" => "",
					 "dni" => "",
					 "fechaNacimiento" => "",
					 "permisoA" => "",
					 "permisoB" => "",
					 "email" => "",
					 "pass" => "",
					 "pass2" => "");	
	
	if(validarCampos($cliente, $valido)){				
		$bd = conectar();
		//Creamos la sal
		crearSal($sal);
		//La pasamos por una función hash
		$sal = hash("$metodoHash", $sal);
		//Concatenamos la sal hasheada con la contraseña introducida y
		// lo volvemos a pasar por una función hash, obteniendo la contraseña resultante.
		//Esta vez hasheamos con el algoritmo PBKDF2, el cual hashea un numero limitado
		// de veces una misma contraseña. Con el fin de dar lentitud a la autenticación
		// y ralentizar cualquier tipo de ataque de fuerza bruta.
		$pass = hash_pbkdf2("$metodoHash", $cliente["pass"], $sal, $iteracionesHash);
		crearCuentaBancaria($banco);
		
		mysqli_autocommit($bd, FALSE); //Desactivamos el autocommit
		mysqli_commit($bd); //Hacemos commit, ya que en caso de rollback, deshará hasta aquí
		
		$sql1 = "INSERT INTO clientes VALUES ('".$cliente["nombre"]."',
											 '".$cliente["apellido"]."',
											 '".$cliente["dni"]."',
											 STR_TO_DATE('".$cliente["fechaNacimiento"]."', '%d-%m-%Y' ),
											 '".$cliente["permisoA"]."',
											 '".$cliente["permisoB"]."',
											 '".$cliente["email"]."',
											 '".$banco."');";
		$query1 = mysqli_query($bd, $sql1);
		if(!$query1){
			//Si hay fallos en el insert a la BD:
			//Sustituye cualquier caracter que no se contemple en el primer parametro, por vacios
			$falloBD = preg_replace("([^A-Za-z0-9-_@\.\s])","",mysqli_error($bd))."\n";
			echo $falloBD;
			mysqli_rollback($bd); //Si falla hacemos rollback (en este caso no haría falta, ya que al ser la primera no se insertaría y terminaría)
		}
		else{
			$sql2 = "INSERT INTO banco VALUES ('$banco', 50000 + RAND()* (150000-50000));";
			$query2 = mysqli_query($bd, $sql2);
			if(!$query2){
				//Si hay fallos en el insert a la BD:
				//Sustituye cualquier caracter que no se contemple en el primer parametro, por vacios
				$falloBD = preg_replace("([^A-Za-z0-9-_@\.\s])","",mysqli_error($bd))."\n";
				echo $falloBD;
				mysqli_rollback($bd); //Si falla hacemos rollback (desharía la inserción en clientes)
			}
			else{
				$sql3 = "INSERT INTO passwords VALUES ('".$cliente["dni"]."', '$metodoHash', $iteracionesHash, '$sal', '$pass');";
				$query3 = mysqli_query($bd, $sql3);
				if(!$query3){
					//Si hay fallos en el insert a la BD:
					//Sustituye cualquier caracter que no se contemple en el primer parametro, por vacios
					$falloBD = preg_replace("([^A-Za-z0-9-_@\.\s])","",mysqli_error($bd))."\n";
					echo $falloBD;
					mysqli_rollback($bd); //Si falla hacemos rollback (desharía las inserciones en cliente y banco)
				}
				else{
					$sql4 = "INSERT INTO accesos (cliente) VALUES ('".$cliente["dni"]."');";
					$query4 = mysqli_query($bd, $sql4);
					if(!$query4){
						//Si hay fallos en el insert a la BD:
						//Sustituye cualquier caracter que no se contemple en el primer parametro, por vacios
						$falloBD = preg_replace("([^A-Za-z0-9-_@\.\s])","",mysqli_error($bd))."\n";
						echo $falloBD;
						mysqli_rollback($bd); //Si falla hacemos rollback (desharía las inserciones en cliente, banco y contraseña)	
					}
					else{
						mysqli_commit($bd); //Si todo ha ido bien, confirmamos
					}
				}
			}
		}
		desconectar($bd);
	}
	
	//----FUNCIOINES SECUNDARIAS----
	function validarCampos(&$cliente, &$valido){
		$MAX_ELEM = 7;
		$i=0;				
		
		$valido[0] = validarNombre($cliente["nombre"]);
		$valido[1] = validarApellido($cliente["apellido"]);
		$valido[2] = validarDni($cliente["dni"]);
		$valido[3] = validarFechaNacimiento($cliente["fechaNacimiento"]);
		$valido[4] = validarPermisos($cliente["permisoA"], $cliente["permisoB"]);
		$valido[5] = validarEmail($cliente["email"]);
		$valido[6] = validarPasses($cliente["pass"], $cliente["pass2"]);
		
		//Comprueba que todos sean validos
		while($i<$MAX_ELEM && $valido[$i])
			$i++;

		//Si $i==numero de elementos del array, todos los datos son correctos
		if($i==$MAX_ELEM)
			return true;
		else
			return false;
	}
	
	function validarNombre(&$nombre){
		$valido = true;
		$nombre = ucwords(strtolower(trim($_POST["nombreUsuario"]))); //Con trim, se quitan los espacios del principio y del final
		//ucwords() convierte a mayúscula la primera letra de cada palabra y strtolower() a minuscula toda la palabra
		
		//Nombre: Patrón -> 1 o más letras ([A-Z]+)
		//		  Modificador: i -> no diferencia mayusc. de minusc.
		if(!preg_match("/^[a-z áéíóúüñÁÉÍÓÚÑ \s]{2,30}$/i", $nombre)){
			$valido = false;
			echo "El nombre tiene que tener entre 2 y 30 carateres alfabéticos.\n";
		}

		return $valido;
	}
	
	function validarApellido(&$apellido){
		$valido = true;
		$apellido = ucwords(strtolower(trim($_POST["apellidoUsuario"])));
		//Apellidos: Patrón -> 1 o más letras ([A-Z]+)
		//		  Modificador: i -> no diferencia mayusc. de minusc.
		if(!preg_match("/^[a-z áéíóúüñÁÉÍÓÚÑ \s]{2,30}$/i", $apellido)){
			$valido = false;
			echo "El apellido tiene que tener entre 2 y 30 carateres alfabéticos.\n";
		}
	
		return $valido;
	}
	
	function validarDni(&$dni){
		$valido = true;
		$dni = strtoupper(trim($_POST["dniUsuario"])); // strtoupper() convierte a mayúsculas en PHP
		//DNI: Patrón -> 8 dígitos(\d), 1 letra
		//     Modificador: i -> no diferencia mayusc. de minusc.
		if(!preg_match("/^\d{8}[A-Z]$/i", $dni)){
			$valido = false;
			echo "El DNI introducido no es válido.\n";
		}
	
		return $valido;
	}
	
	function validarFechaNacimiento(&$fechaNacimiento){
		$valido = true;
		$fechaNacimiento = trim($_POST["fechaNacimientoUsuario"]);
		$edad;
		//Fecha: Patrón -> 1 ó 2 dígitos "/" 1 ó 2 dígitos "/" "19" ó "20" y 2 dígitos
		//		 Modificador: g(por defecto) -> diferencia entre mayusc. y minusc.
		if(!preg_match("/^\d{1,2}\/\d{1,2}\/(19|20)\d{2}$/i", $fechaNacimiento)){ //El checkdate() da error cuando el formato y/o los caracteres son incorrectos, por eso se hace esta comprobacion inicial
			$valido = false;
			echo "El formato de la fecha de nacimiento no es correcto. Formato de fecha: dd/mm/aaaa.\n";
		}
		else{
			$arrayFecha = explode("/", $fechaNacimiento); //Guarda el string fecha en un array de 3, cada vez que encuentra "/"
			//El formato para checkdate es MM/DD/AAAA 
			if(!checkdate($arrayFecha[1], $arrayFecha[0], $arrayFecha[2])){
				$valido = false;
				echo "La fecha introducida no es correcta.\n";
			}
			else{
				//Una vez hecho el checkdate ponemos el array en orden correcto, ya que en la insercion a la BD usaremos el formato '%d-%m-%Y'
				$fechaNacimiento = $arrayFecha[0]."-".$arrayFecha[1]."-".$arrayFecha[2];
				$edad = calcularEdad($arrayFecha);
				if($edad < 18){
					$valido = false;
					echo "Debe tener 18 años para poder registrarse.\n";
				}					
			}
		}				
		return $valido;
	}
	
	function validarPermisos(&$permisoA, &$permisoB){
		$valido = true;
		if($_POST["permisoA"]){
			$permisoA = true;
		}
		else{
			$permisoA = false;
		}
		if($_POST["permisoB"]){
			$permisoB = true;
		}
		else{
			$permisoB = false;
		}
		
		if(!$permisoA && !$permisoB){
			$valido = false;
			echo "Seleccione al menos un permiso de conducir.\n";
		}
		
		return $valido;
	}
	
	function validarEmail(&$email){
		$valido = true;
		$email = strtolower(trim($_POST["emailUsuario"])); //strtolower() convierte a minúsculas en PHP
		//OJO: a+ = a{1,} || a* = a{0,} 
		//E-Mail: Patrón -> {1,} letra, {0,}cualquier caracter, @, {1,}letra, "."{2,4}letras
		//          Modificador: i -> no diferencia mayusc. de minusc.
		if(strlen($email)>30){
			$valido = false;
			echo "El email introducido es demasiado largo.\n";
		}
		
		if(!preg_match("/^[a-z]+[\._a-z0-9-]*@[a-z]+(\.[a-z]{2,4})$/i", $email)){
			$valido = false;
			echo "El email introducido no es correcto. Formato de email: xxx@yyy.zz(z).\n";
		}
		
		return $valido;
	}
	
	function validarPasses(&$pass, &$pass2){
		$valido = true;
		$pass = trim($_POST["pass1Usuario"]);
		$pass2 = trim($_POST["pass2Usuario"]);
		//Contraseña: Patrón -> De 6 a 15 caracteres alfanumericos
		//          Modificador: g -> diferencia entre mayusc. y minusc.
		if(!preg_match("/^[a-zA-Z0-9]{6,15}$/", $pass)){
			$valido = false;
			echo "La contraseña debe tener entre 6 y 15 caracteres alfanuméricos.\n";
		}
		
		else{
			if($pass != $pass2){
				$valido = false;
				echo "Las contraseñas no coinciden.\n";
			}
		}				
		return $valido;
	}
	
	function calcularEdad($arrayFecha){ //$arrayFecha: 0->dia 1->mes 2->año
		$hoy = getdate(); //Cogemos la fecha (y hora) actual
		$edad = $hoy["year"] - $arrayFecha[2];
		
		if($hoy["mon"] == $arrayFecha[1]){
			if($hoy["mday"] < $arrayFecha[0])
				$edad--;					
		}
		else if($hoy["mon"] < $arrayFecha[1])
			$edad--;
		
		return $edad;
	}
	
	function crearSal(&$sal){
		for($i=0; $i<128; $i++){
			$sal = $sal.letraAleatoria();
		}
	}
	
	function crearCuentaBancaria(&$banco){
		$i=0;
		for($i=0; $i<4; $i++){
			$banco = $banco.letraAleatoria();
		}
		$banco = $banco."-";
		for($i=0; $i<10; $i++){
			$banco = $banco.numeroAleatorio();			
		}		
	}
	
	function letraAleatoria(){
		return chr(rand(ord("A"), ord("Z")));
	}
	
	function numeroAleatorio(){
		return rand(0, 9);
	}
?>