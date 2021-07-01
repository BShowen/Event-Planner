<?php
// Get the document root. 
$document_root = $_SERVER['DOCUMENT_ROOT'];

require $document_root.'/errors/EventError.php';
class Event {

  private $title;
  private $date;
  private $description;
  private $errors = [];
  
  public function __construct($new_title, $new_date, $new_description){
    $this->set_title($new_title);
    $this->set_date($new_date);
    $this->set_description($new_description);
  }

  public function __get($attr){
    return $this->$attr;
  }

  public function __toString(){
    return "$this->title\t$this->date\t$this->description\n";
  }

  public function valid(){
    return count($this->errors) == 0;
  }

  function get_errors(){  
    $string_of_errors = '';
    for($i = 0; $i < count($this->errors); $i++){
      $string_of_errors .= $this->errors[$i];
    }
    return $string_of_errors;
  }

  private function set_title($new_title){
    if(strlen(trim($new_title)) == 0){
      array_push($this->errors, new EventError("Title cannot be blank"));
    }else{
      $this->title = $new_title;
    }
  }

  private function set_date($new_date){
    if(strlen(trim($new_date)) == 0){
      array_push($this->errors, new EventError("Date cannot be blank"));
    }else{
      $this->date = $new_date;
    }
  }

  private function set_description($new_description){
    if(strlen(trim($new_description)) == 0){
      array_push($this->errors, new EventError("Description cannot be blank"));
    }else{
      $this->description = $new_description;
    }
  }
}
?>