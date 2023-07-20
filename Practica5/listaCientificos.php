<?php
    require_once "/usr/local/lib/php/vendor/autoload.php";
    include("bd.php");

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $bd = new BaseDatos();

    session_start();

    if(isset($_SESSION['nick'])){
		$user = $bd->getUser($_SESSION['nick']);
	}

    $cientificos = $bd->getCientificos();

    echo $twig->render('listaCientificos.html', ['usuario' => $user, 'cientificos' => $cientificos]);

    $db->desconexionDB();
?>