<?php
  class FileSaveError extends Exception {
    function __toString(){
      return $this->getMessage();
    }
  }
?>
