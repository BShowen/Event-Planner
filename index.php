<?php
  // Get the base file path. 
  $document_root = $_SERVER['DOCUMENT_ROOT'];

  // Require in the template for the website
  // and instantiate a page object. 
  require $document_root.'/Page.php';
  $page = new Page($title = "dashboard");
  
  // Require in the database object then
  // get a handle on the database.
  require $document_root.'/Database.php';
  $db = new Database();
  $db = &$db->get_handle();

  // get the current user
  $query = 'SELECT first, last FROM users LIMIT 1';
  $stmt = $db->prepare($query);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($first, $last);
  $stmt->fetch();
  $user_name = $first.' '.$last;
  $stmt = null;

  // Retrieve 3 events from the database (flat file).
  // $events = $events_db->get_events(3);
  $query = 'SELECT title, event_date, description FROM events';
  $stmt = $db->prepare($query);
  $stmt->execute();
  $stmt->store_result();
  $stmt->bind_result($event_title, $event_date, $event_description);

  // Create a long string that holds all of the cards for the page. 
  $event_elements = '';
  if($stmt->num_rows > 0){
    while($stmt->fetch()){
      $event_elements.="<div class='card mt-3'>
        <div class='card-header'>
          <h3 class='card-title'> $event_title </h3>
          <h5 class='card-subtitle'> $event_date </h5>
        </div>
        <div class='card-body'>  
          <p class='card-text'> $event_description </p>
          <button type='button' class='btn btn-primary'>More Details</button>
        </div>
      </div>";
    }
  }else{
    $event_elements.'No events';
  }

  $content = "<div class='row mt-3'>
    <!-- Left page column. -->
    <div class='col-sm-12 col-lg-6'>  
      <!-- Row in the left page column -->
      <div class='col'>
        <!-- Row content -->
        <div class='card border-0'>
          <!-- Image container -->
          <div class='container'>
            <div class='row justify-content-center'>
              <div class='col-3'>
                <img src='./bradleyshowen.png' alt='User photo' class='card-img-top img-thumbnail'>
              </div>
            </div>
          </div>
          <div class='card-body'>
            <h1 class='card-title text-center'>
              $user_name
            </h1>
          </div>
        </div>
      </div>

      <!-- This is the form for creating a new event. -->
      <div class='col justify-content-center'>
        <div class='card bg-light border-0'>
          <div class='card-header bg-dark text-light'>
            <h3 class='card-title text-center'>Create a new event</h3>
          </div>
          <div class='card-body'>
            <form action='./EventFormHandler.php' method='POST'>
              <div class='row justify-content-center'>
                <div class='col-sm-8'>
                  <label for='eventTitle' class='form-label mt-2'>Event Title</label>
                  <input type='text' class='form-control' name='event_title' id='eventTitle'>
                  
                  <label for='eventDate' class='form-label mt-2'>Event Date</label>
                  <input type='date' class='form-control' name='event_date' id='eventDate'>

                  <label for='eventDescription' class='form-label mt-2'>Event Description</label>
                  <textarea name='event_description' id='eventDescription' class='form-control'></textarea>

                  <input class='btn btn-primary mt-2' type='submit' value='Submit'>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Right page column -->
    <div class='col mt-3 justify-content-center col-lg-6'>
      <div class='card bg-light border-0'>
        <div class='card-header bg-dark text-light'>
          <h3 class='card-title text-center pt-2'>Upcoming events</h3>
        </div>
        <div class='card-body'>".$event_elements."</div>
      </div>
    </div>
  </div>";

  $db->close();
  $page->set_content($content);
  $page->render();
?>