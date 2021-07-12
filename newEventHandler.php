<?php
  /* High level overview of this PHP file. I am retrieving form data, 
  storing that data in a flat file, and then reading that data back and 
  displaying it to the user. */

  // Get the base file path. 
  $document_root = $_SERVER['DOCUMENT_ROOT'];

  // Require in the template for the website.
  require $document_root.'/models/Page.php';
  // Require in the Event class to create a new Event object. 
  require $document_root.'/models/Event.php';
  // Require in the ConfirmationCard component. 
  require $document_root.'/partials/ConfirmationCard.php';
  // Require in the User file for user validation.
  require $document_root.'/models/User.php';

  // Instantiate a Page object. 
  $page = new Page($title = "confirmation");
  // Get a handle on the database.
  $db = (new Database())->get_handle();
  
  // Retrieve the form data and store it in variables to be used later.
  $event_title = trim($_POST['event_title']);
  $event_date = trim($_POST['event_date']);
  $event_description = trim($_POST['event_description']);
 
  // Get the value of the cookie. 
  $user_id = intval($_COOKIE['auth']);
  $current_user = new User($user_id);
  
  // Create a new Event object which holds the data for a particular event. 
  $new_event = new Event($event_title, $event_date, $event_description);
  if($new_event->valid()){
    // Save event to the database.
    $query = "INSERT INTO events (userid, title, description, event_date) VALUES(?,?,?,?)";
    $stmt = $db->prepare($query);
    $user_id= $current_user->user_id;
    $stmt->bind_param('isss', $user_id, $event_title, $event_description, $event_date);
    $stmt->execute();

    if($stmt->affected_rows > 0){
      // Save was successful. 
      $query = "SELECT title, description, event_date FROM events WHERE eventid = ?";
      $event_id = $db->insert_id;//retrieve the primary key from the latest sql statement. 
      $stmt = $db->prepare($query);
      $stmt->bind_param('i', $event_id);
      $stmt->execute();
      $stmt->store_result();
      $stmt->bind_result($event_title, $event_description, $event_date);
      $stmt->fetch();
      $card = new ConfirmationCard($event_title, $event_date, $event_description);
    }else{
      $card = new ConfirmationCard('','','',$new_error = True);
      // Save was not successful. 
    }

    $content = "<div class='container-fluid'>
      <div class='row mt-3 justify-content-center'>
        <div class='col-xs- 12 col-sm-9 col-md-6 col-lg-3'>".$card->get_html()."
        </div>
      </div>
    </div>";

    $page->set_content($content);
    $page->render();
    $db->close();
    exit;
  }else{
    $page->set_content($new_event->get_errors());
    $page->render();
    $db->close();
    exit;
  }  
?>