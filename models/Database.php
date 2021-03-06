<?php  
class Database {
  public $db = null;
  
  function __construct(){
    // Get the base file path. 
    $document_root = $_SERVER['DOCUMENT_ROOT'].'/eventPlanner';
    // Require in the config file for retrieving sensitive data. 
    require $document_root."/.config.php";
    try{
      $this->db = new mysqli($CONFIG['HOST'], $CONFIG['USER_NAME'], $CONFIG['PASSWORD'], $CONFIG['DB_NAME']); 
      if(mysqli_connect_errno()){
        throw new Error('There was an error connecting to the DB.<br/>Check your environment vars.');
      }
    }catch(Error $e){
      echo $e->getMessage().'<br/>';
      exit();
    }
  }

  function &get_handle(){
    return $this->db;
  }

  // This function takes in an email address and returns true or false if the email exists or doesn't exist.
  function email_exists($email_address){
    $query = "SELECT EXISTS (SELECT userid FROM users WHERE email = ?)";
    $stmt = ($this->db)->prepare($query);
    $stmt->bind_param('s', $email_address);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($email_exists);
    $stmt->fetch();
    return $email_exists;
  }

  // This function generates a hash then inserts that hash into the password_reset_token field on the row corresponding to the 
  // email that is passed in. This function then returns that hash to the caller. 
  function insert_password_reset_token($email_address, $token){
    $query = "UPDATE users SET password_reset_token = ? WHERE email = ?";
    $stmt = ($this->db)->prepare($query);
    $stmt->bind_param('ss', $token, $email_address);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->affected_rows > 0;
  }

  // This function is used to find users only when the user is resetting their password. 
  // This function finds a user based on the password_reset_token. 
  // This function returns the userid or NULL if nothing was found. 
  function get_user_by_password_reset_token($reset_token){
    $query = "SELECT userid FROM users WHERE password_reset_token = ?";
    $stmt = ($this->db)->prepare($query);
    $stmt->bind_param('s', $reset_token);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($userid);
    $stmt->fetch();
    return $userid;
  }

  // This function takes in an id and retrieves that event from the database. 
  // This function returns an array of all the data fields for an event. 
  function get_event_by_id($event_id){
    $query = "SELECT title, description, event_date FROM events WHERE eventid = ?";
    $stmt = ($this->db)->prepare($query);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($title, $description, $date);
    $stmt->fetch();
    return [$title, $description, $date];
  }

  /* 
  This function returns all of the events for a particular user. 
  This function returns an array in the format 
  [
    ['first name', 'last name', 'event title', 'event date', 'event description'], 
    ['first name', 'last name', 'event title', 'event date', 'event description'], 
    ...
    ['first name', 'last name', 'event title', 'event date', 'event description'], 
  ]
  */
  public function get_events_for_user($user_id){
    $query = (
      'SELECT u.first, u.last, e.title, e.event_date, e.description 
      FROM users AS u JOIN events AS e 
      USING(userid) 
      WHERE userid = ?'
    );
    $stmt = ($this->db)->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($first, $last, $title, $event_date, $description);
    $events = [];
    while($stmt->fetch()){
      array_push($events, [$first, $last, $title, $event_date, $description]);
    }
    return $events;
  }

  /* 
  This function returns a list of all of the Events in the database.
  This function returns an array in the format 
  [
    ['first name', 'last name', 'event title', 'event date', 'event description'], 
    ['first name', 'last name', 'event title', 'event date', 'event description'], 
    ...
    ['first name', 'last name', 'event title', 'event date', 'event description'], 
  ]
  */
  public function get_all_events(){
    $query = 'SELECT u.first, u.last, e.title, e.event_date, e.description FROM users AS u JOIN events as e USING(userid)';
    $stmt = ($this->db)->prepare($query);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($first, $last, $title, $event_date, $description);
    $events = [];
    while($stmt->fetch()){
      array_push($events, [$first, $last, $title, $event_date, $description]);
    }
    return $events;
  }
}
?>