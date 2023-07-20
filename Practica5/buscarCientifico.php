<?php
    include ("bd.php"); 

    $bd = new BaseDatos();

    session_start();

    $user = null;
    $resultadoBusqueda = null;
    $tabla = null;

    if(isset($_SESSION['nick'])){
		$user = $bd->getUser($_SESSION['nick']);

        
        if (isset($_POST['cientifico'])) {

            if($user['id_rol'] == 4 or $user['id_rol'] == 3) {
                $resultadoBusqueda = $bd->buscarCientificos($_POST['cientifico']);
            }
            else{
                $resultadoBusqueda = $bd->buscarCientificosPublicados($_POST['cientifico']);
            }
        }

        if ($resultadoBusqueda->num_rows > 0)
        {
          $tabla.= 
          '<table class="table">
            <tr class="bg-primary">
            </tr>';
      
          while($cientifico= $resultadoBusqueda->fetch_assoc())
          {
            $tabla.=
            '<tr>
              <td><a href="./cientifico.php?id='.$cientifico['id'].'</td>
             </tr>
            ';
          }
      
          $tabla.='</table>';
        } 
      
          echo $tabla;
	}


    


    $bd->desconexionDB();
?>