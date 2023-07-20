<?php
	require_once "/usr/local/lib/php/vendor/autoload.php";

	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);
	require_once 'bd.php';

	session_start();

	$bd = new BaseDatos();

	//Obtener los datos del usuario
	if(isset($_SESSION['nick'])){
		$usuario = $bd->getUser($_SESSION['nick']);
	}

    //Obtener el id de un producto
	if (isset($_POST['idCientifico'])){
		$idCientifico = $_POST['idCientifico'];
        //var_dump($idCientifico);
	}

	//Obtener los atributos para aniadir un comentario
	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		$contenido = $_POST['contenido'];

		$bd->addComentario($idCientifico, $usuario['nick'], $contenido);
	}

	header("Location: " .$_SERVER['HTTP_REFERER']);
	exit();

?>
	