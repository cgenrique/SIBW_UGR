<?php
	include("bd.php");
	require_once "/usr/local/lib/php/vendor/autoload.php";

	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);

	$bd = new BaseDatos();
	$sesion_iniciada = 0;
	$sentencia = true;

	session_start();


	if(isset($_SESSION['nick'])){
		$user = $bd->getUser($_SESSION['nick']);
		$sesion_iniciada = 1;
		$old_user = $user['nick'];
		$old_pass = $user['contrasenia'];
		$old_tel = $user['telefono'];
		$old_email = $user['email'];
	}

	if($sesion_iniciada == 1){
		$id = isset($_GET['id']) ? $_GET['id'] : null;
		if (isset($_POST['nick']) && $_POST['nick'] != ""){
			$nick = $_POST['nick'];
		}else{
			$nick = $old_user;
		}
		if (isset($_POST['contrasenia']) && $_POST['contrasenia'] != ""){
			$contrasenia = $_POST['contrasenia'];
			$contrasenia = password_hash($contrasenia, PASSWORD_DEFAULT);
		}else{
			$contrasenia = $old_pass;
		}
		if (isset($_POST['telefono']) && $_POST['telefono'] != ""){
			$telefono = $_POST['telefono'];
		}else{
			$telefono = $old_tel;
		}
		if (isset($_POST['email']) && $_POST['email'] != ""){
			$email = $_POST['email'];
		}else{
			$email = $old_email;
		}
		$sentencia = $bd->actualizarDatosUsuario($nick, $contrasenia, $telefono, $email, $id);
	}

	echo $twig->render('cambiar_perfil.html', ['usuario' => $user]);

	$bd->desconexionDB();

?>