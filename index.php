<?php
  // Get the base file path. 
  $document_root = $_SERVER['DOCUMENT_ROOT'];

  // Require in the EventTable class.
  require $document_root.'/database/EventTable.php';
  // Require in the UserTable class.  
  require $document_root.'/database/UserTable.php';
  // Require in the Header for the page.
  require $document_root.'/partials/Header.php';
  // Require navigation for the page. 
  require $document_root.'/partials/SiteNav.php';
  // Require in the Footer for the page. 
  require $document_root.'/partials/Footer.php'; 
  
  
  // Instantiate a EventTable class so I can retrieve data from the database (flat file).
  $events_db = new EventTable("$document_root/flat_db/events.txt", "rb");
  
  // Retrieve 3 events from the database (flat file).
  $events = $events_db->get_events(3);

  // Instantiate a UserTable class so I can retrieve data from the database (flat file).
  $users_db = new UserTable("$document_root/flat_db/users.txt", "rb");
  
  // get the user that is currently logged in. 
  // Right now there is no way to log in or out. I will implement this when 
  // I start using MySQL. 
  $user_name = $users_db->get_current_user();
  
  // Close the connection to the database. 
  $users_db->close_file();

  // Instantiate a navigation component. 
  $nav = new SiteNav("dashboard");
  Header::render();
?>
<!-- This is the container for the page content. -->
<div class="container-fluid">

  <?php 
  // Display the navigation component. 
  $nav->render(); 
  ?>

  <!-- Row 2 for page content -->
  <div class="row mt-3">
    <!-- Left page column. -->
    <div class="col-sm-12 col-lg-6">  
      <!-- Row in the left page column -->
      <div class="col">
        <!-- Row content -->
        <div class="card border-0">
          <!-- Image container -->
          <div class="container">
            <div class="row justify-content-center">
              <div class="col-3">
                <img src="./bradleyshowen.png" alt="User photo" class="card-img-top img-thumbnail">
              </div>
            </div>
          </div>
          <div class="card-body">
            <h1 class="card-title text-center">
              <?php
                echo ucwords($user_name);
              ?>
            </h1>
          </div>
        </div>
      </div>

      <!-- This is the form for creating a new event. -->
      <div class="col justify-content-center">
        <div class="card bg-light border-0">
          <div class="card-header bg-dark text-light">
            <h3 class="card-title text-center">Create a new event</h3>
          </div>
          <div class="card-body">
            <form action="./EventFormHandler.php" method="POST">
              <div class="row justify-content-center">
                <div class="col-sm-8">
                  <label for="eventTitle" class="form-label mt-2">Event Title</label>
                  <input type="text" class="form-control" name="event_title" id="eventTitle">
                  
                  <label for="eventDate" class="form-label mt-2">Event Date</label>
                  <input type="date" class="form-control" name="event_date" id="eventDate">

                  <label for="eventDescription" class="form-label mt-2">Event Description</label>
                  <textarea name="event_description" id="eventDescription" class="form-control"></textarea>

                  <input class="btn btn-primary mt-2" type="submit" value="Submit">
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Right page column -->
    <div class="col mt-3 justify-content-center col-lg-6">
      <div class="card bg-light border-0">
        <div class="card-header bg-dark text-light">
          <h3 class="card-title text-center pt-2">Upcoming events</h3>
        </div>
        <div class="card-body">

          <?php
            /* The $events array holds a key/value array. I iterate through it
            and extract the keys and values out to variables by using extract(). 
            Each iteration does this and replaces the old contents of the variables 
            that were extracted by extract(). 
            */
            for($i = 0; $i < count($events); $i++){
              // Extract the keys out. Replace any name collisions. 
              extract($events[$i]);
              // Render the content in the DOM. 
              echo "<div class='card mt-3'>
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
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php Footer::render(); ?>
