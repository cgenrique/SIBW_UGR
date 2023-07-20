<?php
	include("bd.php");
	require_once "/usr/local/lib/php/vendor/autoload.php";

	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);

	$bd = new BaseDatos();

	session_start();

	$user = NULL;

	if(isset($_SESSION['nick'])){
		$user = $bd->getUser($_SESSION['nick']);
	}

	echo $twig->render('mi_cuenta.html', ['usuario' => $user]);
?>