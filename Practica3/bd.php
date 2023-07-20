<?php
  function conexionDB(){
    $mysqli = new mysqli("mysql", "usuario", "usuario", "SIBW");
    if ($mysqli->connect_error) {
      echo ("Fallo al conectar: " . $mysqli->connect_error);
    }

    return  $mysqli;
  }

  function getCientifico($idCientifico,  $mysqli) {
  
    /*$mysqli = new mysqli("mysql", "usuario", "usuario", "SIBW");
    if ($mysqli->connect_error) {
      echo ("Fallo al conectar: " . $mysqli->connect_error);
    }*/
    
    //$mysqli = conexionDB();

    $res = $mysqli->query("SELECT id, nombre, fechas, descripcion, lugarNacimiento FROM cientificos WHERE id=" . $idCientifico);
    
    $cientifico = array('id' => 0, 'nombre' => 'Nadie', 'fechas' => 'dd/mm/aaaa - dd/mm/aaaa', 'descripcion' => 'Vacío', 'lugarNacimiento' => 'Ningún lado');
    
    if ($res->num_rows > 0) {
      $row = $res->fetch_assoc();
      
      $cientifico = array('id' => $row['id'],'nombre' => $row['nombre'], 'fechas' => $row['fechas'], 'descripcion' => $row['descripcion'], 'lugarNacimiento' => $row['lugarNacimiento']);
    }
    
    
    return $cientifico;
  }

  function getComentario($idCientifico,$idComentario,$mysqli) {

    //$res = $mysqli->query("SELECT id_cientifico, nombreAutor, fecha, hora, contenido FROM mensajes WHERE id_cientifico=" . $idCientifico);
    $res = $mysqli->query("SELECT id_cientifico, id_comentario, nombreAutor, fecha, hora, contenido FROM mensajes WHERE id_cientifico=" . $idCientifico . " AND id_comentario=" . $idComentario);
    
    $comentario = array('id_cientifico' => 0, "id_comentario" => 0, 'nombreAutor' => 'Nadie', 'fecha' => '0-0-0', 'hora' => '00:00:00', 'contenido' => 'Nada que decir');
    
    if ($res->num_rows > 0) {
      $row = $res->fetch_assoc();
      
      $comentario = array('id_cientifico' => $row['id_cientifico'], 'id_comentario' => $row['id_comentario'], 'nombreAutor' => $row['nombreAutor'], 'fecha' => $row['fecha'], 'hora' => $row['hora'], 'contenido' => $row['contenido']);
    }
    
    
    return $comentario;
  }

?>
