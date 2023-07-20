<?php
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("bd.php");
  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  session_start();
	
	$bd = new BaseDatos();
  $user = null;
  $cientificos = $bd->getCientificos();

	if(isset($_SESSION['nick'])){
		$user = $bd->getUser($_SESSION['nick']);
	}

  
  echo $twig->render('index.html', ['usuario' => $user, 'cientificos' => $cientificos]);
?>
