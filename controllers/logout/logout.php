<?php
  if(isset($_COOKIE['auth'])){
    unset($_COOKIE['auth']);
    setcookie("auth", '', 0, "/");
    session_reset();
  }
  Header("Location: http://".$_SERVER['HTTP_HOST']."/controllers/login/login.php",TRUE,302);
?>