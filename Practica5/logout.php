<?php
  session_start();
      
  session_destroy();
  
  header("Location: /paginaLogout.php");
    
  //exit();
?>
