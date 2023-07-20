<?php
	include("bd.php");
	require_once "/usr/local/lib/php/vendor/autoload.php";

	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);


	session_start();
	$bd = new BaseDatos();

	if(isset($_SESSION['nick'])){
		$user = $bd->getUser($_SESSION['nick']);
	}

	if($user['id_rol'] == 2 or $user['id_rol'] == 4){
		$comentarios = $bd->getTodosComentariosBD();
		echo $twig->render('Comentarios.html', ['comentarios' => $comentarios, 'usuario' => $user]);	
	}else{
		echo("No tienes permiso para ver todos los comentarios");
	}

	
	$bd->desconexionDB();
?>