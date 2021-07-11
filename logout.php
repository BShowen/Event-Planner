<?php
  if(isset($_COOKIE['auth'])){
    unset($_COOKIE['auth']);
    setcookie("auth", '', 0, "/");
  }
  Header("Location: http://".$_SERVER['HTTP_HOST']."/Login.php",TRUE,302);
?>