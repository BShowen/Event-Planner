<?php

// Get the document root.
// $document_root = $_SERVER['DOCUMENT_ROOT'];
// Require in the script for database connectivity. 
// require $document_root."/Database.php";
$db = new Database();
$db = $db->get_handle();

class User{
  private $user_id;
  private $valid;
  private $first_name;
  private $last_name;
  
  function __construct($user_id){
    $this->verify_id($user_id);
  }

  public function __get($attr){
    return $this->$attr;
  }

  private function verify_id($user_id){
    $db = new Database();
    $db = $db->get_handle();
    $query = "SELECT userid FROM users WHERE userid = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($retrieved_user_id);
    $stmt->fetch();
    $db->close();
    if($stmt->num_rows > 0 && $user_id == $retrieved_user_id){
      // this is a valid user
      $this->valid = True;
      $this->user_id = $user_id;
      $this->get_and_set_attributes();
    }else{
      $this->valid = False;
    }
  }

  private function get_and_set_attributes(){
    $db = new Database();
    $db = $db->get_handle();
    $query = "SELECT first, last FROM users WHERE userid = ?";
    $stmt= $db->prepare($query);
    $stmt->bind_param('i', $this->user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($first, $last);
    $stmt->fetch();
    $db->close();
    $this->first_name = $first;
    $this->last_name = $last;
  }
}
?>