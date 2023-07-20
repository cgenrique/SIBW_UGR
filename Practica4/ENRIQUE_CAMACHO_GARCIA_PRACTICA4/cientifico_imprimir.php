<?php
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("bd.php");

  $mysqli = conexionDB();
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

  $cientifico = getCientifico($idCientifico, $mysqli);
  $nombre = str_replace(' ', '', $cientifico['nombre']); // Eliminar espacios en el nombre
  $urlImagen1 = "imagenes/{$nombre}1.jpg";
  $urlImagen2 = "imagenes/{$nombre}2.jpg";
  
  //echo $twig->render('cientifico.html', ['cientifico' => $cientifico],['comentario1' => $comentario1], ['comentario2' => $comentario2] );
  //echo $twig->render('cientifico_imprimir.html', ['cientifico' => $cientifico]);

  echo $twig->render('cientifico_imprimir.html', [
    'cientifico' => $cientifico,
    'urlImagen1' =>  $urlImagen1,
    'urlImagen2' =>  $urlImagen2,
]);
  
?>

