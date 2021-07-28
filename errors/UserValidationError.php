<?php
class UserValidationError extends Exception {
  function __toString(){
    return $this->getMessage();
  }
}
?>