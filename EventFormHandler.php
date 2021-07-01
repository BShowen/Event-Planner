<?php
  /* High level overview of this PHP file. I am retrieving form data, 
  storing that data in a flat file, and then reading that data back and 
  displaying it to the user. */

  // Get the base file path. 
  $document_root = $_SERVER['DOCUMENT_ROOT'];

  // Require in the Event class to create a new Event object. 
  require $document_root.'/models/Event.php';
  // Require in the ConfirmationCard component. 
  require $document_root.'/partials/ConfirmationCard.php';
  
  // Require in the database object then
  // get a handle on the database.
  require $document_root.'/Database.php';
  $db = new Database();
  $db = &$db->get_handle();
  
  // Retrieve the form data and store it in variables to be used later.
  $event_title = trim($_POST['event_title']);
  $event_date = trim($_POST['event_date']);
  $event_description = trim($_POST['event_description']);

  // Require in the template for the website
  // and instantiate a page object. 
  require $document_root.'/Page.php';
  $page = new Page($title = "confirmation"); 
  
  // Create a new Event object which holds the data for a particular event. 
  $new_event = new Event($event_title, $event_date, $event_description);
  if($new_event->valid()){
    $query = "INSERT INTO events (userid, title, description, event_date) VALUES(?,?,?,?)";
    $stmt = $db->prepare($query);
    $user_id= 1;
    $stmt->bind_param('isss', $user_id, $event_title, $event_description, $event_date);
    $stmt->execute();

    if($stmt->affected_rows > 0){
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
      echo 'There is an error in saving your data. Please try again.';
      exit();
    }

    $content = "<div class='container-fluid'>
      <div class='row mt-3 justify-content-center'>
        <div class='col-xs- 12 col-sm-9 col-md-6 col-lg-3'>".$card->render()."
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