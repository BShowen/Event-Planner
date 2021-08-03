<?php
// Get the document root. 
$document_root = $_SERVER['DOCUMENT_ROOT'];
// Require in the custom error that this class uses. 
require $document_root.'/errors/UserValidationError.php';
// Require in the Event object.
require $document_root.'/models/Event.php';
class User{
  private $user_id;
  private $valid;
  private $first_name;
  private $last_name;
  private $email;
  private $errors = [];
  private $friends = [];
  private $events = [];
  
  function __construct($user_id){
    $this->verify_id($user_id);
  }

  public function __get($attr){
    if($attr == 'friends'){
      $this->get_friends();
    }
    return $this->$attr;
  }

  public function __set($attr, $value){
    switch($attr){
      case 'first_name':
        $this->set_first_name($value);
        break;
      case 'last_name':
        $this->set_last_name($value);
        break;
      case 'email':
        $this->set_email($value);
        break;
      default:
        throw new UserValidationError("You cannot set that attribute for the User.");
    }
  }

  public function events($limit = -1){
    $db = (new Database())->get_handle();
    if($limit > 0){
      // If the limit is greater than 0, then return $limit number of events for the given user. 
      $query = 'SELECT title, event_date, description FROM events WHERE userid = ? LIMIT ?';
      $stmt = $db->prepare($query);
      $stmt->bind_param('ii', $this->user_id, $limit);
    }else{
      // Otherwise return all of the events for the given user. 
      $query = 'SELECT title, event_date, description FROM events WHERE userid = ?';
      $stmt = $db->prepare($query);
      $stmt->bind_param('i', $this->user_id);
    }

    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($title, $event_date, $description);

    // Create an array of events to be returned from this method. 
    //  The returned array is a multi dimensional array. Each sub array is an array containing event attributes. 
    while($stmt->fetch()){
      array_push($this->events, new Event($title, $event_date, $description));
    }
    $db->close();
    return $this->events;
  }

  private function set_first_name($value){
    try{
      if(strlen($value) > 20){
        throw new UserValidationError("First name must be less than 20 characters.");
      }
      $db = (new Database())->get_handle();
      $query = "UPDATE users SET first = ? WHERE userid = ?";
      $stmt = $db->prepare($query);
      $stmt ->bind_param('si', $value, $this->user_id);
      $stmt->execute();
      $stmt->store_result();
      $this->first_name = $value;
    }catch(UserValidationError $e){
      $this->valid = false;
      array_push($this->errors, $e);
    }
  }

  private function set_last_name($value){
    try{
      if(strlen($value) > 20){
        throw new UserValidationError("Last name must be less than 20 characters.");
      }
      $db = (new Database())->get_handle();
      $query = "UPDATE users SET last = ? WHERE userid = ?";
      $stmt = $db->prepare($query);
      $stmt ->bind_param('si', $value, $this->user_id);
      $stmt->execute();
      $stmt->store_result();
      $this->last_name = $value;
    } catch(UserValidationError $e){
      $this->valid = false;
      array_push($this->errors, $e);
    }
  }

  private function set_email($value){
    try{
      if(strlen($value) > 40){
        throw new UserValidationError("Email must be less than 40 characters.");
      }
      $db = (new Database())->get_handle();

      // Query the database to determine if the email is already in use. 
      $query = "SELECT EXISTS (SELECT * FROM users WHERE email = ?)";
      $stmt = $db->prepare($query);
      $stmt->bind_param('s', $value);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($exists);
      $stmt->fetch();
      if($exists){
        throw new UserValidationError("That email already exists. Please try another email.");
      }

      // Set the email. 
      $query = "UPDATE users SET email = ? WHERE userid = ?";
      $stmt = $db->prepare($query);
      $stmt ->bind_param('si', $value, $this->user_id);
      $stmt->execute();
      $stmt->store_result();
      $this->email = $value;
    }catch(UserValidationError $e){
      $this->valid = false;
      array_push($this->errors, $e);
    }
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

  private function get_friends(){
    try{
      $db = (new Database)->get_handle();
      $query = (
        "SELECT userid0 FROM users_friends WHERE userid1 = ?
        UNION 
        SELECT userid1 FROM users_friends WHERE userid0 = ?"
      );
      $stmt = $db->prepare($query);
      $stmt->bind_param('ii', $this->user_id, $this->user_id);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($friend_user_id);
      while($stmt->fetch()){
        array_push($this->friends, new User($friend_user_id));
      }
      $db->close();
    }catch(Exception $e){
      echo $e->getMessage();
    }
  }
}
?>