<?php
/* High level overview of this PHP file. I am retrieving form data, 
storing that data in a flat file, and then reading that data back and 
displaying it to the user. */

// Get the base file path. 
$document_root = $_SERVER['DOCUMENT_ROOT'];

// Require in the template for the website.
require_once $document_root.'/models/Page.php';
// Require in the Event class to create a new Event object. 
require_once $document_root.'/models/Event.php';
// Require in the ConfirmationCard component. 
require_once $document_root.'/models/ConfirmationCard.php';
// Require in the User file for user validation.
require_once $document_root.'/models/User.php';

// Instantiate a Page object. 
$page = new Page($title = "confirmation");
$page->render_header();

// Retrieve the form data and store it in variables to be used later.
$event_title = trim($_POST['event_title']);
$event_date = trim($_POST['event_date']);
$event_description = trim($_POST['event_description']);

// Get the value of the cookie. 
$user_id = intval($_COOKIE['auth']);
$current_user = new User($user_id);

// Create a new Event object which holds the data for a particular event. 
$new_event = new Event($event_title, $event_date, $event_description, $user_id);
if($new_event->valid()){
  if($new_event->save_event()){
    // Save was successful. 
    $card = new ConfirmationCard($new_event->title, $new_event->date, $new_event->description);
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

  echo $content;
  $page->render_footer();
}else{
  echo $new_event->get_errors();
  $page->render_footer();
}  
?>