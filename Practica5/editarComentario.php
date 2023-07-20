<?php

	require_once "/usr/local/lib/php/vendor/autoload.php";

	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);

	include("bd.php");

	$bd = new BaseDatos();
	session_start();

	if(isset($_GET['id_comentario'])){
		$id_comentario = $_GET['id_comentario'];
	}else{
		$id_comentario = -1;
	}


	if(isset($_SESSION['nick'])){
		$user = $bd->getUser($_SESSION['nick']);

		$idCientifico = $bd->getIdCientificoByIdComentario($id_comentario);
		$comentario = $bd->getComentario ($idCientifico, $id_comentario);
		$cientifico = $bd->getCientifico($idCientifico);
		$comentarios = $bd->getComentarios($idCientifico);

		$contenidoAnterior = $comentario['contenido'];
	}	

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		if ($_POST['coment_box'] != ""){
			$contenidoNuevo = $_POST['coment_box'];
		}else{
			$contenidoNuevo = $contenidoAnterior;
		}

		$contenidoModificado = $bd->modificarComentario($id_comentario, $contenidoNuevo);
		$nombre = str_replace(' ', '', $cientifico['nombre']); // Eliminar espacios en el nombre

		$urlImagen1 = "imagenes/{$nombre}1.jpg";
		$urlImagen2 = "imagenes/{$nombre}2.jpg";

		//Si se hace el cambio se muestra de una vez
		if($contenidoModificado){
			echo $twig->render('cientifico.html', [
				'cientifico' => $bd->getCientifico($idCientifico),
				'comentarios' => $bd->getComentarios($idCientifico), 
				'usuario' => $user,
				'urlImagen1' =>  $urlImagen1,
				'urlImagen2' =>  $urlImagen2,
				'usuario' => $user,
				'idCientifico' => $cientifico['id']
			]);
			exit();
		}
	}

	echo $twig->render('editarComentario.html', ['cientifico' => $cientifico, 'comentario' => $comentario, 'usuario' => $user, 'comentarios' => $comentarios]);


?>