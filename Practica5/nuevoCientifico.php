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

    
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$nombre = $_POST['nombre'];
		$fechas = $_POST['fechas'];
		$descripcion = $_POST['descripcion'];
		$lugarNacimiento = $_POST['lugarNacimiento'];
        $publicado = isset($_POST['publicado']) ? 1 : 0;


        if($bd->addCientifico($nombre, $fechas, $descripcion, $lugarNacimiento, $publicado)) {
            $idCientifico = $bd->ultimoCientificoRegistrado();
            header("Location:".$_SERVER['HTTP_REFERER']);	
        }
	}

	if($user['id_rol'] == 4 or $user['id_rol'] == 3){
		echo $twig->render('nuevoCientifico.html', ['usuario' => $user]);
	}else{
		echo ("No tienes permiso para añadir nuevos científicos");
	}
?>
