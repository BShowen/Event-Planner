<?php
class User{
  private $user_id;
  private $valid;
  private $first_name;
  private $last_name;
  private $email;
  
  function __construct($user_id){
    $this->verify_id($user_id);
  }

  public function __get($attr){
    return $this->$attr;
  }

  public function events($limit = -1){
    $db = (new Database())->get_handle();
    if($limit > 0){
      $query = 'SELECT title, event_date, description FROM events WHERE userid = ? LIMIT ?';
      $stmt = $db->prepare($query);
      $stmt->bind_param('ii', $this->user_id, $limit);
    }else{
      $query = 'SELECT title, event_date, description FROM events WHERE userid = ?';
      $stmt = $db->prepare($query);
      $stmt->bind_param('i', $this->user_id);
    }
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($title, $event_date, $description);
    $events = [];
    while($stmt->fetch()){
      $event = array($title, $event_date, $description);
      array_push($events, $event);
    }
    return $events;
  }

  private function verify_id($user_id){
    $db = (new Database())->get_handle();
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
      $this->user_id = intval($user_id);
      $this->get_and_set_attributes();
    }else{
      $this->valid = False;
    }
  }

  private function get_and_set_attributes(){
    $db = (new Database())->get_handle();
    $query = "SELECT first, last, email FROM users WHERE userid = ?";
    $stmt= $db->prepare($query);
    $stmt->bind_param('i', $this->user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($first, $last, $email);
    $stmt->fetch();
    $db->close();
    $this->first_name = $first;
    $this->last_name = $last;
    $this->email = $email;
  }
}
?>