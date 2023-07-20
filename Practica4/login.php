<?php
  include("bd.php");
  require_once "/usr/local/lib/php/vendor/autoload.php";
  
  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  
  //require_once 'bdUsuarios.php';

  $bd = new BaseDatos();

  //$db = new BD();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsuario = $_POST['nick'];
    $contrasenia = $_POST['contrasenia'];
  
    if ($bd->checkLogin($nombreUsuario, $contrasenia)) {
      session_start();
      //var_dump($nombreUsuario);
      $_SESSION['nick'] = $nombreUsuario;  
      header("Location: index.php");
    }else{
      header("Location: login.php");
		}
  }
  
  echo $twig->render('login.html',  []);
  $bd->desconexionDB();
?>