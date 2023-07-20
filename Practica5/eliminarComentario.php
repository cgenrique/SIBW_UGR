<?php

	require_once "/usr/local/lib/php/vendor/autoload.php";

	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);

	include("bd.php");

	$bd = new BaseDatos();
	session_start();

	if(isset($_SESSION['nick'])){
		$user = $bd->getUser($_SESSION['nick']);
	}

	if(isset($_GET['id_comentario'])){
		$id_comentario = $_GET['id_comentario'];
		$idCientifico = $bd->getIdCientificoByIdComentario($id_comentario);

		if($bd->borrarComentario($id_comentario)){
			header("Location:".$_SERVER['HTTP_REFERER']);
			exit();
		}

	}