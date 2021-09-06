<?php
// Get the document root. 
$document_root = $_SERVER['DOCUMENT_ROOT'].'/eventPlanner';

require_once $document_root.'/errors/EventError.php';

class Event {

  private $title;
  private $date;
  private $description;
  private $userid;
  private $errors = [];
  
  public function __construct($new_title, $new_date, $new_description, $userid = null){
    $this->set_title($new_title);
    $this->set_date($new_date);
    $this->set_description($new_description);
    $this->userid = $userid;
  }

  public function __get($attr){
    if($attr == 'date'){
      return $this->formatted_date();
    }else{
      return $this->$attr;
    }
  }

  public function __toString(){
    return "$this->title\t$this->date\t$this->description\n";
  }

  public function valid(){
    return count($this->errors) == 0;
  }

  public function get_errors(){  
    $string_of_errors = '';
    for($i = 0; $i < count($this->errors); $i++){
      $string_of_errors .= $this->errors[$i];
    }
    return $string_of_errors;
  }

  // This function saves an Event to the database and returns true or false depending on if the save is successful or not. 
  public function save_event(){
    $db = new Database();
    // Save event to the database.
    $query = "INSERT INTO events (userid, title, description, event_date) VALUES(?,?,?,?)";
    $stmt = ($db->get_handle())->prepare($query);
    $stmt->bind_param('isss', $this->userid, $this->title, $this->description, $this->date);
    $stmt->execute();
    ($db->get_handle())->close();
    return $stmt->affected_rows > 0;
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

  // This function takes in a date that is formatted yyyy-mm-dd by MySQL and returns a date formatted
  // as mm-dd-yyyy. 
  private function formatted_date(){
    $date_array = explode('-', $this->date);
    $year = intval($date_array[0]);
    $month = intval($date_array[1]);
    $day = intval($date_array[2]);
    $hour = $minute = $second = 0;
    $unix_time = mktime($hour, $minute, $second, $month, $day, $year);
    return date('F jS Y', $unix_time);
  }
}
?>