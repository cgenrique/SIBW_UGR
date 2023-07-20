<?php
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("bd.php");

  $bd = new BaseDatos();
  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  if (isset($_GET['id'])) {
    $idCientifico = $_GET['id'];
  } else {
    $idCientifico = -1;
  }

  if (!is_numeric($idCientifico)) {
    // Si el valor de la variable no es numérico, se redirige a una página de error
    header("Location: error.php");
    exit();
  }

  $cientifico = $bd->getCientifico($idCientifico);
  $comentarios = $bd->getComentarios($idCientifico);
  //$comentario2 = $bd->getComentario($idCientifico, 2);
  

  $nombre = str_replace(' ', '', $cientifico['nombre']); // Eliminar espacios en el nombre
  $urlImagen1 = "imagenes/{$nombre}1.jpg";
  $urlImagen2 = "imagenes/{$nombre}2.jpg";

  $user = null;

  session_start();

	if(isset($_SESSION['nick'])){
		$user = $bd->getUser($_SESSION['nick']);
	}
  
  //echo $twig->render('cientifico.html', ['cientifico' => $cientifico],['comentario1' => $comentario1], ['comentario2' => $comentario2] );

  echo $twig->render('cientifico.html', [
    'cientifico' => $cientifico,
    'comentarios' => $comentarios,
    //'comentario2' => $comentario2,
    'urlImagen1' =>  $urlImagen1,
    'urlImagen2' =>  $urlImagen2,
    'usuario' => $user,
    'idCientifico' => $cientifico['id']
]);
?>
