<?php
  class BaseDatos{
    public $mysqli = null;

    public function __construct(){
			$this->mysqli = new mysqli("mysql", "usuario", "usuario", "SIBW") or die(mysqli_error());
		}

    public function conexionDB(){
      if($this->mysqli == null){
        $this->mysqli = new mysqli("mysql", "usuario", "usuario", "SIBW") or die(mysqli_error());	
      }
    }
  
    public function desconexionDB(){
      $this->mysqli = null;
    }

    public function getCientifico($idCientifico){
      $this->conexionDB();
      $infoCientifico = $this->mysqli->prepare("SELECT id, nombre, fechas, descripcion, lugarNacimiento FROM cientificos WHERE id= ?");
      $infoCientifico->bind_param("i", $idCientifico);
			$infoCientifico->execute();
			$infoCientifico = $infoCientifico->get_result();
			$cientifico = $infoCientifico->fetch_assoc();
      return $cientifico;
    }

    public function getComentario($idCientifico, $idComentario) {
      $this->conexionDB();
      $infoComentario = $this->mysqli->prepare("SELECT id_cientifico, id_comentario, nombreAutor, fecha, hora, contenido FROM comentarios WHERE id_cientifico= ? AND id_comentario= ?");
			$infoComentario->bind_param("ii", $idCientifico, $idComentario);
			$infoComentario->execute();
			$infoComentario = $infoComentario->get_result();
      $comentario = $infoComentario->fetch_assoc();
      return $comentario;
    }

	public function getComentarios($idCientifico) {
		$this->conexionDB();
		$comentarios = $this->mysqli->prepare("SELECT * FROM comentarios WHERE id_cientifico = ?");
			$comentarios->bind_param("i", $idCientifico);
			$comentarios->execute();
			$comentarios =  $comentarios->get_result();

		return $comentarios->fetch_all(MYSQLI_ASSOC);
	}

	public function addComentario($idCientifico, $autor, $contenido){
		$fecha = date('Y-m-d');
		$hora = date('H:i:s');

		$addComentario = $this->mysqli->prepare("INSERT INTO comentarios(id_cientifico, nombreAutor, fecha,hora,contenido) VALUES (?, ?, ?, ?, ?)");
		$addComentario->bind_param("issss", $idCientifico, $autor, $fecha, $hora, $contenido);
		$addComentario->execute();

		if($addComentario->affected_rows != -1){
			$result = true;
		}else{
			$result = false;
		}

		$addComentario->close();
		return $result;
	}

    	//Sacar la info de un usuario si este existe

    public function getUsuarioById($id_usuario){
      $infoUsuario = $this->mysqli->prepare("SELECT nick FROM usuarios WHERE id = ?");
      $infoUsuario->bind_param("i", $id_usuario);
  
      $infoUsuario->execute();
  
      $result = $infoUsuario->get_result();
  
      return $result;
    }

	public function getUser($nick){
		$infoUser = $this->mysqli->prepare("SELECT * FROM usuarios WHERE nick = ?");
		$infoUser->bind_param("s", $nick);
		$infoUser->execute();
		$user = -1;

		$result = $infoUser->get_result();
			
		if($result->num_rows === 1){
			$row = $result->fetch_assoc();
			$user = ['nick' => $row['nick'], 'nombre' => $row['nombre'], 'apellidos' => $row['apellidos'], 'telefono' => $row['telefono'],
					'email' => $row['email'], 'contrasenia' => $row['contrasenia'], 'id_rol' => $row['id_rol'], 'id' => $row['id']];	
		}
		$infoUser-> close();
		return $user;
	}

		public function checkLogin($nick, $contrasenia){
			$userCheck = $this->getUser($nick);
			$result = false;

				if($userCheck!=-1){
					if(password_verify($contrasenia, $userCheck['contrasenia'])){
						//var_dump($userCheck['contrasenia']); // Imprimir el hash almacenado en la base de datos
						//var_dump($contrasenia); // Imprimir la contraseña proporcionada durante el inicio de sesió
						$result = true;
					}
				}
				//var_dump($result);
			return $result;
		}



    	//Aniade un usuario a la BD. por defecto los roles sera registrado
		public function aniadirUsuario($usuario, $contrasenia, $nombre, $apellidos, $email, $telefono){

			$aniadirUsuario = $this->mysqli->prepare("INSERT INTO usuarios(nick, nombre, apellidos, telefono, email, contrasenia, id_rol) VALUES (?, ?, ?, ?, ?, ?, '1')");
			$contraseniaSuperSegura = password_hash($contrasenia, PASSWORD_DEFAULT);


			$aniadirUsuario->bind_param("ssssss", $usuario,$nombre, $apellidos,$telefono, $email,$contraseniaSuperSegura);
			//$aniadirUsuario->bind_param("ssssss", $usuario,$nombre, $apellidos,$telefono, $email,$contrasenia);

			return $aniadirUsuario->execute();

		}

    	//Checkea que exista un usuario con el nick == $nick
		public function checkExisteUsuario($nick){
			$checkUsuario = $this->mysqli->prepare("SELECT nick FROM usuarios WHERE nick = ?");
			$checkUsuario->bind_param("s", $nick);
			$checkUsuario->execute();

			return $checkUsuario->get_result()->num_rows == 1;
		}

    public function actualizarDatosUsuario($nick, $contrasenia, $telefono, $email, $id){
			//Cambiar para que acepte ingresar un nuevo nick desde uno viejoo o una contrasenia
			$updateUser = $this->mysqli->prepare("UPDATE usuarios SET nick = ?, contrasenia = ?, telefono = ?, email = ? WHERE id = ?");
			$updateUser->bind_param("sssss", $nick, $contrasenia, $telefono, $email, $id);

			return $updateUser->execute();
		}
  }
?>