<?php
class SignUpError extends Exception {
  function __toString(){
    return $this->getMessage();
  }
}
?>