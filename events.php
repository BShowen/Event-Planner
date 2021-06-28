<?php
  // Get the base file path.
  $document_root = $_SERVER['DOCUMENT_ROOT'];

  // Require in the EventTable class.
  require $document_root.'/database/EventTable.php';
  // Require in the Header. 
  require $document_root.'/partials/Header.php';
  // Require in the navigation.
  require $document_root.'/partials/SiteNav.php';
  // Require in the Footer. 
  require $document_root.'/partials/Footer.php';

  // Instantiate a EventTable class so I can retrieve data from the database (flat file).
  $events_db = new EventTable("$document_root/flat_db/events.txt", "rb");
  
  // Retrieve all the events from the database. 
  $events = $events_db->get_events();

  // Instantiate a navigation component. 
  $nav = new SiteNav("events");

  Header::render();
?>
<!-- This is the container for the page content. -->
<div class="container-fluid">
  
  <?php 
  // Display the navigation component.
  $nav->render(); 
  ?>

  <!-- Row 2 for page content -->
  <div class="row mt-3 justify-content-center">
    <!-- Left page column. -->
    <div class="col-sm-9">
      <div class="card">
      <div class="card-header bg-dark text-light">
          <h3 class="card-title text-center pt-2">All Events</h3>
        </div>
        <div class="card-body">
          <table class=" table table-striped table-hover border-start border-end border-top">
            <thead>
              <th scope="col">Host</th>
              <th scope="col">Event Title</th>
              <th scope="col">Event Date</th>
              <th scope="col">Event Description</th>
              <th scope="col">Guests</th>
            </thead>
            <tbody>
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
                echo "<tr>
                  <td> Bradley Showen </td>
                  <td> $event_title </td>
                  <td> $event_date </td>
                  <td> $event_description </td>
                  <td> 0 </td>
                </tr>";
              }
            ?>
            </tbody>
          </table>
        </div>
      </div>  
    </div>
  </div>
</div>
<?php
  Footer::render()
?>