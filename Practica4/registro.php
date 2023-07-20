<?php
	require_once "/usr/local/lib/php/vendor/autoload.php";
    include("bd.php");
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);

	

	$bd = new BaseDatos();

	session_start();
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$nombreUsuario = $_POST['nick'];
		$clave = $_POST['contrasenia'];
		$nombre = $_POST['nombre'];
		$apellidos = $_POST['apellidos'];
		$email = $_POST['email'];
		$telefono = $_POST['telefono'];

		if(!$bd->checkExisteUsuario($nombreUsuario)) {
			if($bd->aniadirUsuario($nombreUsuario, $clave, $nombre, $apellidos, $email, $telefono)) {

				$_SESSION = $bd->getUser($nombreUsuario);
				header("Location: /index.php");

			}else{
				header("Location: /index.php");
			}
		}
	}

	echo $twig->render('registro.html', []);


?>