<?php
class EventError extends Exception {
  function __toString(){
    return $this->getMessage();
  }
}
?>